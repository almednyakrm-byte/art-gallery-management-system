<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Controller\ArtistController;
use App\Repository\ArtistRepository;
use App\Entity\Artist;
use Doctrine\ORM\EntityManagerInterface;

class Testفنانين extends TestCase
{
    private $controller;
    private $router;
    private $tokenStorage;
    private $artistRepository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->artistRepository = $this->createMock(ArtistRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->controller = new ArtistController(
            $this->router,
            $this->tokenStorage,
            $this->artistRepository,
            $this->entityManager
        );
    }

    public function testGetAllArtists()
    {
        $this->artistRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                new Artist('John Doe', 'Artist'),
                new Artist('Jane Doe', 'Artist')
            ]);

        $response = $this->controller->getAllArtists();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testCreateArtist()
    {
        $artist = new Artist('John Doe', 'Artist');
        $this->artistRepository->expects($this->once())
            ->method('save')
            ->with($artist);

        $request = new Request([], [], ['artist' => $artist]);
        $response = $this->controller->createArtist($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateArtist()
    {
        $artist = new Artist('John Doe', 'Artist');
        $this->artistRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($artist);
        $this->artistRepository->expects($this->once())
            ->method('save')
            ->with($artist);

        $request = new Request([], [], ['artist' => $artist]);
        $response = $this->controller->updateArtist(1, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDeleteArtist()
    {
        $this->artistRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new Artist('John Doe', 'Artist'));
        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with(new Artist('John Doe', 'Artist'));
        $this->entityManager->expects($this->once())
            ->method('flush');

        $response = $this->controller->deleteArtist(1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the following scenarios:

*   `testGetAllArtists`: Tests the `getAllArtists` method by mocking the `findAll` method of the `ArtistRepository` to return a list of artists.
*   `testCreateArtist`: Tests the `createArtist` method by mocking the `save` method of the `ArtistRepository` to save a new artist.
*   `testUpdateArtist`: Tests the `updateArtist` method by mocking the `find` method of the `ArtistRepository` to return an existing artist, and then saving the updated artist.
*   `testDeleteArtist`: Tests the `deleteArtist` method by mocking the `find` method of the `ArtistRepository` to return an existing artist, removing the artist, and flushing the entity manager.

Note that this is a basic example and you may need to adjust it according to your specific requirements and implementation.