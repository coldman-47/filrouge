<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfilSortieRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProfilSortieRepository::class)
 * @ApiResource(
 *  normalizationContext={
 *      "groups"={
 *          "profil:read"
 *      }
 *  }
 * )
 */
class ProfilSortie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @ApiSubresource()
     * @Groups({"profil:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @ApiSubresource()
     * @Groups({"profil:read"})
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="profilSorties")
     * @ApiSubresource()
     * @Groups({"profil:read"})
     */
    private $ProfilApprenant;

    public function __construct()
    {
        $this->ProfilApprenant = new ArrayCollection();
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
     * @return Collection|User[]
     */
    public function getProfilApprenant(): Collection
    {
        return $this->ProfilApprenant;
    }

    public function addProfilApprenant(User $profilApprenant): self
    {
        if (!$this->ProfilApprenant->contains($profilApprenant)) {
            $this->ProfilApprenant[] = $profilApprenant;
        }

        return $this;
    }

    public function removeProfilApprenant(User $profilApprenant): self
    {
        if ($this->ProfilApprenant->contains($profilApprenant)) {
            $this->ProfilApprenant->removeElement($profilApprenant);
        }

        return $this;
    }
}
