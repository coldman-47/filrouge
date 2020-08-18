<?php

namespace App;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class Main
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        return $this->serializer = $serializer;
    }

    public function getAll($repo)
    {
        $data = $repo->findBy(['deleted' => false]);
        $dataJson = $this->serializer->serialize($data, "json", [
            "groups" => ["profil:read"]
        ]);
        return new JsonResponse($dataJson, Response::HTTP_CREATED, [], true);
    }
}
