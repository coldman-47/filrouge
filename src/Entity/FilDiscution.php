<?php

namespace App\Entity;

use App\Repository\FilDiscutionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FilDiscutionRepository::class)
 */
class FilDiscution
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Commentaire::class, inversedBy="filDiscutions")
     */
    private $commentaire;

    /**
     * @ORM\ManyToOne(targetEntity=ApprenantLivrablePartiel::class, inversedBy="filDiscutions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $apprenantLivravlePartiel;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentaire(): ?Commentaire
    {
        return $this->commentaire;
    }

    public function setCommentaire(?Commentaire $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getApprenantLivravlePartiel(): ?ApprenantLivrablePartiel
    {
        return $this->apprenantLivravlePartiel;
    }

    public function setApprenantLivravlePartiel(?ApprenantLivrablePartiel $apprenantLivravlePartiel): self
    {
        $this->apprenantLivravlePartiel = $apprenantLivravlePartiel;

        return $this;
    }
}
