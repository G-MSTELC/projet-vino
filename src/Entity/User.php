<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass=App\Repository\UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="bigint")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(length=255, unique=true)
     */
    private ?string $username = null;

    /**
     * @ORM\Column(length=255, unique=true)
     */
    private ?string $email = null;

    /**
     * @ORM\Column(length=255)
     */
    private ?string $password = null;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $createdAt = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $resetToken = null;

    private ?string $plainPassword = null;

    /**
     * @var Collection<int, BouteilleCellier>
     * @ORM\OneToMany(mappedBy="user", targetEntity=BouteilleCellier::class)
     */
    private Collection $bouteilleCelliers;

    /**
     * @var Collection<int, Liste>
     * @ORM\OneToMany(mappedBy="user", targetEntity=Liste::class, orphanRemoval=true)
     */
    private Collection $listes;

    /**
     * @var Collection<int, BouteillePersonnalisee>
     * @ORM\OneToMany(mappedBy="user", targetEntity=BouteillePersonnalisee::class)
     */
    private Collection $bouteillePersonnalisees;

    /**
     * @var Collection<int, Commentaire>
     * @ORM\OneToMany(mappedBy="user", targetEntity=Commentaire::class)
     */
    private Collection $commentaires;

    public function __construct()
    {
        $this->bouteilleCelliers = new ArrayCollection();
        $this->listes = new ArrayCollection();
        $this->bouteillePersonnalisees = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;
        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function addRole(string $role): self
    {
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(string $role): self
    {
        if (false !== ($key = array_search($role, $this->roles, true))) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles); 
        }

        return $this;
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

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    /**
     * @return Collection<int, BouteilleCellier>
     */
    public function getBouteilleCelliers(): Collection
    {
        return $this->bouteilleCelliers;
    }

    public function addBouteilleCellier(BouteilleCellier $bouteilleCellier): self
    {
        if (!$this->bouteilleCelliers->contains($bouteilleCellier)) {
            $this->bouteilleCelliers[] = $bouteilleCellier;
            $bouteilleCellier->setUser($this);
        }

        return $this;
    }

    public function removeBouteilleCellier(BouteilleCellier $bouteilleCellier): self
    {
        if ($this->bouteilleCelliers->removeElement($bouteilleCellier)) {
            
            if ($bouteilleCellier->getUser() === $this) {
                $bouteilleCellier->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Liste>
     */
    public function getListes(): Collection
    {
        return $this->listes;
    }

    public function addListe(Liste $liste): self
    {
        if (!$this->listes->contains($liste)) {
            $this->listes[] = $liste;
            $liste->setUser($this);
        }

        return $this;
    }

    public function removeListe(Liste $liste): self
    {
        if ($this->listes->removeElement($liste)) {
            
            if ($liste->getUser() === $this) {
                $liste->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BouteillePersonnalisee>
     */
    public function getBouteillePersonnalisees(): Collection
    {
        return $this->bouteillePersonnalisees;
    }

    public function addBouteillePersonnalisee(BouteillePersonnalisee $bouteillePersonnalisee): self
    {
        if (!$this->bouteillePersonnalisees->contains($bouteillePersonnalisee)) {
            $this->bouteillePersonnalisees[] = $bouteillePersonnalisee;
            $bouteillePersonnalisee->setUser($this);
        }

        return $this;
    }

    public function removeBouteillePersonnalisee(BouteillePersonnalisee $bouteillePersonnalisee): self
    {
        if ($this->bouteillePersonnalisees->removeElement($bouteillePersonnalisee)) {
            
            if ($bouteillePersonnalisee->getUser() === $this) {
                $bouteillePersonnalisee->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setUser($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
         
            if ($commentaire->getUser() === $this) {
                $commentaire->setUser(null);
            }
        }

        return $this;
    }
}
