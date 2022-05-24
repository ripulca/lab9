<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('PH');
        $this->assertCount(8, $crawler->filter('.post'));
        $client->clickLink('photo_20');
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Photo 20');
    }
}
