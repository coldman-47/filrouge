<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 *  @ApiResource(
 *  collectionOperations = {
 *      "get" = {
 *          "path" = "/admin/groupes"
 *      },
 *      "addgroups" = {
 *          "method" = "post",
 *          "path" = "/admin/groupes"
 *      }
 *  },
 *  itemOperations = {
 *      "get" = {
 *          "path" = "/admin/groupes/{id}"
 *      },
 *      "put" = {
 *          "path" = "/admin/groupes/{id}"
 *      },
 *      "delete" = {
 *          "path" = "/admin/groupes/id/apprenants"
 *      }
 *  }
 * )
 */
class Groupe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class)
     */
    private $apprenants;

    public function __construct()
    {
        $this->apprenants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?int
    {
        return $this->libelle;
    }

    public function setLibelle(int $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->contains($apprenant)) {
            $this->apprenants->removeElement($apprenant);
        }

        return $this;
    }
}
