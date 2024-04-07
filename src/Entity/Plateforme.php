<?php

namespace App\Entity;

use App\Repository\PlateformeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter; // Permet de faire des recherche 
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;  // Permet de filter par ordre alphabétique
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(paginationItemsPerPage: 20, 
operations:[new Get(normalizationContext:['groups' => 'plateformes:item']),
            new GetCollection(normalizationContext:['groups' => 'plateformes:list']),
            ])] 

#[ORM\Entity(repositoryClass: PlateformeRepository::class)]
class Plateforme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['plateformes:list', 'plateformes:item', 'produit:list', 'produit:item'])]
    private ?string $libelle = null;

    #[ORM\ManyToMany(targetEntity: Produit::class, mappedBy: 'plateformes')]
    private Collection $produits;


    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups(['plateformes:list','plateformes:item','produit:list','produit:item'])]
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->addPlateforme($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            $produit->removePlateforme($this);
        }

        return $this;
    }
}
