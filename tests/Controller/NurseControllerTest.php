<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NurseControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/nurse/index');

        // Verifica que la respuesta sea exitosa
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Verifica que el contenido de la respuesta sea JSON
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testFindByName()
    {
        $client = static::createClient();
        $client->request('GET', '/nurse/name/John');

        // Verifica que la respuesta sea exitosa
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Verifica que el contenido de la respuesta sea JSON
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testLoginSuccess()
    {
        $client = static::createClient();
        $client->request('POST', '/nurse/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'test@example.com',
            'password' => 'password123'
        ]));

        // Verifica que el código de respuesta sea 200 (login exitoso)
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertStringContainsString('"success":true', $client->getResponse()->getContent());
    }

    public function testLoginFailure()
    {
        $client = static::createClient();
        $client->request('POST', '/nurse/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword'
        ]));

        // Verifica que el código de respuesta sea 404 (login fallido)
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertStringContainsString('"success":false', $client->getResponse()->getContent());
    }

    public function testDeleteNurse()
    {
        $client = static::createClient();
        $client->request('DELETE', '/nurse/delete/1');

        // Verifica que el código de respuesta sea 200
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertStringContainsString('"success":true', $client->getResponse()->getContent());
    }

    public function testFindById()
    {
        $client = static::createClient();
        $client->request('GET', '/nurse/id/1');

        // Verifica que el código de respuesta sea 200
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }
}
