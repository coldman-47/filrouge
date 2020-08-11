<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PromoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PromoRepository::class)
 * @ApiResource(
 * attributes = {
 *      "security" = "is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')",
 *      "security_message" = "Vous n'avez pas accès à cette ressource"
 *  },
 *  collectionOperations = {
 *      "get" = {
 *          "path" = "/admin/promos/",
 *      },
 *      "add_promo" = {
 *          "method" = "post",
 *          "path" = "/admin/promos/",
 *          "deserialize" = false,
 *      }
 *  },
 * )
 * @ORM\Entity(repositoryClass=PromoRepository::class)
 */
class Promo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $lieu;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $ReferenceAgate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fabrique;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     */
    private $dateFin;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $langue;

    /**
     * @ORM\OneToMany(targetEntity=Groupe::class, mappedBy="promo")
     */
    private $groupes;

    /**
     * @ORM\ManyToMany(targetEntity=Referentiel::class, inversedBy="promos")
     */
    private $referentil_promo;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        $this->referentil_promo = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getReferenceAgate(): ?string
    {
        return $this->ReferenceAgate;
    }

    public function setReferenceAgate(string $ReferenceAgate): self
    {
        $this->ReferenceAgate = $ReferenceAgate;

        return $this;
    }

    public function getFabrique(): ?string
    {
        return $this->fabrique;
    }

    public function setFabrique(string $fabrique): self
    {
        $this->fabrique = $fabrique;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;

        return $this;
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
            $groupe->setPromo($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->contains($groupe)) {
            $this->groupes->removeElement($groupe);
            // set the owning side to null (unless already changed)
            if ($groupe->getPromo() === $this) {
                $groupe->setPromo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Referentiel[]
     */
    public function getReferentilPromo(): Collection
    {
        return $this->referentil_promo;
    }

    public function addReferentilPromo(Referentiel $referentilPromo): self
    {
        if (!$this->referentil_promo->contains($referentilPromo)) {
            $this->referentil_promo[] = $referentilPromo;
        }

        return $this;
    }

    public function removeReferentilPromo(Referentiel $referentilPromo): self
    {
        if ($this->referentil_promo->contains($referentilPromo)) {
            $this->referentil_promo->removeElement($referentilPromo);
        }

        return $this;
    }
}
