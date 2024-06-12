<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class UrlDto
{
    #[Assert\Url()]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
   private string $url;

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;
        return $this;
    }
}