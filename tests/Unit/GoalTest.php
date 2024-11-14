<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\GoalController;
use App\Models\Goal;

class GoalTest extends TestCase
{
    protected $goalController;
    protected $createdGoalId;

    protected function setUp(): void
    {
        $this->goalController = new GoalController();

        $goalModel = new Goal();
        $result = $goalModel->create(1, 1, 1000.00, 500.00, 'Meta para testes');
        
        if ($result['success']) {
            $this->createdGoalId = $result['data']['id'] ?? null;
        } else {
            $this->markTestSkipped("Não foi possível criar uma meta para teste.");
        }
    }

    protected function tearDown(): void
    {
        if ($this->createdGoalId) {
            $this->goalController->delete($this->createdGoalId);
            $this->createdGoalId = null;
        }
    }

    public function testCreateGoal()
    {
        $_POST = [
            'user_id' => 1,
            'category_id' => 1,
            'target_amount' => 1500.00,
            'current_amount' => 750.00,
            'description' => 'Nova meta de teste'
        ];

        ob_start();
        $this->goalController->create();
        $output = ob_get_clean();
        $response = json_decode($output, true);

        $this->assertNotNull($response, 'A resposta para a criação da meta está nula.');
        $this->assertTrue($response['success'] ?? false, 'A criação da meta falhou.');
        $this->assertEquals('Meta criada com sucesso.', $response['message'] ?? '');

        if ($response['success']) {
            $this->createdGoalId = $response['data']['id'] ?? null;
        }
    }

    public function testReadGoal()
    {
        ob_start();
        $this->goalController->read($this->createdGoalId);
        $output = ob_get_clean();
        $response = json_decode($output, true);

        $this->assertNotNull($response, 'A resposta para a leitura da meta está nula.');
        $this->assertTrue($response['success'] ?? false, 'A leitura da meta falhou.');
        $this->assertArrayHasKey('data', $response);
        $this->assertEquals($this->createdGoalId, $response['data']['id']);
    }

    public function testUpdateGoal()
    {
        $_POST = [
            'user_id' => 1,
            'category_id' => 1,
            'target_amount' => 2000.00,
            'current_amount' => 1000.00,
            'description' => 'Meta atualizada de teste'
        ];

        ob_start();
        $this->goalController->update($this->createdGoalId);
        $output = ob_get_clean();
        $response = json_decode($output, true);

        $this->assertNotNull($response, 'A resposta para a atualização da meta está nula.');
        $this->assertTrue($response['success'] ?? false, 'A atualização da meta falhou.');
        $this->assertEquals('Meta atualizada com sucesso.', $response['message'] ?? '');
    }

    public function testDeleteGoal()
    {
        ob_start();
        $this->goalController->delete($this->createdGoalId);
        $output = ob_get_clean();
        $response = json_decode($output, true);

        $this->assertNotNull($response, 'A resposta para a exclusão da meta está nula.');
        $this->assertTrue($response['success'] ?? false, 'A exclusão da meta falhou.');
        $this->assertEquals('Meta deletada com sucesso.', $response['message'] ?? '');

        $this->createdGoalId = null;
    }
}