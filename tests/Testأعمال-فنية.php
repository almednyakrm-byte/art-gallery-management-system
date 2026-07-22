<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ArtisticWorksController;
use App\Repository\ArtisticWorksRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestArtisticWorks extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->repository = $this->createMock(ArtisticWorksRepository::class);
        $this->controller = new ArtisticWorksController($this->repository);
    }

    public function testGetArtisticWorks()
    {
        $this->repository->expects($this->once())
            ->method('getAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Work 1'],
                ['id' => 2, 'name' => 'Work 2'],
            ]);

        $response = $this->controller->getArtisticWorks();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([
            ['id' => 1, 'name' => 'Work 1'],
            ['id' => 2, 'name' => 'Work 2'],
        ], json_decode($response->getContent(), true));
    }

    public function testCreateArtisticWork()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdo->expects($this->once())
            ->method('exec')
            ->with('INSERT INTO artistic_works (name) VALUES (:name)');

        $response = $this->controller->createArtisticWork(['name' => 'New Work']);

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdateArtisticWork()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdo->expects($this->once())
            ->method('exec')
            ->with('UPDATE artistic_works SET name = :name WHERE id = :id');

        $response = $this->controller->updateArtisticWork(1, ['name' => 'Updated Work']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteArtisticWork()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdo->expects($this->once())
            ->method('exec')
            ->with('DELETE FROM artistic_works WHERE id = :id');

        $response = $this->controller->deleteArtisticWork(1);

        $this->assertEquals(200, $response->getStatusCode());
    }
}



// ArtisticWorksController.php

namespace App\Controller;

use App\Repository\ArtisticWorksRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ArtisticWorksController
{
    private $repository;

    public function __construct(ArtisticWorksRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getArtisticWorks(): Response
    {
        $artisticWorks = $this->repository->getAll();

        return new JsonResponse($artisticWorks, 200);
    }

    public function createArtisticWork(array $data): Response
    {
        $this->pdo->prepare('INSERT INTO artistic_works (name) VALUES (:name)')->execute($data);

        return new JsonResponse(null, 201);
    }

    public function updateArtisticWork(int $id, array $data): Response
    {
        $this->pdo->prepare('UPDATE artistic_works SET name = :name WHERE id = :id')->execute($data);

        return new JsonResponse(null, 200);
    }

    public function deleteArtisticWork(int $id): Response
    {
        $this->pdo->prepare('DELETE FROM artistic_works WHERE id = :id')->execute(['id' => $id]);

        return new JsonResponse(null, 200);
    }
}