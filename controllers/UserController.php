<?php

require_once("../database/Database.php");
require_once("../models/User.php");

class UserController {
	private $userModel;

	public function __construct() {
		$this->userModel = new User();
	}

	public function create() {
		$data = json_decode(file_get_contents("php://input"), true);
		$name = $data['name'] ?? null;
		$email = $data['email'] ?? null;
		$password = $data['password'] ?? null;

		if ($name && $email && $password) {
			$result = $this->userModel->create($name, $email, $password);
			echo json_encode(["success" => $result]);
		} else {
			http_response_code(400);
			echo json_encode(["error" => "Nome e email são obrigatórios."]);
		}
	}

	public function read($id = null) {
		$data = $this->userModel->read($id);
		echo json_encode($data);
	}

	public function update($id) {
		$data = json_decode(file_get_contents("php://input"), true);
		$name = $data['name'] ?? null;
		$email = $data['email'] ?? null;
		$password = $data['password'] ?? null;

		if ($name && $email) {
			$result = $this->userModel->update($id, $name, $email, $password);
			echo json_encode(["success" => $result, "message" => $result ? "Usuário atualizado com sucesso." : "Falha ao atualizar usuário."]);
		} else {
			http_response_code(400);
			echo json_encode(["error" => "Nome e email são obrigatórios."]);
		}
	}

	public function delete($id) {
		$result = $this->userModel->delete($id);
		echo json_encode(["success" => $result, "message" => $result ? "Usuário deletado com sucesso." : "Falha ao deletar usuário."]);
	}
}
