<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeCompetenceRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=GroupeCompetenceRepository::class)
 * @ApiResource(
 *  collectionOperations = {
 *      "get" = {
 *          "path" = "/admin/grpcompetences/"
 *      },
 *      "add_grp_competences" = {
 *          "method" = "post",
 *          "path" = "/admin/grpcompetences/"
 *      }
 *  },
 *  itemOperations = {
 *      "get" = {
 *          "path" = "/admin/grpcompetences/{id}/"
 *      },
 *      "put" = {
 *          "path" = "/admin/grpcompetences/{id}/"
 *      }
 *  }
 * )
 */
class GroupeCompetence
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
     * @ORM\ManyToMany(targetEntity=Competence::class, inversedBy="groupeCompetences")
     */
    private $competence;

    public function __construct()
    {
        $this->competence = new ArrayCollection();
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
     * @return Collection|Competence[]
     */
    public function getCompetence(): Collection
    {
        return $this->competence;
    }

    public function addCompetence(Competence $competence): self
    {
        if (!$this->competence->contains($competence)) {
            $this->competence[] = $competence;
        }

        return $this;
    }

    public function removeCompetence(Competence $competence): self
    {
        if ($this->competence->contains($competence)) {
            $this->competence->removeElement($competence);
        }

        return $this;
    }
}
