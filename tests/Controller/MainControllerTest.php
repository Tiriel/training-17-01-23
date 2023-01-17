<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    /**
     * @dataProvider provideUrlAndMethod
     * @group smoke
     */
    public function testPublicUriAreSuccessful(string $method, string $uri): void
    {
        $client = static::createClient();
        $crawler = $client->request($method, $uri);

        $this->assertResponseIsSuccessful();
    }

    public function provideUrlAndMethod(): array
    {
        return [
            'index' => ['GET', '/'],
            'hello' => ['GET', '/hello'],
            'contact' => ['GET', '/contact'],
            'book' => ['GET', '/book'],
        ];
    }
}
