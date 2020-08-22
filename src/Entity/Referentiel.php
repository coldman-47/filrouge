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
 *      "getreferentiel" = {
 *          "path" = "/admin/referentiels/"
 *      },
 *      "addreferentiel" = {
 *          "method" = "post",
 *          "path" = "/admin/referentiels/",
 *          "deserialize" = false
 *      }
 *  },
 *  itemOperations = {
 *      "get" = {
 *          "path" = "/admin/referentiels/{id}"
 *      },
 *      "setreferentiel" = {
 *          "method" = "put",
 *          "path" = "/admin/referentiels/{id}",
 *          "deserialize" = false
 *      },
 *      "delreferentiel" = {
 *          "method" = "delete",
 *          "path" = "/admin/referentiels/{id}",
 *          "deserialize" = false
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

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $programme;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $deleted;

    /**
     * @ORM\OneToMany(targetEntity=CompetenceValide::class, mappedBy="referentiel")
     */
    private $competenceValides;

    public function __construct()
    {
        $this->grpCompetences = new ArrayCollection();
        $this->promos = new ArrayCollection();
        $this->competenceValides = new ArrayCollection();
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

    public function getProgramme()
    {
        return stream_get_contents($this->programme);
    }

    public function setProgramme($programme): self
    {
        $this->programme = stream_get_contents($programme);

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

    /**
     * @return Collection|CompetenceValide[]
     */
    public function getCompetenceValides(): Collection
    {
        return $this->competenceValides;
    }

    public function addCompetenceValide(CompetenceValide $competenceValide): self
    {
        if (!$this->competenceValides->contains($competenceValide)) {
            $this->competenceValides[] = $competenceValide;
            $competenceValide->setReferentiel($this);
        }

        return $this;
    }

    public function removeCompetenceValide(CompetenceValide $competenceValide): self
    {
        if ($this->competenceValides->contains($competenceValide)) {
            $this->competenceValides->removeElement($competenceValide);
            // set the owning side to null (unless already changed)
            if ($competenceValide->getReferentiel() === $this) {
                $competenceValide->setReferentiel(null);
            }
        }

        return $this;
    }
}
