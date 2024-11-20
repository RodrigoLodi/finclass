<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Controllers\CashController;

class CashTest extends TestCase
{
    protected $cashController;
    protected $createdCashId;

    protected function setUp(): void
    {
        $this->cashController = new CashController();
    
        $_POST = [
            'user_id' => 1,
            'category_id' => 1,
            'type' => 'expense',
            'amount' => 300.00,
            'date' => '2023-11-15',
            'description' => 'Transação atualizada de teste'
        ];
        
    
        ob_start();
        $this->cashController->create();
        $output = ob_get_clean();
        $response = json_decode($output, true);
    
        if (isset($response['success']) && $response['success']) {
            $this->createdCashId = $response['data']['id'];
        } else {
            $this->markTestSkipped("Não foi possível criar uma transação para os testes.");
        }
    }
    
    protected function tearDown(): void
    {
        if ($this->createdCashId) {
            ob_start();
            $this->cashController->delete($this->createdCashId);
            ob_get_clean();
        }
        $this->createdCashId = null;
    }

    public function testCreateCash()
    {
        $_POST = [
            'user_id' => 1,
            'category_id' => 1,
            'type' => 'income',
            'amount' => 100.00,
            'date' => '2023-11-15',
            'description' => 'Transação de teste'
        ];

        ob_start();
        $this->cashController->create();
        $output = ob_get_clean();
        $response = json_decode($output, true);

        $this->assertNotNull($response, 'A resposta está nula.');
        $this->assertTrue($response['success'], 'A criação da transação falhou.');
        $this->assertArrayHasKey('data', $response, 'O retorno não contém a chave data.');
        $this->assertArrayHasKey('id', $response['data'], 'O retorno não contém o ID da transação.');

        $this->createdCashId = $response['data']['id'];
    }

    public function testReadCash()
    {
        ob_start();
        $this->cashController->read($this->createdCashId);
        $output = ob_get_clean();
        $response = json_decode($output, true);
    
        $this->assertNotNull($response, 'A resposta para a leitura está nula.');
        $this->assertTrue($response['success'], 'A leitura da transação falhou.');
        $this->assertArrayHasKey('data', $response, 'O retorno não contém a chave data.');
        $this->assertEquals($this->createdCashId, $response['data']['id'] ?? null, 'O ID retornado não corresponde ao esperado.');
    }
    

    public function testUpdateCash()
    {
        $_POST = [
            'user_id' => 1,
            'category_id' => 1,
            'type' => 'expense',
            'amount' => 400.00,
            'date' => '2023-11-16',
            'description' => 'Transação atualizada de teste (modificada)'
        ];
    
        file_put_contents('php://input', json_encode($_POST));
    
        ob_start();
        $this->cashController->update($this->createdCashId);
        $output = ob_get_clean();
    
        $output = trim($output);
    
        $response = json_decode($output, true);
    
        $this->assertNotNull($response, 'A resposta para a atualização está nula.');
        $this->assertTrue($response['success'] ?? false, 'A atualização da transação falhou.');
        $this->assertEquals('Transação atualizada com sucesso.', $response['message'] ?? '');
    }

    public function testDeleteCash()
    {
        ob_start();
        $this->cashController->delete($this->createdCashId);
        $output = ob_get_clean();
        $response = json_decode($output, true);
    
        $this->assertNotNull($response, 'A resposta para a exclusão está nula.');
        $this->assertTrue($response['success'], 'A exclusão da transação falhou.');
        $this->assertEquals('Transação deletada com sucesso.', $response['message'] ?? '');
    }
}