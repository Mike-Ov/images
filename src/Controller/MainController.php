<?php

namespace App\Controller;


use App\Model\UrlDto;
use App\Service\ImageService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'image', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('images.html.twig',);

    }

    #[Route('/', name: 'search_image', methods: ['POST'])]
    public function searchImage(#[MapRequestPayload] UrlDto $urlDto, ImageService $imageService): Response
    {
        $client = new Client();
        try {
            $resource = $client->get($urlDto->getUrl());
        } catch (GuzzleException $e) {
            $errors[] = ['url' => $urlDto->getUrl(), 'error' => $e->getMessage()];
            return $this->render('images.html.twig', ['errors' => $errors]);
        }

        $text = (string)$resource->getBody();

        $imageURLs = array_unique(ImageService::extractImagesUrls($text));
        $images = [];
        $errors = [];
        $filesSizeTotal = 0;

        foreach ($imageURLs as $imageURL) {
            if (strlen($imageURL) < 2) {
                continue;
            }
            if ($imageURL[0] === '/') {
                $imageURL = $urlDto->getUrl() . $imageURL;
            }
            try {
                $imageDto = $imageService->extractImage($imageURL);
                $images[] = $imageDto;
                $filesSizeTotal += $imageDto->getSize();
            } catch (\Exception $e) {
                $errors[] = ['url' => $imageURL, 'error' => $e->getMessage()];
            }
        }

        return $this->render('images.html.twig', ['images' => $images, 'errors' => $errors, 'filesSizeTotal' => $filesSizeTotal]);
    }
}
