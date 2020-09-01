<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CompetenceRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

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
     * @Groups({"promo_cmpt","competence:read","grp"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *  @Groups({"promo_cmpt","competence:read","grp"})
     * 
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
     * @ORM\OneToMany(targetEntity=Niveau::class, mappedBy="competence", cascade={"persist"})
     * @Groups({"promo_cmpt","competence:read","grp"})
     */
    private $niveaux;

    /**
     * @ORM\OneToMany(targetEntity=CompetenceValide::class, mappedBy="competence")
     *  @Groups({"promo_cmpt"})
     */
    private $competenceValides;


    public function __construct()
    {
        $this->groupeCompetences = new ArrayCollection();
        $this->niveaux = new ArrayCollection();
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
            $competenceValide->setCompetence($this);
        }

        return $this;
    }

    public function removeCompetenceValide(CompetenceValide $competenceValide): self
    {
        if ($this->competenceValides->contains($competenceValide)) {
            $this->competenceValides->removeElement($competenceValide);
            // set the owning side to null (unless already changed)
            if ($competenceValide->getCompetence() === $this) {
                $competenceValide->setCompetence(null);
            }
        }

        return $this;
    }

    
}
