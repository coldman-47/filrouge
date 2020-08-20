<?php

namespace App\Entity;

use App\Repository\ApprenantLivrablePartielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApprenantLivrablePartielRepository::class)
 */
class ApprenantLivrablePartiel
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
    private $etat;

    /**
     * @ORM\Column(type="datetime")
     */
    private $delaiAt;

    /**
     * @ORM\ManyToOne(targetEntity=Apprenant::class, inversedBy="apprenantLivrablePartiels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $apprenant;

    /**
     * @ORM\ManyToOne(targetEntity=LivrablePartiel::class, inversedBy="apprenantLivrablePartiels")
     */
    private $livrablePartiel;

    /**
     * @ORM\OneToMany(targetEntity=FilDiscution::class, mappedBy="apprenantLivravlePartiel")
     */
    private $filDiscutions;

    public function __construct()
    {
        $this->filDiscutions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

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

    public function getApprenant(): ?Apprenant
    {
        return $this->apprenant;
    }

    public function setApprenant(?Apprenant $apprenant): self
    {
        $this->apprenant = $apprenant;

        return $this;
    }

    public function getLivrablePartiel(): ?LivrablePartiel
    {
        return $this->livrablePartiel;
    }

    public function setLivrablePartiel(?LivrablePartiel $livrablePartiel): self
    {
        $this->livrablePartiel = $livrablePartiel;

        return $this;
    }

    /**
     * @return Collection|FilDiscution[]
     */
    public function getFilDiscutions(): Collection
    {
        return $this->filDiscutions;
    }

    public function addFilDiscution(FilDiscution $filDiscution): self
    {
        if (!$this->filDiscutions->contains($filDiscution)) {
            $this->filDiscutions[] = $filDiscution;
            $filDiscution->setApprenantLivravlePartiel($this);
        }

        return $this;
    }

    public function removeFilDiscution(FilDiscution $filDiscution): self
    {
        if ($this->filDiscutions->contains($filDiscution)) {
            $this->filDiscutions->removeElement($filDiscution);
            // set the owning side to null (unless already changed)
            if ($filDiscution->getApprenantLivravlePartiel() === $this) {
                $filDiscution->setApprenantLivravlePartiel(null);
            }
        }

        return $this;
    }
}
