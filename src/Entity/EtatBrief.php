<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EtatBriefRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=EtatBriefRepository::class)
 */
class EtatBrief
{

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    /**
     * @Id
     * @ORM\ManyToOne(targetEntity=Brief::class, inversedBy="etatBriefs")
     */
    private $brief;

    /**
     * @Id
     * @ORM\ManyToOne(targetEntity=Groupe::class, inversedBy="etatBriefs")
     */
    private $groupe;

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getBrief(): ?Brief
    {
        return $this->brief;
    }

    public function setBrief(?Brief $brief): self
    {
        $this->brief = $brief;

        return $this;
    }

    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }

    public function setGroupe(?Groupe $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }
}
