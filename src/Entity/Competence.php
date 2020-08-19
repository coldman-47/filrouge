<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CompetenceRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=CompetenceRepository::class)
 * @ApiResource(
 *  collectionOperations = {
 *      "get" = {
 *          "path" = "/admin/competences/"
 *      },
 *      "addcompetences" = {
 *          "method" = "post",
 *          "path" = "/admin/competences/",
 *          "deserialize" = false
 *      }
 *  },
 *  itemOperations = {
 *      "get" = {
 *          "path" = "/admin/competences/{id}/"
 *      },
 *      "put" = {
 *          "path" = "/admin/competences/{id}/",
 *          "deserialize" = false
 *      },
 *      "delete" = {
 *          "path" = "/admin/competences/{id}/"
 *      }
 *  }
 * )
 */
class Competence
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
     * @ORM\ManyToMany(targetEntity=GroupeCompetence::class, mappedBy="competence")
     */
    private $groupeCompetences;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descriptif;

    /**
     * @ORM\OneToMany(targetEntity=Niveau::class, mappedBy="competence")
     */
    private $niveaux;

    /**
     * @ORM\OneToMany(targetEntity=DetailBriefCompetence::class, mappedBy="competence")
     */
    private $detailBriefCompetences;

    public function __construct()
    {
        $this->groupeCompetences = new ArrayCollection();
        $this->niveaux = new ArrayCollection();
        $this->detailBriefCompetences = new ArrayCollection();
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

    /**
     * @return Collection|GroupeCompetence[]
     */
    public function getGroupeCompetences(): Collection
    {
        return $this->groupeCompetences;
    }

    public function addGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        if (!$this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences[] = $groupeCompetence;
            $groupeCompetence->addCompetence($this);
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        if ($this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences->removeElement($groupeCompetence);
            $groupeCompetence->removeCompetence($this);
        }

        return $this;
    }

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    /**
     * @return Collection|Niveau[]
     */
    public function getNiveaux(): Collection
    {
        return $this->niveaux;
    }

    public function addNiveau(Niveau $niveau): self
    {
        if (!$this->niveaux->contains($niveau)) {
            $this->niveaux[] = $niveau;
            $niveau->setCompetence($this);
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        if ($this->niveaux->contains($niveau)) {
            $this->niveaux->removeElement($niveau);
            // set the owning side to null (unless already changed)
            if ($niveau->getCompetence() === $this) {
                $niveau->setCompetence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DetailBriefCompetence[]
     */
    public function getDetailBriefCompetences(): Collection
    {
        return $this->detailBriefCompetences;
    }

    public function addDetailBriefCompetence(DetailBriefCompetence $detailBriefCompetence): self
    {
        if (!$this->detailBriefCompetences->contains($detailBriefCompetence)) {
            $this->detailBriefCompetences[] = $detailBriefCompetence;
            $detailBriefCompetence->setCompetence($this);
        }

        return $this;
    }

    public function removeDetailBriefCompetence(DetailBriefCompetence $detailBriefCompetence): self
    {
        if ($this->detailBriefCompetences->contains($detailBriefCompetence)) {
            $this->detailBriefCompetences->removeElement($detailBriefCompetence);
            // set the owning side to null (unless already changed)
            if ($detailBriefCompetence->getCompetence() === $this) {
                $detailBriefCompetence->setCompetence(null);
            }
        }

        return $this;
    }
}
