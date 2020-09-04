<?php

use App\Controller\BriefController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Serializer\SerializerInterface;

class TestBriefAdd extends WebTestCase
{
    public function authentification(string $user, string $pwd): KernelBrowser
    {
        $client = static::createClient();
        $idconnexion = ['username' => $user, 'password' => $pwd];
        $client->request('POST', '/api/login', [], [], ['CONTENT_TYPE' => 'application/json'], \json_encode($idconnexion));
        $this->assertResponseStatusCodeSame(200);
        $data = \json_decode($client->getResponse()->getContent(), true);
        $client->setServerParameters(['HTTP_Authorization' => sprintf('Bearer %s', $data['token']), 'CONTENT_TYPE' => 'application/json']);
        return $client;
    }

    public function testAdd()
    {
        $client = $this->authentification('admin1', 'pass_1234');
        // $result = static::createClient();
        // $result->request('POST', '/api/formateur/brief/');

        // $this->assertEquals(200, $result->getResponse()->getStatusCode());
    }
}
