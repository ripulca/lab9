<?php

namespace App\Entity;

use App\Repository\PhotoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PhotoRepository::class)]
class Photo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'photos')]
    #[ORM\JoinColumn(nullable: false)]
    private $post;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=10)
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $path;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     */
    #[ORM\Column(type: 'string', length: 5)]
    private $format;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(string $format): self
    {
        $this->format = $format;

        return $this;
    }
    
    public function __toString() {
        return $this->name;
    }
}
