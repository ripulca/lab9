<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[ApiResource(
    collectionOperations: ['get' => ['normalization_context' => ['groups' => 'user:list']]],
    itemOperations: ['get' => ['normalization_context' => ['groups' => 'user:item']]],
    order: ['name' => 'DESC'],
    paginationEnabled: true,
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['user:list', 'user:item'])]
    private $id;

    /**
     * @Assert\Email
     */
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(['user:list', 'user:item'])]
    private $email;

    #[ORM\Column(type: 'json')]
    #[Groups(['user:list', 'user:item'])]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    #[Groups(['user:list', 'user:item'])]
    private $password;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     */
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Groups(['user:list', 'user:item'])]
    private $name;

    /**
     * @Assert\Regex("/^(\+)?((\d{2,3}) ?\d|\d)(([ -]?\d)|( ?(\d{2,3}) ?)){5,12}\d$/")
     */
    #[ORM\Column(type: 'string', length: 13, nullable: true)]
    #[Groups(['user:list', 'user:item'])]
    private $phone;

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: user::class, orphanRemoval: true)]
    private $users;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private $apiToken;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection<int, user>
     */
    public function getusers(): Collection
    {
        return $this->users;
    }

    public function adduser(user $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setUserId($this);
        }

        return $this;
    }

    public function removeuser(user $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getUserId() === $this) {
                $user->setUserId(null);
            }
        }

        return $this;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }
}
