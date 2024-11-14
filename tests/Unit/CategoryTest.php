<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\CategoryController;
use App\Models\Category;

class CategoryTest extends TestCase
{
    protected $categoryController;
    protected $createdCategoryId;

    protected function setUp(): void
    {
        $this->categoryController = new CategoryController();

        $categoryModel = new Category();
        $result = $categoryModel->create('Test Category', 'Description for test category');
        
        if ($result['success']) {
            $this->createdCategoryId = $result['data']['id'] ?? null;
        } else {
            $this->markTestSkipped("Não foi possível criar uma categoria para teste.");
        }
    }

    protected function tearDown(): void
    {
        if ($this->createdCategoryId) {
            $this->categoryController->delete($this->createdCategoryId);
            $this->createdCategoryId = null;
        }
    }

    public function testCreateCategory()
    {
        $_POST = [
            'name' => 'New Test Category',
            'description' => 'Description for new test category'
        ];

        ob_start();
        $this->categoryController->create();
        $output = ob_get_clean();
        $response = json_decode($output, true);

        $this->assertNotNull($response, 'A resposta para a criação da categoria está nula.');
        $this->assertTrue($response['success'] ?? false, 'A criação da categoria falhou.');
        $this->assertEquals('Categoria criada com sucesso.', $response['message'] ?? '');

        if ($response['success']) {
            $this->createdCategoryId = $response['data']['id'] ?? null;
        }
    }

    public function testReadCategory()
    {
        ob_start();
        $this->categoryController->read($this->createdCategoryId);
        $output = ob_get_clean();
        $response = json_decode($output, true);

        $this->assertNotNull($response, 'A resposta para a leitura da categoria está nula.');
        $this->assertTrue($response['success'] ?? false, 'A leitura da categoria falhou.');
        $this->assertArrayHasKey('data', $response);
        $this->assertEquals($this->createdCategoryId, $response['data']['id']);
    }

    public function testUpdateCategory()
    {
        $_POST = [
            'name' => 'Updated Category Name',
            'description' => 'Updated description for category'
        ];

        ob_start();
        $this->categoryController->update($this->createdCategoryId);
        $output = ob_get_clean();
        $response = json_decode($output, true);

        $this->assertNotNull($response, 'A resposta para a atualização da categoria está nula.');
        $this->assertTrue($response['success'] ?? false, 'A atualização da categoria falhou.');
        $this->assertEquals('Categoria atualizada com sucesso.', $response['message'] ?? '');
    }

    public function testDeleteCategory()
    {
        ob_start();
        $this->categoryController->delete($this->createdCategoryId);
        $output = ob_get_clean();
        $response = json_decode($output, true);

        $this->assertNotNull($response, 'A resposta para a exclusão da categoria está nula.');
        $this->assertTrue($response['success'] ?? false, 'A exclusão da categoria falhou.');
        $this->assertEquals('Categoria deletada com sucesso.', $response['message'] ?? '');

        $this->createdCategoryId = null;
    }
}