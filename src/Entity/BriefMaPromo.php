<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BriefMaPromoRepository;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=BriefMaPromoRepository::class)
 * @Table(
 *  name = "brief_ma_promo",
 * uniqueConstraints = {
 *         @UniqueConstraint(name = "promo_brief_idx", columns = {"promo_id","brief_id"}),
 * }
 * )
 */
class BriefMaPromo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * 
     * @ORM\ManyToOne(targetEntity=Brief::class, inversedBy="briefMaPromos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $brief;

    /**
     * 
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="briefMaPromos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $promo;

    /**
     * @ORM\OneToMany(targetEntity=BriefApprenant::class, mappedBy="briefmapromo")
     */
    private $briefApprenants;

    /**
     * @ORM\OneToMany(targetEntity=LivrablePartiel::class, mappedBy="BriefMapromo")
     */
    private $livrablePartiels;

    public function __construct()
    {
        $this->briefApprenants = new ArrayCollection();
        $this->livrablePartiels = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrief(): ?Brief
    {
        return $this->brief;
    }

    public function setBrief(?Brief $brief): self
    {
        $this->brief = $brief;

        return $this;
    }

    public function getPromo(): ?Promo
    {
        return $this->promo;
    }

    public function setPromo(?Promo $promo): self
    {
        $this->promo = $promo;

        return $this;
    }

    /**
     * @return Collection|BriefApprenant[]
     */
    public function getBriefApprenants(): Collection
    {
        return $this->briefApprenants;
    }

    public function addBriefApprenant(BriefApprenant $briefApprenant): self
    {
        if (!$this->briefApprenants->contains($briefApprenant)) {
            $this->briefApprenants[] = $briefApprenant;
            $briefApprenant->setBriefmapromo($this);
        }

        return $this;
    }

    public function removeBriefApprenant(BriefApprenant $briefApprenant): self
    {
        if ($this->briefApprenants->contains($briefApprenant)) {
            $this->briefApprenants->removeElement($briefApprenant);
            // set the owning side to null (unless already changed)
            if ($briefApprenant->getBriefmapromo() === $this) {
                $briefApprenant->setBriefmapromo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LivrablePartiel[]
     */
    public function getLivrablePartiels(): Collection
    {
        return $this->livrablePartiels;
    }

    public function addLivrablePartiel(LivrablePartiel $livrablePartiel): self
    {
        if (!$this->livrablePartiels->contains($livrablePartiel)) {
            $this->livrablePartiels[] = $livrablePartiel;
            $livrablePartiel->setBriefMapromo($this);
        }

        return $this;
    }

    public function removeLivrablePartiel(LivrablePartiel $livrablePartiel): self
    {
        if ($this->livrablePartiels->contains($livrablePartiel)) {
            $this->livrablePartiels->removeElement($livrablePartiel);
            // set the owning side to null (unless already changed)
            if ($livrablePartiel->getBriefMapromo() === $this) {
                $livrablePartiel->setBriefMapromo(null);
            }
        }

        return $this;
    }
}
