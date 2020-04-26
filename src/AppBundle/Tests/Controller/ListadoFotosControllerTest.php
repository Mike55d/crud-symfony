<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ListadoFotosControllerTest extends WebTestCase
{
    public function testPrimeravez()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/PrimeraVez');
    }

    public function testRegistros()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/Registros');
    }

}
