<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReferentielRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=ReferentielRepository::class)
 * @ApiResource(
 *  collectionOperations = {
 *      "get" = {
 *          "path" = "/admin/referentiels/"
 *      },
 *      "addreferentiel" = {
 *          "method" = "post",
 *          "path" = "/admin/referentiels/"
 *      }
 *  }
 * )
 */
class Referentiel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $libelle;

    /**
     * @ORM\Column(type="text")
     */
    private $presentation;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetence::class, inversedBy="referentiels")
     */
    private $grpCompetences;

    /**
     * @ORM\ManyToMany(targetEntity=Promo::class, mappedBy="referentil_promo")
     */
    private $promos;

    public function __construct()
    {
        $this->grpCompetences = new ArrayCollection();
        $this->promos = new ArrayCollection();
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

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    /**
     * @return Collection|GroupeCompetence[]
     */
    public function getGrpCompetences(): Collection
    {
        return $this->grpCompetences;
    }

    public function addGrpCompetence(GroupeCompetence $grpCompetence): self
    {
        if (!$this->grpCompetences->contains($grpCompetence)) {
            $this->grpCompetences[] = $grpCompetence;
        }

        return $this;
    }

    public function removeGrpCompetence(GroupeCompetence $grpCompetence): self
    {
        if ($this->grpCompetences->contains($grpCompetence)) {
            $this->grpCompetences->removeElement($grpCompetence);
        }

        return $this;
    }

    /**
     * @return Collection|Promo[]
     */
    public function getPromos(): Collection
    {
        return $this->promos;
    }

    public function addPromo(Promo $promo): self
    {
        if (!$this->promos->contains($promo)) {
            $this->promos[] = $promo;
            $promo->addReferentilPromo($this);
        }

        return $this;
    }

    public function removePromo(Promo $promo): self
    {
        if ($this->promos->contains($promo)) {
            $this->promos->removeElement($promo);
            $promo->removeReferentilPromo($this);
        }

        return $this;
    }
}
