<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use PDO;
use PDOStatement;

class TestArtists extends TestCase
{
    private $pdo;
    private $artistController;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->artistController = new ArtistController($this->pdo);
    }

    public function testGetAllArtists()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([]);

        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Artist 1'],
                ['id' => 2, 'name' => 'Artist 2'],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM artists')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $result = $this->artistController->getAllArtists($request, $response);

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals([
            ['id' => 1, 'name' => 'Artist 1'],
            ['id' => 2, 'name' => 'Artist 2'],
        ], json_decode($result->getBody()->getContents(), true));
    }

    public function testGetArtistById()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'Artist 1']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM artists WHERE id = ?')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->artistController->getArtistById($request, $response);

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals(['id' => 1, 'name' => 'Artist 1'], json_decode($result->getBody()->getContents(), true));
    }

    public function testCreateArtist()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['name' => 'New Artist']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO artists (name) VALUES (?)')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'New Artist']);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->artistController->createArtist($request, $response);

        $this->assertEquals(201, $result->getStatusCode());
        $this->assertEquals(['message' => 'Artist created successfully'], json_decode($result->getBody()->getContents(), true));
    }

    public function testUpdateArtist()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1, 'Updated Artist']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE artists SET name = ? WHERE id = ?')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Updated Artist']);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->artistController->updateArtist($request, $response);

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals(['message' => 'Artist updated successfully'], json_decode($result->getBody()->getContents(), true));
    }

    public function testDeleteArtist()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM artists WHERE id = ?')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->artistController->deleteArtist($request, $response);

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals(['message' => 'Artist deleted successfully'], json_decode($result->getBody()->getContents(), true));
    }
}