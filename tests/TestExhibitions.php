<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use PDO;
use PDOStatement;

class TestExhibitions extends TestCase
{
    private $pdo;
    private $exhibitionController;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->exhibitionController = new ExhibitionController($this->pdo);
    }

    public function testGetExhibitions()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([]);
        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Exhibition 1'],
                ['id' => 2, 'name' => 'Exhibition 2'],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM exhibitions')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $result = $this->exhibitionController->getExhibitions($request, $response);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testGetExhibitionById()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);
        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'Exhibition 1']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM exhibitions WHERE id = ?')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);
        $response = $this->createMock(ResponseInterface::class);

        $result = $this->exhibitionController->getExhibitionById($request, $response);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testCreateExhibition()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['Exhibition 1']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO exhibitions (name) VALUES (?)')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Exhibition 1']);
        $response = $this->createMock(ResponseInterface::class);

        $result = $this->exhibitionController->createExhibition($request, $response);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(201, $result->getStatusCode());
    }

    public function testUpdateExhibition()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['Exhibition 1', 1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE exhibitions SET name = ? WHERE id = ?')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Exhibition 1']);
        $response = $this->createMock(ResponseInterface::class);

        $result = $this->exhibitionController->updateExhibition($request, $response);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testDeleteExhibition()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM exhibitions WHERE id = ?')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);
        $response = $this->createMock(ResponseInterface::class);

        $result = $this->exhibitionController->deleteExhibition($request, $response);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(204, $result->getStatusCode());
    }
}