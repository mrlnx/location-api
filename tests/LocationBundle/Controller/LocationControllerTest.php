<?php

namespace LocationBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LocationControllerTest extends WebTestCase
{
    public function testFindone()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'location/find');
    }

    public function testFindnumberof()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'location/search');
    }

    public function testCreate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'location/create');
    }

    public function testUpdate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'location/update');
    }

    public function testDelete()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'location/delete');
    }

}
