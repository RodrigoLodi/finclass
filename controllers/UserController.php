<?php

namespace App\Controllers;

use App\Models\User;

class UserController {
	private $userModel;

	public function __construct() {
		$this->userModel = new User();
	}

	public function index()
    {
        $viewPath = __DIR__ . '/../views/user/index.php';

        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo "View não encontrada: user/index.php";
        }
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
		if ($id === "all") {
            $data = $this->userModel->read();
        } else {
            $data = $this->userModel->read($id);
        }
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
