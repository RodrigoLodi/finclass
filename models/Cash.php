<?php

namespace App\Models;

use App\Database\Database;

class Cash {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function create($user_id, $category_id, $type, $amount, $date, $description) {
        try {
            $stmt = $this->db->prepare("INSERT INTO cash (user_id, category_id, type, amount, date, description) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iisdss", $user_id, $category_id, $type, $amount, $date, $description);
            $stmt->execute();
            return ["success" => true, "message" => "Transação criada com sucesso."];
        } catch (\mysqli_sql_exception $e) {
            return ["success" => false, "message" => "Erro ao criar transação: " . $e->getMessage()];
        }
    }

    public function read($id = null) {
        try {
            if ($id) {
                $stmt = $this->db->prepare("SELECT * FROM cash WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                return ["success" => true, "data" => $stmt->get_result()->fetch_assoc()];
            } else {
                $result = $this->db->query("SELECT * FROM cash");
                return ["success" => true, "data" => $result->fetch_all(MYSQLI_ASSOC)];
            }
        } catch (\mysqli_sql_exception $e) {
            return ["success" => false, "message" => "Erro ao ler transação: " . $e->getMessage()];
        }
    }

    public function update($id, $user_id, $category_id, $type, $amount, $date, $description) {
        try {
            if (!$this->exists($id)) {
                return ["success" => false, "message" => "Erro: transação não encontrada."];
            }

            $stmt = $this->db->prepare("UPDATE cash SET user_id = ?, category_id = ?, type = ?, amount = ?, date = ?, description = ? WHERE id = ?");
            $stmt->bind_param("iisdssi", $user_id, $category_id, $type, $amount, $date, $description, $id);
            $stmt->execute();
            return ["success" => true, "message" => "Transação atualizada com sucesso."];
        } catch (\mysqli_sql_exception $e) {
            return ["success" => false, "message" => "Erro ao atualizar transação: " . $e->getMessage()];
        }
    }

    public function delete($id) {
        try {
            if (!$this->exists($id)) {
                return ["success" => false, "message" => "Erro: transação não encontrada."];
            }

            $stmt = $this->db->prepare("DELETE FROM cash WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return ["success" => true, "message" => "Transação deletada com sucesso."];
        } catch (\mysqli_sql_exception $e) {
            return ["success" => false, "message" => "Erro ao deletar transação: " . $e->getMessage()];
        }
    }

    private function exists($id) {
        try {
            $stmt = $this->db->prepare("SELECT 1 FROM cash WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return $stmt->get_result()->num_rows > 0;
        } catch (\mysqli_sql_exception $e) {
            return false;
        }
    }
}