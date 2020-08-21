<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\RessourceRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=RessourceRepository::class)
 */
class Ressource
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"briefs"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"briefs"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity=Brief::class, inversedBy="ressources")
     * @ORM\JoinColumn(nullable=false)
     */
    private $brief;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $pieceJointe;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

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

    public function getPieceJointe()
    {
        $pj = $this->pieceJointe;
        if (!is_resource($pj)) {
            return $pj;
        }
        return base64_encode(stream_get_contents($pj));
    }

    public function setPieceJointe($pieceJointe): self
    {
        $this->pieceJointe = $pieceJointe;

        return $this;
    }
}
