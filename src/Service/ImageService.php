<?php

namespace App\Service;

use App\Model\ImageDto;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ImageService
{
    /**
     * @throws GuzzleException
     */
    public function extractImage(string $imageURL): ?ImageDto
    {
        $client = new Client();

        $data = (string)$client->request('GET', $imageURL)->getBody();
        preg_match("/.*[.]([^\/][a-zA-Z]+)\??[^\/]*$/", $imageURL, $formatImage);
        if ($formatImage[1]) {
            $fileSize = strlen($data);
            return new ImageDto($imageURL, $fileSize);
        }

        return null;
    }

    public static function extractImagesUrls(string $content): array
    {
        preg_match_all("/<\s*img\s*src\s*=\s*[\"']?([^\"'>]*)/im", $content, $imageSources);

        return $imageSources[1];
    }
}