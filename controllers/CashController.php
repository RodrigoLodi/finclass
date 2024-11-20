<?php

namespace App\Controllers;

use App\Models\Cash;

class CashController {
	private $cashModel;

	public function __construct() {
		$this->cashModel = new Cash();
	}

	public function index()
    {
        $viewPath = __DIR__ . '/../views/cash/index.php';

        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo "View não encontrada: cash/index.php";
        }
    }

	public function create() {
		$data = json_decode(file_get_contents("php://input"), true) ?? $_POST;
		$user_id = $data['user_id'] ?? null;
		$category_id = $data['category_id'] ?? null;
		$type = $data['type'] ?? null;
		$amount = $data['amount'] ?? null;
		$date = $data['date'] ?? null;
		$description = $data['description'] ?? null;
	
		if ($user_id && $category_id && $type && $amount && $date) {
			$result = $this->cashModel->create($user_id, $category_id, $type, $amount, $date, $description);
	
			if (isset($result['success']) && $result['success']) {
				echo json_encode([
					"success" => true,
					"message" => "Transação criada com sucesso.",
					"data" => $result['data']
				]);
			} else {
				echo json_encode([
					"success" => false,
					"message" => $result['message'] ?? "Falha ao criar transação."
				]);
			}
		} else {
			http_response_code(400);
			echo json_encode([
				"success" => false,
				"error" => "Campos obrigatórios: user_id, category_id, type, amount e date."
			]);
		}
	}

	public function read($id = null) {
        if ($id === "all") {
            $data = $this->cashModel->read();
        } else {
            $data = $this->cashModel->read($id);
        }

        echo json_encode($data);
    }

	public function update($id) {
		header('Content-Type: application/json');
		$data = json_decode(file_get_contents("php://input"), true) ?? $_POST;
	
		if (!$data) {
			echo json_encode(["success" => false, "message" => "Nenhum dado recebido."]);
			return;
		}
	
		$user_id = $data['user_id'] ?? null;
		$category_id = $data['category_id'] ?? null;
		$type = $data['type'] ?? null;
		$amount = $data['amount'] ?? null;
		$date = $data['date'] ?? null;
		$description = $data['description'] ?? null;
	
		if ($user_id && $category_id && $type && $amount && $date) {
			$result = $this->cashModel->update($id, $user_id, $category_id, $type, $amount, $date, $description);
	
			echo json_encode([
				"success" => $result['success'],
				"message" => $result['message']
			]);
		} else {
			http_response_code(400);
			echo json_encode(["success" => false, "message" => "Todos os campos são obrigatórios."]);
		}
	}
	
	public function delete($id) {
		$result = $this->cashModel->delete($id);
		echo json_encode([
			"success" => $result['success'],
			"message" => $result['message']
		]);
	}	
}