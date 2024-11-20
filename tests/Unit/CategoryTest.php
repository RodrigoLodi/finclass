<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Controllers\CategoryController;

class CategoryTest extends TestCase
{
    protected $categoryController;
    protected $createdCategoryId;

    protected function setUp(): void
    {
        $this->categoryController = new CategoryController();

        $_POST = [
            'name' => 'Categoria de Teste',
            'description' => 'Descrição da categoria de teste'
        ];

        ob_start();
        $this->categoryController->create();
        $output = ob_get_clean();
        $response = json_decode($output, true);

        if (isset($response['success']) && $response['success']) {
            $this->createdCategoryId = $response['data']['id'] ?? null;
        } else {
            $this->markTestSkipped("Não foi possível criar uma categoria para os testes.");
        }
    }

    protected function tearDown(): void
    {
        if ($this->createdCategoryId) {
            ob_start();
            $this->categoryController->delete($this->createdCategoryId);
            ob_get_clean();
        }
        $this->createdCategoryId = null;
    }

    public function testCreateCategory()
    {
        $_POST = [
            'name' => 'Categoria de Teste para Criação',
            'description' => 'Descrição da categoria de teste para criação'
        ];

        ob_start();
        $this->categoryController->create();
        $output = ob_get_clean();
        $response = json_decode($output, true);

        $this->assertNotNull($response, 'A resposta está nula.');
        $this->assertTrue($response['success'] ?? false, 'A criação da categoria falhou.');
        $this->assertArrayHasKey('data', $response, 'O retorno não contém a chave data.');
        $this->assertArrayHasKey('id', $response['data'], 'O retorno não contém o ID da categoria.');

        $this->createdCategoryId = $response['data']['id'];
    }

    public function testReadCategory()
    {
        ob_start();
        $this->categoryController->read($this->createdCategoryId);
        $output = ob_get_clean();
        $response = json_decode($output, true);

        $this->assertNotNull($response, 'A resposta para a leitura está nula.');
        $this->assertTrue($response['success'] ?? false, 'A leitura da categoria falhou.');
        $this->assertArrayHasKey('data', $response, 'O retorno não contém a chave data.');
        $this->assertEquals($this->createdCategoryId, $response['data']['id'] ?? null, 'O ID retornado não corresponde ao esperado.');
    }

    public function testUpdateCategory()
    {
        $_POST = [
            'name' => 'Categoria Atualizada',
            'description' => 'Descrição atualizada da categoria'
        ];

        file_put_contents('php://input', json_encode($_POST));

        ob_start();
        $this->categoryController->update($this->createdCategoryId);
        $output = ob_get_clean();

        $output = trim($output);

        $response = json_decode($output, true);

        $this->assertNotNull($response, 'A resposta para a atualização está nula.');
        $this->assertTrue($response['success'] ?? false, 'A atualização da categoria falhou.');
        $this->assertEquals('Categoria atualizada com sucesso.', $response['message'] ?? '');
    }

    public function testDeleteCategory()
    {
        $this->assertNotNull($this->createdCategoryId, 'ID da categoria criada está nulo.');
    
        ob_start();
        $this->categoryController->delete($this->createdCategoryId);
        $output = ob_get_clean();
        $response = json_decode($output, true);
    
        $this->assertNotNull($response, 'A resposta para a exclusão está nula.');
        $this->assertTrue($response['success'] ?? false, 'A exclusão da categoria falhou.');
        $this->assertEquals('Categoria deletada com sucesso.', $response['message'] ?? '');
    }
    
}