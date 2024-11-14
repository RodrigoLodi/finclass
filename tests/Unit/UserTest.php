<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\UserController;
use App\Models\User;

class UserTest extends TestCase
{
    protected $userController;
    protected $createdUserId;

    protected function setUp(): void
    {
        $this->userController = new UserController();

        $userModel = new User();
        $result = $userModel->create('Test User', 'testuser@example.com', 'password123');

        if ($result['success']) {
            $this->createdUserId = $result['data']['id'];
        } else {
            $this->markTestSkipped("Não foi possível criar um usuário para teste.");
        }
    }

    protected function tearDown(): void
    {
        if ($this->createdUserId) {
            $this->userController->delete($this->createdUserId);
            $this->createdUserId = null;
        }
    }

    public function testCreateUser()
    {
        $user = new User();
        $result = $user->create('John Doe', 'john@example.com', 'password123');
        $this->assertNotNull($result, 'A resposta para a criação do usuário está nula.');
        $this->assertTrue($result['success'] ?? false, 'A criação do usuário falhou.');
    }

    public function testReadUser()
    {
        $user = new User();
        $result = $user->read($this->createdUserId);
        
        $this->assertNotNull($result, 'A resposta para a leitura do usuário está nula.');
        $this->assertTrue($result['success'] ?? false, 'A leitura do usuário falhou.');
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals($this->createdUserId, $result['data']['id']);
    }

    public function testUpdateUser()
    {
        $user = new User();
        $result = $user->update($this->createdUserId, 'Jane Doe', 'jane@example.com', 'newpassword123');
        
        $this->assertNotNull($result, 'A resposta para a atualização do usuário está nula.');
        $this->assertTrue($result['success'] ?? false, 'A atualização do usuário falhou.');
        $this->assertEquals('Usuário atualizado com sucesso.', $result['message'] ?? '');
    }

    public function testDeleteUser()
    {
        $user = new User();
        $result = $user->delete($this->createdUserId);
        
        $this->assertNotNull($result, 'A resposta para a exclusão do usuário está nula.');
        $this->assertTrue($result['success'] ?? false, 'A exclusão do usuário falhou.');
        $this->assertEquals('Usuário deletado com sucesso.', $result['message'] ?? '');

        $this->createdUserId = null;
    }
}
