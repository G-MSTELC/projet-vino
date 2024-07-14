<?php


namespace App\Entity;

use App\Repository\BouteilleCellierRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BouteilleCellierRepository::class)
 */
class BouteilleCellier
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint", options={"unsigned": true})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Cellier::class, inversedBy="bouteilleCelliers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cellier;

    /**
     * @ORM\ManyToOne(targetEntity=Bouteille::class, inversedBy="bouteilleCelliers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bouteille;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCellier(): ?Cellier
    {
        return $this->cellier;
    }

    public function setCellier(?Cellier $cellier): self
    {
        $this->cellier = $cellier;
        return $this;
    }

    public function getBouteille(): ?Bouteille
    {
        return $this->bouteille;
    }

    public function setBouteille(?Bouteille $bouteille): self
    {
        $this->bouteille = $bouteille;
        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;
        return $this;
    }
}
