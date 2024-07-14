<?php

namespace App\Entity;

use App\Repository\BouteilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BouteilleRepository::class)
 */
class Bouteille
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pays;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $format;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lien_produit;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $src_image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $srcset_image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $designation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $degre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $taux_sucre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $couleur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $producteur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $agent_promotion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $millesime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cepage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $region;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $produit_quebec;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pastille_gout_titre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pastille_image_src;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity=BouteilleCellier::class, mappedBy="bouteille")
     */
    private $bouteilleCelliers;

    /**
     * @ORM\ManyToMany(targetEntity=Cellier::class)
     * @ORM\JoinTable(name="bouteille_cellier",
     *      joinColumns={@ORM\JoinColumn(name="bouteille_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="cellier_id", referencedColumnName="id")}
     *      )
     */
    private $celliers;

    public function __construct()
    {
        $this->bouteilleCelliers = new ArrayCollection();
        $this->celliers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

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

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(?string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function getLienProduit(): ?string
    {
        return $this->lien_produit;
    }

    public function setLienProduit(?string $lien_produit): self
    {
        $this->lien_produit = $lien_produit;

        return $this;
    }

    public function getSrcImage(): ?string
    {
        return $this->src_image;
    }

    public function setSrcImage(?string $src_image): self
    {
        $this->src_image = $src_image;

        return $this;
    }

    public function getSrcsetImage(): ?string
    {
        return $this->srcset_image;
    }

    public function setSrcsetImage(?string $srcset_image): self
    {
        $this->srcset_image = $srcset_image;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(?string $designation): self
    {
        $this->designation = $designation;

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

    public function getTauxSucre(): ?string
    {
        return $this->taux_sucre;
    }

    public function setTauxSucre(?string $taux_sucre): self
    {
        $this->taux_sucre = $taux_sucre;

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

    public function getProducteur(): ?string
    {
        return $this->producteur;
    }

    public function setProducteur(?string $producteur): self
    {
        $this->producteur = $producteur;

        return $this;
    }

    public function getAgentPromotion(): ?string
    {
        return $this->agent_promotion;
    }

    public function setAgentPromotion(?string $agent_promotion): self
    {
        $this->agent_promotion = $agent_promotion;

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

    public function getMillesime(): ?string
    {
        return $this->millesime;
    }

    public function setMillesime(?string $millesime): self
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

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getProduitQuebec(): ?bool
    {
        return $this->produit_quebec;
    }

    public function setProduitQuebec(?bool $produit_quebec): self
    {
        $this->produit_quebec = $produit_quebec;

        return $this;
    }

    public function getPastilleGoutTitre(): ?string
    {
        return $this->pastille_gout_titre;
    }

    public function setPastilleGoutTitre(?string $pastille_gout_titre): self
    {
        $this->pastille_gout_titre = $pastille_gout_titre;

        return $this;
    }

    public function getPastilleImageSrc(): ?string
    {
        return $this->pastille_image_src;
    }

    public function setPastilleImageSrc(?string $pastille_image_src): self
    {
        $this->pastille_image_src = $pastille_image_src;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

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
            $bouteilleCellier->setBouteille($this);
        }

        return $this;
    }

    public function removeBouteilleCellier(BouteilleCellier $bouteilleCellier): self
    {
        if ($this->bouteilleCelliers->removeElement($bouteilleCellier)) {
            // set the owning side to null (unless already changed)
            if ($bouteilleCellier->getBouteille() === $this) {
                $bouteilleCellier->setBouteille(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Cellier[]
     */
    public function getCelliers(): Collection
    {
        return $this->celliers;
    }

    public function addCellier(Cellier $cellier): self
    {
        if (!$this->celliers->contains($cellier)) {
            $this->celliers[] = $cellier;
        }

        return $this;
    }

    public function removeCellier(Cellier $cellier): self
    {
        $this->celliers->removeElement($cellier);

        return $this;
    }
}
