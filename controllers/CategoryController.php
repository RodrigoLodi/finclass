<?php

namespace App\Controllers;

use App\Models\Category;

class CategoryController {
	private $categoryModel;

	public function __construct() {
		$this->categoryModel = new Category();
	}

	public function index()
    {
        $viewPath = __DIR__ . '/../views/category/index.php';

        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo "View não encontrada: category/index.php";
        }
    }

	public function create() {
		$data = json_decode(file_get_contents("php://input"), true) ?? $_POST;
		$name = $data['name'] ?? null;
		$description = $data['description'] ?? null;
	
		if ($name) {
			$result = $this->categoryModel->create($name, $description);
	
			if (isset($result['success']) && $result['success']) {
				echo json_encode([
					"success" => true,
					"message" => $result['message'],
					"data" => $result['data']
				]);
			} else {
				echo json_encode([
					"success" => false,
					"message" => $result['message'] ?? "Falha ao criar categoria."
				]);
			}
		} else {
			http_response_code(400);
			echo json_encode(["success" => false, "message" => "O campo nome é obrigatório."]);
		}
	}	

	public function read($id = null) {
		if ($id === "all") {
            $data = $this->categoryModel->read();
        } else {
            $data = $this->categoryModel->read($id);
        }
		echo json_encode($data);
	}

	public function update($id) {
		$data = json_decode(file_get_contents("php://input"), true) ?? $_POST;
		$name = $data['name'] ?? null;
		$description = $data['description'] ?? null;
	
		if ($name) {
			$result = $this->categoryModel->update($id, $name, $description);
			echo json_encode([
				"success" => $result['success'],
				"message" => $result['message']
			]);
		} else {
			http_response_code(400);
			echo json_encode(["success" => false, "message" => "O campo nome é obrigatório."]);
		}
	}	

	public function delete($id) {
		$result = $this->categoryModel->delete($id);
	
		echo json_encode([
			"success" => $result['success'],
			"message" => $result['message']
		]);
	}	
}