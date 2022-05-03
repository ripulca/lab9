<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Photo;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // create 20 obj! Bam!
        for ($i = 0; $i < 21; $i++) {
            $user = new User();
            $user->setName('user_'.$i);
            $user->setEmail('user_'.$i.'@example.com');
            $user->setPassword(password_hash('pwd____'.$i, PASSWORD_DEFAULT));
            $user->setPhone('+89001239'.$i);
            if($i<20){
                $user->setRoles(['ROLE_USER']);
            }
            else{
                $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
            }
            $manager->persist($user);
            $post = new Post();
            $post->setUserId($user);
            $post->setDate(new \DateTime());
            $post->setComment('comment_'.$i);
            $manager->persist($post);
            $photo = new Photo();
            $photo->setPost($post);
            $photo->setName('photo_'.$i);
            $photo->setPath('img/imleedh-ali-Uf-_p8zZiT8-unsplash.jpg');
            $photo->setFormat('jpg');
            $manager->persist($photo);
        }

        $manager->flush();
    }
}
