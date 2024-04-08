<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
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
operations:[new Get(normalizationContext:['groups' => 'produits:item']),
            new GetCollection(normalizationContext:['groups' => 'produits:list']),
            ])] 
#[ApiFilter(SearchFilter::class, properties: ['plateformes.libelle' => 'exact','designation' => 'partial'])]
#[ApiFilter(OrderFilter::class, properties: ['prix' => 'asc'])]

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['produits:list','produits:item'])]
    private ?int $id = null;

    #[Groups(['produits:list','produits:item'])]
    #[ORM\Column(length: 50)]
    private ?string $designation = null;

    #[Groups(['produits:list','produits:item'])]
    #[ORM\Column]
    private ?float $prix = null;

    #[Groups(['produits:list','produits:item'])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[Groups(['produits:list','produits:item'])]
    #[ORM\Column(length: 255, nullable: true)]
    private string $image = '';

    #[Groups(['produits:list','produits:item'])]
    #[ORM\Column]
    private ?int $nblikes = 0;

    #[ORM\OneToMany(mappedBy: 'produits', targetEntity: Ajouter::class)]
    private Collection $ajouters;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'favoris')]
    private Collection $users;

    #[Groups(['produits:list','produits:item','plateformes:list','plateformes:item'])]
    #[ORM\ManyToMany(targetEntity: Plateforme::class, inversedBy: 'produits')]
    private Collection $plateformes;


    public function __construct()
    {
        $this->ajouters = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->plateforme = new ArrayCollection();
        $this->plateformes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getNbLikes(): ?int
    {
        return $this->nblikes;
    }

    public function setNbLikes(int $nblikes): self
    {
        $this->nblikes = $nblikes;

        return $this;
    }

    /**
     * @return Collection<int, Ajouter>
     */
    public function getAjouters(): Collection
    {
        return $this->ajouters;
    }

    public function addAjouter(Ajouter $ajouter): self
    {
        if (!$this->ajouters->contains($ajouter)) {
            $this->ajouters->add($ajouter);
            $ajouter->setProduits($this);
        }

        return $this;
    }

    public function removeAjouter(Ajouter $ajouter): self
    {
        if ($this->ajouters->removeElement($ajouter)) {
            // set the owning side to null (unless already changed)
            if ($ajouter->getProduits() === $this) {
                $ajouter->setProduits(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addFavori($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeFavori($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Plateforme>
     */
    public function getPlateformes(): Collection
    {
        return $this->plateformes;
    }

    public function addPlateforme(Plateforme $plateforme): self
    {
        if (!$this->plateformes->contains($plateforme)) {
            $this->plateformes->add($plateforme);
        }

        return $this;
    }

    public function removePlateforme(Plateforme $plateforme): self
    {
        $this->plateformes->removeElement($plateforme);

        return $this;
    }

}
