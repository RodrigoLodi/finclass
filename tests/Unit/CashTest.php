<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\CashController;
use App\Models\Cash;

class CashTest extends TestCase
{
    protected $cashController;
    protected $createdCashId;

    protected function setUp(): void
    {
        $this->cashController = new CashController();

        $cashModel = new Cash();
        $result = $cashModel->create(1, 1, 'income', 100.00, '2023-11-15', 'Transação de teste');
        
        if ($result['success']) {
            $this->createdCashId = $result['data']['id'] ?? null;
        } else {
            $this->markTestSkipped("Não foi possível criar uma transação para teste.");
        }
    }

    protected function tearDown(): void
    {
        if ($this->createdCashId) {
            $this->cashController->delete($this->createdCashId);
            $this->createdCashId = null;
        }
    }

    public function testCreateCash()
    {
        $_POST = [
            'user_id' => 1,
            'category_id' => 1,
            'type' => 'expense',
            'amount' => 200.00,
            'date' => '2023-11-15',
            'description' => 'Nova transação de teste'
        ];

        ob_start();
        $this->cashController->create();
        $output = ob_get_clean();
        $response = json_decode($output, true);

        $this->assertNotNull($response, 'A resposta para a criação da transação está nula.');
        $this->assertTrue($response['success'] ?? false, 'A criação da transação falhou.');
        $this->assertEquals('Transação criada com sucesso.', $response['message'] ?? '');

        if ($response['success']) {
            $this->createdCashId = $response['data']['id'] ?? null;
        }
    }

    public function testReadCash()
    {
        ob_start();
        $this->cashController->read($this->createdCashId);
        $output = ob_get_clean();
        $response = json_decode($output, true);

        $this->assertNotNull($response, 'A resposta para a leitura da transação está nula.');
        $this->assertTrue($response['success'] ?? false, 'A leitura da transação falhou.');
        $this->assertArrayHasKey('data', $response);
        $this->assertEquals($this->createdCashId, $response['data']['id']);
    }

    public function testUpdateCash()
    {
        $_POST = [
            'user_id' => 1,
            'category_id' => 1,
            'type' => 'expense',
            'amount' => 300.00,
            'date' => '2023-11-15',
            'description' => 'Transação atualizada de teste'
        ];

        ob_start();
        $this->cashController->update($this->createdCashId);
        $output = ob_get_clean();
        $response = json_decode($output, true);

        $this->assertNotNull($response, 'A resposta para a atualização da transação está nula.');
        $this->assertTrue($response['success'] ?? false, 'A atualização da transação falhou.');
        $this->assertEquals('Transação atualizada com sucesso.', $response['message'] ?? '');
    }

    public function testDeleteCash()
    {
        ob_start();
        $this->cashController->delete($this->createdCashId);
        $output = ob_get_clean();
        $response = json_decode($output, true);

        $this->assertNotNull($response, 'A resposta para a exclusão da transação está nula.');
        $this->assertTrue($response['success'] ?? false, 'A exclusão da transação falhou.');
        $this->assertEquals('Transação deletada com sucesso.', $response['message'] ?? '');

        $this->createdCashId = null;
    }
}