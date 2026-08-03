<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Controller\OffersController;
use App\Repository\OffersRepository;
use App\Entity\Offers;

class Testعروض extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->repository = $this->createMock(OffersRepository::class);
        $this->controller = new OffersController($this->repository);

        $this->repository->method('findAll')->willReturn([
            new Offers(1, 'Offer 1', 'Description 1'),
            new Offers(2, 'Offer 2', 'Description 2'),
        ]);

        $this->repository->method('find')->willReturn(new Offers(1, 'Offer 1', 'Description 1'));

        $this->repository->method('create')->willReturn(new Offers(1, 'Offer 1', 'Description 1'));

        $this->repository->method('update')->willReturn(new Offers(1, 'Offer 1', 'Description 1'));

        $this->repository->method('delete')->willReturn(true);
    }

    public function testGetAllOffers()
    {
        $response = $this->controller->getAllOffers();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOfferById()
    {
        $response = $this->controller->getOfferById(1);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreateOffer()
    {
        $request = new Request([], [], ['name' => 'Offer 1', 'description' => 'Description 1']);
        $response = $this->controller->createOffer($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateOffer()
    {
        $request = new Request([], [], ['name' => 'Offer 1', 'description' => 'Description 1']);
        $response = $this->controller->updateOffer(1, $request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteOffer()
    {
        $response = $this->controller->deleteOffer(1);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// App\Controller\OffersController.php

namespace App\Controller;

use App\Repository\OffersRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class OffersController
{
    private $repository;

    public function __construct(OffersRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllOffers(): JsonResponse
    {
        $offers = $this->repository->findAll();
        return new JsonResponse($offers);
    }

    public function getOfferById(int $id): JsonResponse
    {
        $offer = $this->repository->find($id);
        return new JsonResponse($offer);
    }

    public function createOffer(Request $request): JsonResponse
    {
        $offer = $this->repository->create();
        // Set properties from request
        $offer->setName($request->get('name'));
        $offer->setDescription($request->get('description'));
        $this->repository->save($offer);
        return new JsonResponse($offer, Response::HTTP_CREATED);
    }

    public function updateOffer(int $id, Request $request): JsonResponse
    {
        $offer = $this->repository->find($id);
        // Set properties from request
        $offer->setName($request->get('name'));
        $offer->setDescription($request->get('description'));
        $this->repository->save($offer);
        return new JsonResponse($offer);
    }

    public function deleteOffer(int $id): JsonResponse
    {
        $this->repository->delete($id);
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}