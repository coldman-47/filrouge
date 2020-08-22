<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 * @ApiResource(
 *   attributes = {
 *      "security" = "is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')",
 *      "security_message" = "Vous n'avez pas accès à cette ressource"
 *  },
 *  collectionOperations = {
 *      "get" = {
 *          "path" = "/admin/tags/"
 *      },
 *      "add_tags" = {
 *          "method" = "post",
 *          "path" = "/admin/tags/"
 *      }
 *  },
 *  itemOperations = {
*       "put" = {
 *          "path" = "/admin/tags/{id}/"
 *      },
 *      "get" = {
 *          "path" = "/admin/tags/{id}/"
 *      },
 *  }
 * )
 */
class Tag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeTag::class, inversedBy="tags", cascade={"persist"})
     */
    private $GroupeTag;

    public function __construct()
    {
        $this->GroupeTag = new ArrayCollection();
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
     * @return Collection|GroupeTag[]
     */
    public function getGroupeTag(): Collection
    {
        return $this->GroupeTag;
    }

    public function addGroupeTag(GroupeTag $groupeTag): self
    {
        if (!$this->GroupeTag->contains($groupeTag)) {
            $this->GroupeTag[] = $groupeTag;
        }

        return $this;
    }

    public function removeGroupeTag(GroupeTag $groupeTag): self
    {
        if ($this->GroupeTag->contains($groupeTag)) {
            $this->GroupeTag->removeElement($groupeTag);
        }

        return $this;
    }
}
