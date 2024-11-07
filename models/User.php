<?php

require_once("../database/Database.php");

class User {
	private $db;

	public function __construct() {
		$this->db = (new DataBase())->getConnection();
	}

	public function create($name, $email, $password) {
		try {
			$stmt = $this->db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
			$stmt->bind_param("sss", $name, $email, $password);
			$stmt->execute();
			return ["success" => true, "message" => "Usuário criado com sucesso."];
		} catch (\mysqli_sql_exception $e) {
			return ["success" => false, "message" => "Erro ao criar usuário: " . $e->getMessage()];
		}
	}

	public function read($id = null) {
		try {
			if ($id) {
				$stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
				$stmt->bind_param("i", $id);
				$stmt->execute();
				return ["success" => true, "data" => $stmt->get_result()->fetch_assoc()];
			} else {
				$result = $this->db->query("SELECT * FROM users");
				return ["success" => true, "data" => $result->fetch_all(MYSQLI_ASSOC)];
			}
		} catch (\mysqli_sql_exception $e) {
			return ["success" => false, "message" => "Erro ao ler usuários: " . $e->getMessage()];
		}
	}

	public function update($id, $name, $email, $password) {
		try {
			if (!$this->exists($id)) {
				return ["success" => false, "message" => "Erro: usuário não encontrado."];
			}

			$stmt = $this->db->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
			$stmt->bind_param("sssi", $name, $email, $password, $id);
			$stmt->execute();
			return ["success" => true, "message" => "Usuário atualizado com sucesso."];
		} catch (\mysqli_sql_exception $e) {
			return ["success" => false, "message" => "Erro ao atualizar usuário: " . $e->getMessage()];
		}
	}

	public function delete($id) {
		try {
			if (!$this->exists($id)) {
				return ["success" => false, "message" => "Erro: usuário não encontrado."];
			}

			$stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
			$stmt->bind_param("i", $id);
			$stmt->execute();
			return ["success" => true, "message" => "Usuário deletado com sucesso."];
		} catch (\mysqli_sql_exception $e) {
			return ["success" => false, "message" => "Erro ao deletar usuário: " . $e->getMessage()];
		}
	}

	private function exists($id) {
		try {
			$stmt = $this->db->prepare("SELECT 1 FROM users WHERE id = ?");
			$stmt->bind_param("i", $id);
			$stmt->execute();
			return $stmt->get_result()->num_rows > 0;
		} catch (\mysqli_sql_exception $e) {
			return false;
		}
	}
}