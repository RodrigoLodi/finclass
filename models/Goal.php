<?php

namespace App\Models;

use App\Database\Database;

class Goal {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function create($user_id, $category_id, $target_amount, $current_amount, $description) {
        try {
            $stmt = $this->db->prepare("INSERT INTO goals (user_id, category_id, target_amount, current_amount, description) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iidds", $user_id, $category_id, $target_amount, $current_amount, $description);
            $stmt->execute();
            return ["success" => true, "message" => "Meta criada com sucesso."];
        } catch (\mysqli_sql_exception $e) {
            return ["success" => false, "message" => "Erro ao criar meta: " . $e->getMessage()];
        }
    }

    public function read($id = null) {
        try {
            if ($id) {
                $stmt = $this->db->prepare("SELECT * FROM goals WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                return ["success" => true, "data" => $stmt->get_result()->fetch_assoc()];
            } else {
                $result = $this->db->query("SELECT * FROM goals");
                return ["success" => true, "data" => $result->fetch_all(MYSQLI_ASSOC)];
            }
        } catch (\mysqli_sql_exception $e) {
            return ["success" => false, "message" => "Erro ao ler metas: " . $e->getMessage()];
        }
    }

    public function update($id, $user_id, $category_id, $target_amount, $current_amount, $description) {
        try {
            if (!$this->exists($id)) {
                return ["success" => false, "message" => "Erro: meta não encontrada."];
            }

            $stmt = $this->db->prepare("UPDATE goals SET user_id = ?, category_id = ?, target_amount = ?, current_amount = ?, description = ? WHERE id = ?");
            $stmt->bind_param("iiddsi", $user_id, $category_id, $target_amount, $current_amount, $description, $id);
            $stmt->execute();
            return ["success" => true, "message" => "Meta atualizada com sucesso."];
        } catch (\mysqli_sql_exception $e) {
            return ["success" => false, "message" => "Erro ao atualizar meta: " . $e->getMessage()];
        }
    }

    public function delete($id) {
        try {
            if (!$this->exists($id)) {
                return ["success" => false, "message" => "Erro: meta não encontrada."];
            }

            $stmt = $this->db->prepare("DELETE FROM goals WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return ["success" => true, "message" => "Meta deletada com sucesso."];
        } catch (\mysqli_sql_exception $e) {
            return ["success" => false, "message" => "Erro ao deletar meta: " . $e->getMessage()];
        }
    }

    private function exists($id) {
        try {
            $stmt = $this->db->prepare("SELECT 1 FROM goals WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return $stmt->get_result()->num_rows > 0;
        } catch (\mysqli_sql_exception $e) {
            return false;
        }
    }
}