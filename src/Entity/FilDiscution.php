<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FilDiscutionRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FilDiscutionRepository::class)
 * @ApiResource()
 */
class FilDiscution
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"all_comment"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Commentaire::class, inversedBy="filDiscutions")
     * @Groups({"all_comment"})
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
