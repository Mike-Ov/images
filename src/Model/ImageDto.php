<?php

namespace App\Model;


class ImageDto extends AbstractSerializer
{
    private readonly string $url;
    private readonly int $size;

    public function __construct(string $url, int $size)
    {
        $this->url = $url;
        $this->size = $size;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getSize(): int
    {
        return $this->size;
    }

}