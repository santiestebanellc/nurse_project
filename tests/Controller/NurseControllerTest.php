<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NurseControllerTest extends WebTestCase
{
    public function testCreateNurse(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/nurse/create',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'John',
                'firstSurname' => 'Doe',
                'secondSurname' => 'Smith',
            ])
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testCreateNurseInvalidData(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/nurse/create',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => '',
                'firstSurname' => 'Doe',
            ])
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testUpdateNurse(): void
    {
        $client = static::createClient();
        $client->request(
            'PUT',
            '/nurse/update/1',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'Jane',
                'firstSurname' => 'Smith',
                'secondSurname' => 'Doe',
            ])
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testDeleteNurse(): void
    {
        $client = static::createClient();
        $client->request('DELETE', '/nurse/delete/1');

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }

    public function testGetNurse(): void
    {
        $client = static::createClient();
        $client->request('GET', '/nurse/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testGetAllNurses(): void
    {
        $client = static::createClient();
        $client->request('GET', '/nurse/all');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }
}
