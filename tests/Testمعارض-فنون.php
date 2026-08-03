<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\MaaradFann;
use App\Repository\MaaradFannRepository;
use App\Service\MaaradFannService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestMaaradFann extends TestCase
{
    private $maaradFannService;
    private $maaradFannRepository;
    private $maaradFannController;

    protected function setUp(): void
    {
        $this->maaradFannRepository = $this->createMock(MaaradFannRepository::class);
        $this->maaradFannService = $this->createMock(MaaradFannService::class);
        $this->maaradFannController = new MaaradFann($this->maaradFannRepository, $this->maaradFannService);
    }

    public function testGetMaaradFanns(): void
    {
        $expectedResponse = ['maarad_fanns' => []];
        $this->maaradFannRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $request = new Request();
        $response = $this->maaradFannController->getMaaradFanns($request);

        $this->assertEquals($expectedResponse, $response->getContent());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testPostMaaradFann(): void
    {
        $expectedResponse = ['message' => 'Maarad Fann created successfully'];
        $this->maaradFannRepository->expects($this->once())
            ->method('save')
            ->with($this->anything());

        $request = new Request([], [], ['maarad_fann' => ['name' => 'Maarad Fann']]);
        $response = $this->maaradFannController->postMaaradFann($request);

        $this->assertEquals($expectedResponse, $response->getContent());
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testPutMaaradFann(): void
    {
        $expectedResponse = ['message' => 'Maarad Fann updated successfully'];
        $this->maaradFannRepository->expects($this->once())
            ->method('update')
            ->with($this->anything());

        $request = new Request([], [], ['maarad_fann' => ['name' => 'Maarad Fann']]);
        $response = $this->maaradFannController->putMaaradFann(1, $request);

        $this->assertEquals($expectedResponse, $response->getContent());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteMaaradFann(): void
    {
        $expectedResponse = ['message' => 'Maarad Fann deleted successfully'];
        $this->maaradFannRepository->expects($this->once())
            ->method('delete')
            ->with(1);

        $request = new Request();
        $response = $this->maaradFannController->deleteMaaradFann(1, $request);

        $this->assertEquals($expectedResponse, $response->getContent());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}


Note: This test file assumes that the `MaaradFann` controller, `MaaradFannRepository` and `MaaradFannService` are already created and are located in the `App\Controller`, `App\Repository` and `App\Service` namespaces respectively. The test file also assumes that the `MaaradFann` controller has methods `getMaaradFanns`, `postMaaradFann`, `putMaaradFann` and `deleteMaaradFann` which handle the CRUD operations.