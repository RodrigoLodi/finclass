<?php

require_once("../models/Cash.php");

class CashController {
	private $cashModel;

	public function __construct() {
		$this->cashModel = new Cash();
	}

	public function create() {
		$data = json_decode(file_get_contents("php://input"), true);
		$user_id = $data['user_id'] ?? null;
		$category_id = $data['category_id'] ?? null;
		$type = $data['type'] ?? null;
		$amount = $data['amount'] ?? null;
		$date = $data['date'] ?? null;
		$description = $data['description'] ?? null;

		if ($user_id && $category_id && $type && $amount && $date) {
			$result = $this->cashModel->create($user_id, $category_id, $type, $amount, $date, $description);
			echo json_encode(["success" => $result, "message" => $result ? "Transação criada com sucesso." : "Falha ao criar transação."]);
		} else {
			http_response_code(400);
			echo json_encode(["error" => "Campos obrigatórios: user_id, category_id, type, amount e date."]);
		}
	}

	public function read($id = null) {
		$data = $this->cashModel->read($id);
		echo json_encode($data);
	}

	public function update($id) {
		$data = json_decode(file_get_contents("php://input"), true);
		$user_id = $data['user_id'] ?? null;
		$category_id = $data['category_id'] ?? null;
		$type = $data['type'] ?? null;
		$amount = $data['amount'] ?? null;
		$date = $data['date'] ?? null;
		$description = $data['description'] ?? null;

		if ($user_id && $category_id && $type && $amount && $date) {
			$result = $this->cashModel->update($id, $user_id, $category_id, $type, $amount, $date, $description);
			echo json_encode(["success" => $result, "message" => $result ? "Transação atualizada com sucesso." : "Falha ao atualizar transação."]);
		} else {
			http_response_code(400);
			echo json_encode(["error" => "Todos os campos são obrigatórios."]);
		}
	}

	public function delete($id) {
		$result = $this->cashModel->delete($id);
		echo json_encode(["success" => $result, "message" => $result ? "Transação deletada com sucesso." : "Falha ao deletar transação."]);
	}
}