<?php

namespace App\Entity;

use App\Repository\BouteillePersonnaliseeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BouteillePersonnaliseeRepository::class)
 */
class BouteillePersonnalisee
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="bigint")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $createdAt = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * @ORM\Column(length=255, nullable=true)
     */
    private ?string $nom = null;

    /**
     * @ORM\Column(length=255, nullable=true)
     */
    private ?string $pays = null;

    /**
     * @ORM\Column(length=255, nullable=true)
     */
    private ?string $region = null;

    /**
     * @ORM\Column(length=255, nullable=true)
     */
    private ?string $couleur = null;

    /**
     * @ORM\Column(length=255, nullable=true)
     */
    private ?string $format = null;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=2, nullable=true)
     */
    private ?string $prix = null;

    /**
     * @ORM\Column(length=255, nullable=true)
     */
    private ?string $producteur = null;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private ?int $millesime = null;

    /**
     * @ORM\Column(length=255, nullable=true)
     */
    private ?string $cepage = null;

    /**
     * @ORM\Column(length=255, nullable=true)
     */
    private ?string $tauxSucre = null;

    /**
     * @ORM\Column(length=255, nullable=true)
     */
    private ?string $degre = null;

    /**
     * @ORM\Column(length=255, nullable=true)
     */
    private ?string $type = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bouteillePersonnalisees")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(?string $pays): self
    {
        $this->pays = $pays;
        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;
        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): self
    {
        $this->couleur = $couleur;
        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(?string $format): self
    {
        $this->format = $format;
        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(?string $prix): self
    {
        $this->prix = $prix;
        return $this;
    }

    public function getProducteur(): ?string
    {
        return $this->producteur;
    }

    public function setProducteur(?string $producteur): self
    {
        $this->producteur = $producteur;
        return $this;
    }

    public function getMillesime(): ?int
    {
        return $this->millesime;
    }

    public function setMillesime(?int $millesime): self
    {
        $this->millesime = $millesime;
        return $this;
    }

    public function getCepage(): ?string
    {
        return $this->cepage;
    }

    public function setCepage(?string $cepage): self
    {
        $this->cepage = $cepage;
        return $this;
    }

    public function getTauxSucre(): ?string
    {
        return $this->tauxSucre;
    }

    public function setTauxSucre(?string $tauxSucre): self
    {
        $this->tauxSucre = $tauxSucre;
        return $this;
    }

    public function getDegre(): ?string
    {
        return $this->degre;
    }

    public function setDegre(?string $degre): self
    {
        $this->degre = $degre;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;
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
}
