<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class NurseControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/nurse/index');

        $this->assertResponseStatusCodeSame(200);
        $this->assertJson($client->getResponse()->getContent());
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($data);
    }

    public function testFindByNameSuccess()
    {
        $client = static::createClient();

        $client->request('GET', '/nurse/name/John');

        $this->assertResponseStatusCodeSame(200);
        $this->assertJson($client->getResponse()->getContent());
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($data);
        $this->assertNotEmpty($data); 
    }

    public function testFindByNameNotFound()
    {
        $client = static::createClient();

        $client->request('GET', '/nurse/name/NonExistingName');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testLoginSuccess()
    {
        $client = static::createClient();

        $client->request('POST', '/nurse/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'test@example.com',
            'password' => 'password123'
        ]));

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonStringEqualsJsonString('{"success":true}', $client->getResponse()->getContent());
    }

    public function testLoginFailure()
    {
        $client = static::createClient();

        $client->request('POST', '/nurse/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword'
        ]));

        $this->assertResponseStatusCodeSame(404);
        $this->assertJsonStringEqualsJsonString('{"success":false}', $client->getResponse()->getContent());
    }

    public function testDeleteSuccess()
    {
        $client = static::createClient();

        $client->request('DELETE', '/nurse/1');

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonStringEqualsJsonString('{"success":true}', $client->getResponse()->getContent());
    }

   /* public function testDeleteNotFound()
    {
        $client = static::createClient();

        $client->request('DELETE', '/nurse/9999');

        $this->assertResponseStatusCodeSame(404);
        $this->assertJsonStringEqualsJsonString('{"success":false}', $client->getResponse()->getContent());
    } */
    /*
    public function testFindByIdSuccess()
    {
        $client = static::createClient();

        $client->request('GET', '/nurse/id/1');

        $this->assertResponseStatusCodeSame(200);
        $this->assertJson($client->getResponse()->getContent());
    } */

    public function testFindByIdNotFound()
    {
        $client = static::createClient();

        $client->request('GET', '/nurse/id/9999');

        $this->assertResponseStatusCodeSame(404);
        $this->assertJsonStringEqualsJsonString('{"error":"Nurse not found"}', $client->getResponse()->getContent());
    }
}
?>
