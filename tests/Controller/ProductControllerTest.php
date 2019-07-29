<?php

namespace App\Tests\Controller;

use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    use RefreshDatabaseTrait;

    public function testProductList()
    {
        $client = static::createClient();

        $client->request('GET', '/product/list');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testProductAdd()
    {
        $client = static::createClient();

        $client->request('POST', '/product/add');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testProductEdit()
    {
        $client = static::createClient();

        $client->request('PUT', '/product/edit/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
