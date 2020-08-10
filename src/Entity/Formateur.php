<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormateurRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=FormateurRepository::class)
 * @ApiResource()
 */
class Formateur extends User
{
    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, mappedBy="formateur")
     */
    private $groupes;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
            $groupe->addFormateur($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->contains($groupe)) {
            $this->groupes->removeElement($groupe);
            $groupe->removeFormateur($this);
        }

        return $this;
    }
}
