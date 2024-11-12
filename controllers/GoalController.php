<?php

namespace App\Controllers;

use App\Models\Goal;
class GoalController {
	private $goalModel;

	public function __construct() {
		$this->goalModel = new Goal();
	}

	public function create() {
		$data = json_decode(file_get_contents("php://input"), true);
		$user_id = $data['user_id'] ?? null;
		$category_id = $data['category_id'] ?? null;
		$target_amount = $data['target_amount'] ?? null;
		$current_amount = $data['current_amount'] ?? 0;
		$description = $data['description'] ?? null;

		if ($user_id && $category_id && $target_amount) {
			$result = $this->goalModel->create($user_id, $category_id, $target_amount, $current_amount, $description);
			echo json_encode(["success" => $result, "message" => $result ? "Meta criada com sucesso." : "Falha ao criar meta."]);
		} else {
			http_response_code(400);
			echo json_encode(["error" => "Campos obrigatórios: user_id, category_id, target_amount."]);
		}
	}

	public function read($id = null) {
		$data = $this->goalModel->read($id);
		echo json_encode($data);
	}

	public function update($id) {
		$data = json_decode(file_get_contents("php://input"), true);
		$user_id = $data['user_id'] ?? null;
		$category_id = $data['category_id'] ?? null;
		$target_amount = $data['target_amount'] ?? null;
		$current_amount = $data['current_amount'] ?? null;
		$description = $data['description'] ?? null;

		if ($user_id && $category_id && $target_amount && $current_amount !== null) {
			$result = $this->goalModel->update($id, $user_id, $category_id, $target_amount, $current_amount, $description);
			echo json_encode(["success" => $result, "message" => $result ? "Meta atualizada com sucesso." : "Falha ao atualizar meta."]);
		} else {
			http_response_code(400);
			echo json_encode(["error" => "Todos os campos são obrigatórios."]);
		}
	}

	public function delete($id) {
		$result = $this->goalModel->delete($id);
		echo json_encode(["success" => $result, "message" => $result ? "Meta deletada com sucesso." : "Falha ao deletar meta."]);
	}
}