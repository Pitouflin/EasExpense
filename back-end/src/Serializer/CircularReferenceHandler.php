<?php

namespace App\Serializer;

class CircularReferenceHandler
{
    public function __invoke($object)
    {
        return $object->getId(); // ou une autre logique pour identifier les objets
    }
}
