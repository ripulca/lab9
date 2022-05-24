<?php

namespace App\Tests\Service;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Photo;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostServiceTest extends WebTestCase
{   
    private KernelBrowser $client;
    private PostRepository $repository;
    private string $path = '/post/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Post::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Post index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'post[date]' => new \DateTime(),
            'post[comment]' => 'Testing',
            'post[user_id]' => 200,
        ]);

        self::assertResponseRedirects('/sweet/food/');

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Post();
        $fixture->setDate(new \DateTime());
        $fixture->setComment('My Title');
        $fixture->setUser_id(200);

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Post');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Post();
        $fixture->setDate(new \DateTime());
        $fixture->setComment('My Title');
        $fixture->setUser_id(200);

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'post[date]' => new \DateTime(),
            'post[comment]' => 'Testing',
            'post[user_id]' => 200,
        ]);

        self::assertResponseRedirects('/post/');

        $fixture = $this->repository->findAll();

        self::assertSame('Testing', $fixture[0]->getComment());
        self::assertSame(new \DateTime(), $fixture[0]->getDate());
        self::assertSame(200, $fixture[0]->getUser_id());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Post();
        $fixture->setDate(new \DateTime());
        $fixture->setComment('My Title');
        $fixture->setUser_id(200);

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/post/');
        self::assertSame(0, $this->repository->count([]));
    }
}