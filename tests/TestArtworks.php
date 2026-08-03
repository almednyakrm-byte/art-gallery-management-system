<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use ArtworksModule;

class TestArtworks extends TestCase
{
    private $artworksModule;
    private $mockPdo;

    protected function setUp(): void
    {
        $this->mockPdo = $this->createMock(\PDO::class);
        $this->artworksModule = new ArtworksModule($this->mockPdo);
    }

    public function testGetArtworks()
    {
        $mockStatement = $this->createMock(\PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([]));

        $mockStatement->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Artwork 1'],
                ['id' => 2, 'name' => 'Artwork 2'],
            ]);

        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('SELECT * FROM artworks'))
            ->willReturn($mockStatement);

        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $result = $this->artworksModule->getArtworks($request, $response);
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testGetArtworkById()
    {
        $mockStatement = $this->createMock(\PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([1]));

        $mockStatement->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'Artwork 1']);

        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('SELECT * FROM artworks WHERE id = ?'))
            ->willReturn($mockStatement);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with($this->equalTo('id'))
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->artworksModule->getArtworkById($request, $response);
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
    }

    public function testCreateArtwork()
    {
        $mockStatement = $this->createMock(\PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(['name' => 'New Artwork']));

        $mockStatement->expects($this->once())
            ->method('lastInsertId')
            ->willReturn(3);

        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('INSERT INTO artworks (name) VALUES (?)'))
            ->willReturn($mockStatement);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'New Artwork']);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->artworksModule->createArtwork($request, $response);
        $this->assertIsArray($result);
        $this->assertEquals(3, $result['id']);
    }

    public function testUpdateArtwork()
    {
        $mockStatement = $this->createMock(\PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(['name' => 'Updated Artwork', 'id' => 1]));

        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('UPDATE artworks SET name = ? WHERE id = ?'))
            ->willReturn($mockStatement);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with($this->equalTo('id'))
            ->willReturn(1);

        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Updated Artwork']);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->artworksModule->updateArtwork($request, $response);
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
    }

    public function testDeleteArtwork()
    {
        $mockStatement = $this->createMock(\PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([1]));

        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('DELETE FROM artworks WHERE id = ?'))
            ->willReturn($mockStatement);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with($this->equalTo('id'))
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->artworksModule->deleteArtwork($request, $response);
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
    }
}