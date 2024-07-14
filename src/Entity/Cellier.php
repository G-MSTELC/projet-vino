<?php

namespace App\Entity;

use App\Repository\CellierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CellierRepository::class)
 */
class Cellier
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint", options={"unsigned": true})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="celliers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $prixTotal;

    /**
     * @ORM\OneToMany(targetEntity=BouteilleCellier::class, mappedBy="cellier", orphanRemoval=true, cascade={"persist"})
     */
    private $bouteilleCelliers;

    /**
     * Propriété temporaire pour stocker les bouteilles associées à ce cellier.
     * @ORM\OneToMany(targetEntity=Bouteille::class, mappedBy="cellier")
     */
    private $bouteilles;

    public function __construct()
    {
        $this->bouteilleCelliers = new ArrayCollection();
        $this->bouteilles = new ArrayCollection(); 
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getPrixTotal(): ?float
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(?float $prixTotal): self
    {
        $this->prixTotal = $prixTotal;
        return $this;
    }

    /**
     * @return Collection|BouteilleCellier[]
     */
    public function getBouteilleCelliers(): Collection
    {
        return $this->bouteilleCelliers;
    }

    public function addBouteilleCellier(BouteilleCellier $bouteilleCellier): self
    {
        if (!$this->bouteilleCelliers->contains($bouteilleCellier)) {
            $this->bouteilleCelliers[] = $bouteilleCellier;
            $bouteilleCellier->setCellier($this);
            $this->calculerPrixTotal();
        }
        return $this;
    }

    public function removeBouteilleCellier(BouteilleCellier $bouteilleCellier): self
    {
        if ($this->bouteilleCelliers->removeElement($bouteilleCellier)) {
          
            if ($bouteilleCellier->getCellier() === $this) {
                $bouteilleCellier->setCellier(null);
                $this->calculerPrixTotal();
            }
        }
        return $this;
    }

    public function getBouteilleCelliersCount(): int
    {
        return $this->bouteilleCelliers->count();
    }

    public function ajouterBouteille(BouteilleCellier $bouteilleCellier): self
    {
        if (!$this->bouteilleCelliers->contains($bouteilleCellier)) {
            $this->bouteilleCelliers[] = $bouteilleCellier;
            $bouteilleCellier->setCellier($this);
            $this->calculerPrixTotal();
        }
        return $this;
    }

    public function selectionnerBouteille(int $bouteilleId): ?BouteilleCellier
    {
        foreach ($this->bouteilleCelliers as $bouteilleCellier) {
            if ($bouteilleCellier->getId() === $bouteilleId) {
                return $bouteilleCellier;
            }
        }
        return null;
    }

    public function calculerPrixTotal(): void
    {
        $prixTotal = 0;

        foreach ($this->bouteilleCelliers as $bouteilleCellier) {
            $prixTotal += $bouteilleCellier->getQuantite() * $bouteilleCellier->getBouteille()->getPrix();
        }

        $this->setPrixTotal($prixTotal);
    }

    public function calculerQuantiteTotale(): int
    {
        $quantiteTotale = 0;

        foreach ($this->bouteilleCelliers as $bouteilleCellier) {
            $quantiteTotale += $bouteilleCellier->getQuantite();
        }

        return $quantiteTotale;
    }

    /**
     * @return Collection|Bouteille[]
     */
    public function getBouteilles(): Collection
    {
        return $this->bouteilles ?: $this->bouteilles = new ArrayCollection();
    }

    /**
     * @param Bouteille $bouteille
     */
    public function addBouteille(Bouteille $bouteille): void
    {
        if (!$this->bouteilles->contains($bouteille)) {
            $this->bouteilles[] = $bouteille;
           
        }
    }

    /**
     * @param Bouteille $bouteille
     */
    public function removeBouteille(Bouteille $bouteille): void
    {
        $this->bouteilles->removeElement($bouteille);
    }
}
