<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\MustahreinController;
use App\Repository\MustahreinRepository;
use App\Entity\Mustahrein;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit\Framework\MockObject\MockObject;

class TestMustahrein extends TestCase
{
    private $controller;
    private $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(MustahreinRepository::class);
        $this->controller = new MustahreinController($this->repository);
    }

    public function testGetAllMustahrein()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([new Mustahrein()]);

        $response = $this->controller->getAllMustahrein();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetMustahreinById()
    {
        $mustahrein = new Mustahrein();
        $mustahrein->setId(1);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($mustahrein);

        $response = $this->controller->getMustahreinById(1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testCreateMustahrein()
    {
        $mustahrein = new Mustahrein();
        $mustahrein->setName('Mustahrein Name');

        $this->repository->expects($this->once())
            ->method('save')
            ->with($mustahrein)
            ->willReturn($mustahrein);

        $request = new Request();
        $request->request->set('name', 'Mustahrein Name');

        $response = $this->controller->createMustahrein($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateMustahrein()
    {
        $mustahrein = new Mustahrein();
        $mustahrein->setId(1);
        $mustahrein->setName('Mustahrein Name');

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($mustahrein);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($mustahrein)
            ->willReturn($mustahrein);

        $request = new Request();
        $request->request->set('name', 'Mustahrein Name');

        $response = $this->controller->updateMustahrein(1, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDeleteMustahrein()
    {
        $mustahrein = new Mustahrein();
        $mustahrein->setId(1);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($mustahrein);

        $this->repository->expects($this->once())
            ->method('remove')
            ->with($mustahrein);

        $response = $this->controller->deleteMustahrein(1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}