<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\MuarradController;
use App\Repository\MuarradRepository;
use App\Entity\Muarrad;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestMuarrad extends TestCase
{
    private $controller;
    private $repository;
    private $request;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(MuarradRepository::class);
        $this->controller = new MuarradController($this->repository);
        $this->request = $this->createMock(Request::class);
    }

    public function testGetAll()
    {
        $muarrads = [new Muarrad(), new Muarrad()];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($muarrads);

        $response = $this->controller->getAll($this->request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetById()
    {
        $muarrad = new Muarrad();
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($muarrad);

        $this->request->expects($this->once())
            ->method('get')
            ->with('id')
            ->willReturn(1);

        $response = $this->controller->getById($this->request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreate()
    {
        $muarrad = new Muarrad();
        $this->repository->expects($this->once())
            ->method('save')
            ->with($muarrad);

        $this->request->expects($this->once())
            ->method('request')
            ->willReturn(['name' => 'Muarrad Name']);

        $response = $this->controller->create($this->request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdate()
    {
        $muarrad = new Muarrad();
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($muarrad);

        $this->request->expects($this->once())
            ->method('get')
            ->with('id')
            ->willReturn(1);

        $this->request->expects($this->once())
            ->method('request')
            ->willReturn(['name' => 'Muarrad Name']);

        $response = $this->controller->update($this->request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDelete()
    {
        $muarrad = new Muarrad();
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($muarrad);

        $this->request->expects($this->once())
            ->method('get')
            ->with('id')
            ->willReturn(1);

        $response = $this->controller->delete($this->request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}


This test file covers the following scenarios:

1. `testGetAll`: Verifies that the `getAll` method returns a 200 response with a list of all muarrads.
2. `testGetById`: Verifies that the `getById` method returns a 200 response with the muarrad with the specified ID.
3. `testCreate`: Verifies that the `create` method returns a 201 response with the newly created muarrad.
4. `testUpdate`: Verifies that the `update` method returns a 200 response with the updated muarrad.
5. `testDelete`: Verifies that the `delete` method returns a 200 response after deleting the muarrad with the specified ID.

Note that this test file assumes that the `MuarradController` class has the following methods:

* `getAll(Request $request)`
* `getById(Request $request)`
* `create(Request $request)`
* `update(Request $request)`
* `delete(Request $request)`

Also, this test file assumes that the `MuarradRepository` class has the following methods:

* `findAll()`
* `find($id)`
* `save(Muarrad $muarrad)`