<?php

namespace App\Models;

use App\Database\Database;

class Category {
	private $db;

	public function __construct() {
		$this->db = (new Database())->getConnection();
	}

	public function create($name, $description) {
		try {
			$stmt = $this->db->prepare("INSERT INTO category (name, description) VALUES (?, ?)");
			$stmt->bind_param("ss", $name, $description);
			$stmt->execute();
	
			return [
				"success" => true,
				"data" => ["id" => $this->db->insert_id],
				"message" => "Categoria criada com sucesso."
			];
		} catch (\mysqli_sql_exception $e) {
			return ["success" => false, "message" => "Erro ao criar categoria: " . $e->getMessage()];
		}
	}	

	public function read($id = null) {
		try {
			if ($id) {
				$stmt = $this->db->prepare("SELECT * FROM category WHERE id = ?");
				$stmt->bind_param("i", $id);
				$stmt->execute();
				return ["success" => true, "data" => $stmt->get_result()->fetch_assoc()];
			} else {
				$result = $this->db->query("SELECT * FROM category");
				return ["success" => true, "data" => $result->fetch_all(MYSQLI_ASSOC)];
			}
		} catch (\mysqli_sql_exception $e) {
			return ["success" => false, "message" => "Erro ao ler categorias: " . $e->getMessage()];
		}
	}

	public function update($id, $name, $description) {
		try {
			if (!$this->exists($id)) {
				return ["success" => false, "message" => "Erro: categoria não encontrada."];
			}

			$stmt = $this->db->prepare("UPDATE category SET name = ?, description = ? WHERE id = ?");
			$stmt->bind_param("ssi", $name, $description, $id);
			$stmt->execute();
			return ["success" => true, "message" => "Categoria atualizada com sucesso."];
		} catch (\mysqli_sql_exception $e) {
			return ["success" => false, "message" => "Erro ao atualizar categoria: " . $e->getMessage()];
		}
	}

	public function delete($id) {
		try {
			if (!$this->exists($id)) {
				return ["success" => false, "message" => "Erro: categoria não encontrada."];
			}
	
			$stmt = $this->db->prepare("DELETE FROM category WHERE id = ?");
			$stmt->bind_param("i", $id);
			$stmt->execute();
	
			return ["success" => true, "message" => "Categoria deletada com sucesso."];
		} catch (\mysqli_sql_exception $e) {
			return ["success" => false, "message" => "Erro ao deletar categoria: " . $e->getMessage()];
		}
	}	

	private function exists($id) {
		try {
			$stmt = $this->db->prepare("SELECT 1 FROM category WHERE id = ?");
			$stmt->bind_param("i", $id);
			$stmt->execute();
			return $stmt->get_result()->num_rows > 0;
		} catch (\mysqli_sql_exception $e) {
			return false;
		}
	}
}