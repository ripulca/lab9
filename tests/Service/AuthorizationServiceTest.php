<?php

namespace App\Tests\Service;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthorizationServiceTest extends WebTestCase
{
    public function testAuth(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('user_1@example.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        // test e.g. the profile page
        $client->request('GET', '/user/2');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('a', 'user_1');
    }
}
