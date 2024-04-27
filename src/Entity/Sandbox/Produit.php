<?php

namespace App\Entity\Sandbox;

use App\Entity\Cmd;
use App\Entity\Panier;
use App\Entity\User;
use App\Repository\Sandbox\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Table(name:'i23_produits')]
#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Assert\Length(
        min : 2,
        max: 20,
        minMessage: 'minimum 2 caractères ',
        maxMessage: 'maximum 20 caractères',
    )]
    private ?string $libelle = null;

    #[ORM\Column]
    #[Assert\Range(
        min : 0.1,
        
        minMessage: 'le prix est 0.1 au moins.',
    )]
  
    private ?float $prix = null;
     
    #[ORM\Column]
    #[Assert\Range(
        min : 1,
        
        minMessage: 'le quantite stocké est 1 au moins.',
    )]
    private ?int $stock = null;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Panier::class, orphanRemoval: true)]
    private Collection $paniers;

    public function __construct()
    {
        $this->paniers = new ArrayCollection();
    }

    


   

  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return Collection<int, Panier>
     */
    public function getPaniers(): Collection
    {
        return $this->paniers;
    }

    public function addPanier(Panier $panier): self
    {
        if (!$this->paniers->contains($panier)) {
            $this->paniers->add($panier);
            $panier->setProduit($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): self
    {
        if ($this->paniers->removeElement($panier)) {
            // set the owning side to null (unless already changed)
            if ($panier->getProduit() === $this) {
                $panier->setProduit(null);
            }
        }

        return $this;
    }

   


   
   
}
