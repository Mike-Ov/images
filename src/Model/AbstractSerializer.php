<?php

namespace App\Model;


use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class AbstractSerializer implements \JsonSerializable
{
    /**
     * @return array|\ArrayObject|bool|float|int|mixed|string|null
     * @throws ExceptionInterface
     */
    public function jsonSerialize(): mixed
    {
        $classMetadataFactory = new ClassMetadataFactory(new AttributeLoader());
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        return (new Serializer([$normalizer]))->normalize($this);
    }
}