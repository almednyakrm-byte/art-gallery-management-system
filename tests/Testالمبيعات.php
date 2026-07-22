<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response;

class Testالمبيعات extends TestCase
{
    private $pdo;
    private $request;
    private $response;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(\PDO::class);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
    }

    public function testGetالمبيعات()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM المبيعات')
            ->willReturn($this->createMock(\PDOStatement::class));

        $controller = new المبيعاتController($this->pdo);
        $response = $controller->getالمبيعات($this->request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPostالمبيعات()
    {
        $data = ['name' => 'Test المبيعات', 'price' => 10.99];
        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($data);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO المبيعات (name, price) VALUES (:name, :price)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $controller = new المبيعاتController($this->pdo);
        $response = $controller->postالمبيعات($this->request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testPutالمبيعات()
    {
        $data = ['name' => 'Updated المبيعات', 'price' => 12.99];
        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($data);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE المبيعات SET name = :name, price = :price WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $controller = new المبيعاتController($this->pdo);
        $response = $controller->putالمبيعات($this->request, 1);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteالمبيعات()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM المبيعات WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $controller = new المبيعاتController($this->pdo);
        $response = $controller->deleteالمبيعات($this->request, 1);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(204, $response->getStatusCode());
    }
}