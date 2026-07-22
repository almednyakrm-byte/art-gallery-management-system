<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\العملاتController;
use App\Repository\العملاتRepository;
use App\Entity\العملات;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class Testالعملات extends TestCase
{
    private $controller;
    private $repository;
    private $router;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(العملاتRepository::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->controller = new العملاتController($this->repository, $this->router);
    }

    public function testGetAll()
    {
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->getAll();
        $this->assertEquals($expectedResponse, $response->getContent());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOne()
    {
        $expectedResponse = ['data' => []];
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->getOne($id);
        $this->assertEquals($expectedResponse, $response->getContent());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOneNotFound()
    {
        $id = 1;
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->controller->getOne($id);
    }

    public function testCreate()
    {
        $expectedResponse = ['data' => []];
        $data = ['name' => 'العملة'];
        $this->repository->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn($expectedResponse['data']);

        $request = new Request();
        $request->request->set('name', $data['name']);
        $response = $this->controller->create($request);
        $this->assertEquals($expectedResponse, $response->getContent());
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate()
    {
        $expectedResponse = ['data' => []];
        $id = 1;
        $data = ['name' => 'العملة'];
        $this->repository->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn($expectedResponse['data']);

        $request = new Request();
        $request->request->set('name', $data['name']);
        $response = $this->controller->update($id, $request);
        $this->assertEquals($expectedResponse, $response->getContent());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testUpdateNotFound()
    {
        $id = 1;
        $data = ['name' => 'العملة'];
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn(null);

        $request = new Request();
        $request->request->set('name', $data['name']);
        $this->controller->update($id, $request);
    }

    public function testDelete()
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('delete')
            ->with($id);

        $response = $this->controller->delete($id);
        $this->assertEquals([], $response->getContent());
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteNotFound()
    {
        $id = 1;
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('delete')
            ->with($id)
            ->willReturn(null);

        $this->controller->delete($id);
    }
}


Note: This test file assumes that the `العملاتController` class has methods for each CRUD operation, and that the `العملاتRepository` class has methods for creating, updating, and deleting `العملات` entities. The `RouterInterface` is used to mock the router for generating URLs.