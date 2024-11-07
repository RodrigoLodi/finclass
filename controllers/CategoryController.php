<?php

require_once("../models/Category.php");

class CategoryController {
	private $categoryModel;

	public function __construct() {
		$this->categoryModel = new Category();
	}

	public function create() {
		$data = json_decode(file_get_contents("php://input"), true);
		$name = $data['name'] ?? null;
		$description = $data['description'] ?? null;

		if ($name) {
			$result = $this->categoryModel->create($name, $description);
			echo json_encode(["success" => $result, "message" => $result ? "Categoria criada com sucesso." : "Falha ao criar categoria."]);
		} else {
			http_response_code(400);
			echo json_encode(["error" => "O campo nome é obrigatório."]);
		}
	}

	public function read($id = null) {
		$data = $this->categoryModel->read($id);
		echo json_encode($data);
	}

	public function update($id) {
		$data = json_decode(file_get_contents("php://input"), true);
		$name = $data['name'] ?? null;
		$description = $data['description'] ?? null;

		if ($name) {
			$result = $this->categoryModel->update($id, $name, $description);
			echo json_encode(["success" => $result, "message" => $result ? "Categoria atualizada com sucesso." : "Falha ao atualizar categoria."]);
		} else {
			http_response_code(400);
			echo json_encode(["error" => "O campo nome é obrigatório."]);
		}
	}

	public function delete($id) {
		$result = $this->categoryModel->delete($id);
		echo json_encode(["success" => $result, "message" => $result ? "Categoria deletada com sucesso." : "Falha ao deletar categoria."]);
	}
}