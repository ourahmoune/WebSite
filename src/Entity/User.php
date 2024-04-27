<?php

namespace App\Entity;


use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name:'i23_users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Length(
        min : 2,
        minMessage: 'La taille du votrelogin doit etre au moins 2 caractères ',
    )]
    private ?string $login = null;

    #[ORM\Column(nullable:true)]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;
    
    #[ORM\Column(length: 30)]
    #[Assert\Length(
        min : 1,
        max: 30,
        minMessage: 'minimum 1 caractères ',
        maxMessage: 'maximum 30 caractères',
    )]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $ddn = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Panier::class, orphanRemoval: true)]
    private Collection $produit;

    public function __construct()
    {
        $this->produit = new ArrayCollection();
    }

    
  
    

    

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->login;
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

    public function getDdn(): ?\DateTimeInterface
    {
        return $this->ddn;
    }

    public function setDdn(?\DateTimeInterface $ddn): self
    {
        $this->ddn = $ddn;

        return $this;
    }

    /**
     * @return Collection<int, Panier>
     */
    public function getProduit(): Collection
    {
        return $this->produit;
    }

    public function addProduit(Panier $produit): self
    {
        if (!$this->produit->contains($produit)) {
            $this->produit->add($produit);
            $produit->setUser($this);
        }

        return $this;
    }

    public function removeProduit(Panier $produit): self
    {
        if ($this->produit->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getUser() === $this) {
                $produit->setUser(null);
            }
        }

        return $this;
    }

    
}

  

   
   

