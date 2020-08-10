<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 * @ApiResource(
 *  collectionOperations = {
 *      "get"={
 *          "path" = "/admin/groupes"
 *      },
 *      "add_groupes" = {
 *          "method" = "post",
 *          "path" = "/admin/groupes"
 *      }
 *  },
 * itemOperations = {
 *      "get"={
 *          "path" = "/admin/groupes/{id}"
 *      },
 *      "put"={
 *          "path" = "/admin/groupes/{id}"
 *      },
 *       "DELETE"={
 *          "path" = "/admin/groupes/id/apprenants"
 *      }
 *      
 *  }
 *  
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
     * @ORM\ManyToMany(targetEntity=Promo::class, inversedBy="groupes")
     * @ApiSubresource
     */
    private $promo;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, inversedBy="groupes")
     * @ApiSubresource
     */
    private $apprenant;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="groupes")
     * @ApiSubresource
     */
    private $formateur;

    public function __construct()
    {
        $this->promo = new ArrayCollection();
        $this->apprenant = new ArrayCollection();
        $this->formateur = new ArrayCollection();
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
     * @return Collection|Promo[]
     */
    public function getPromo(): Collection
    {
        return $this->promo;
    }

    public function addPromo(Promo $promo): self
    {
        if (!$this->promo->contains($promo)) {
            $this->promo[] = $promo;
        }

        return $this;
    }

    public function removePromo(Promo $promo): self
    {
        if ($this->promo->contains($promo)) {
            $this->promo->removeElement($promo);
        }

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenant(): Collection
    {
        return $this->apprenant;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenant->contains($apprenant)) {
            $this->apprenant[] = $apprenant;
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenant->contains($apprenant)) {
            $this->apprenant->removeElement($apprenant);
        }

        return $this;
    }

    /**
     * @return Collection|Formateur[]
     */
    public function getFormateur(): Collection
    {
        return $this->formateur;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateur->contains($formateur)) {
            $this->formateur[] = $formateur;
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        if ($this->formateur->contains($formateur)) {
            $this->formateur->removeElement($formateur);
        }

        return $this;
    }
}
