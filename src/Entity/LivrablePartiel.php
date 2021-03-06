<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LivrablePartielRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=LivrablePartielRepository::class)
 */
class LivrablePartiel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\Column(type="datetime")
     */
    private $delaiAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=BriefMaPromo::class, inversedBy="livrablePartiels")
     */
    private $BriefMapromo;

    /**
     * @ORM\ManyToMany(targetEntity=Niveau::class, inversedBy="livrablePartiels")
     */
    private $niveau;

    /**
     * @ORM\OneToMany(targetEntity=ApprenantLivrablePartiel::class, mappedBy="livrablePartiel")
     */
    private $apprenantLivrablePartiels;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $deleted;



    public function __construct()
    {
        $this->niveau = new ArrayCollection();
        $this->apprenantLivrablePartiels = new ArrayCollection();
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

    public function getDelaiAt(): ?\DateTimeInterface
    {
        return $this->delaiAt;
    }

    public function setDelaiAt(\DateTimeInterface $delaiAt): self
    {
        $this->delaiAt = $delaiAt;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    
    public function getBriefMapromo(): ?BriefMaPromo
    {
        return $this->BriefMapromo;
    }

    public function setBriefMapromo(?BriefMaPromo $BriefMapromo): self
    {
        $this->BriefMapromo = $BriefMapromo;

        return $this;
    }

    /**
     * @return Collection|Niveau[]
     */
    public function getNiveau(): Collection
    {
        return $this->niveau;
    }

    public function addNiveau(Niveau $niveau): self
    {
        if (!$this->niveau->contains($niveau)) {
            $this->niveau[] = $niveau;
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        if ($this->niveau->contains($niveau)) {
            $this->niveau->removeElement($niveau);
        }

        return $this;
    }

    /**
     * @return Collection|ApprenantLivrablePartiel[]
     */
    public function getApprenantLivrablePartiels(): Collection
    {
        return $this->apprenantLivrablePartiels;
    }

    public function addApprenantLivrablePartiel(ApprenantLivrablePartiel $apprenantLivrablePartiel): self
    {
        if (!$this->apprenantLivrablePartiels->contains($apprenantLivrablePartiel)) {
            $this->apprenantLivrablePartiels[] = $apprenantLivrablePartiel;
            $apprenantLivrablePartiel->setLivrablePartiel($this);
        }

        return $this;
    }

    public function removeApprenantLivrablePartiel(ApprenantLivrablePartiel $apprenantLivrablePartiel): self
    {
        if ($this->apprenantLivrablePartiels->contains($apprenantLivrablePartiel)) {
            $this->apprenantLivrablePartiels->removeElement($apprenantLivrablePartiel);
            // set the owning side to null (unless already changed)
            if ($apprenantLivrablePartiel->getLivrablePartiel() === $this) {
                $apprenantLivrablePartiel->setLivrablePartiel(null);
            }
        }

        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(?bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    

}
