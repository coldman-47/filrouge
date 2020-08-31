<?php

use App\Controller\BriefController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class TestBriefAdd extends WebTestCase
{
    public function testAdd()
    {
        $result = static::createClient();
        $result->request('GET', '/api/formateur/promo/2/briefs');

        $this->assertEquals(200, $result->getResponse()->getStatusCode());
    }
}
