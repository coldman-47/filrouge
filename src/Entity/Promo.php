<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PromoRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PromoRepository::class)
 * @ApiResource(
 * subresourceOperations={
 *          "getPromo_ref"={
 *              "method"="GET",
 *              "path"="/admin/promo/{id}/referentiels"
 *          },
 * },
 * attributes = {
 *      "security" = "is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')",
 *      "security_message" = "Vous n'avez pas accès à cette ressource"
 *  },
 *  collectionOperations = {
 *      "get" = {
 *          "path" = "/admin/promos/",

 *      },
 *     "add_promo" = {
 *          "method" = "post",
 *          "path" = "/admin/promos/",
 *          "deserialize" = false,
 *      },
 *      "promo_list" = {
 *          "method" = "get",
 *          "path" = "api/admin/promo/apprenants/attente",
 *           "normalization_context"={"groups"={"promo:read_All"}},
 *           
 *      },
 *      "App_attente" = {
 *          "method" = "get",
 *          "path" = "/admin/promo/principal",
 *           "normalization_context"={"groups"={"promo:read_Attente"}},
 *           
 *      }
 *  },
 *  itemOperations = {
 *      "get" = {
 *          "path" = "/admin/promos/{id}/",
 *          "normalization_context"={"groups"={"promo:read"}}
 *      },
 *      "put" = {
 *          "path" = "/admin/promos/{id}/"
 *      },
 *      
 *  }
 * )
 * @ORM\Entity(repositoryClass=PromoRepository::class)
 */
class Promo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"promo:read","promo:read_All","promo:read_Attente"})
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:read","promo:read_All","promo:read_Attente"})
     *
     */
    private $lieu;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:read","promo:read_All","promo:read_Attente"})
     */
    private $ReferenceAgate;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:read","promo:read_All","promo:read_Attente"})
     */
    private $fabrique;

    /**
     * @ORM\Column(type="date")
     * @Groups({"promo:read","promo:read_All","promo:read_Attente"})
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     * @Groups({"promo:read","promo:read_All"})
     */
    private $dateFin;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"promo:read","promo:read_All"})
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:read","promo:read_All"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:read","promo:read_All"})
     */
    private $langue;

    /**
     * @ORM\OneToMany(targetEntity=Groupe::class, mappedBy="promo")
     * @Groups({"promo:read","promo:read_All","promo:read_Attente"})
     */
    private $groupes;

    /**
     * @ORM\ManyToMany(targetEntity=Referentiel::class, inversedBy="promos")
     * @Groups({"promo:read","promo:read_All","promo:read_Attente"})
     * 
     */
    private $referentil_promo;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="promos")
     * @Groups({"promo:read","promo:read_All"})
     */
    private $formateur;

    /**
     * @ORM\OneToMany(targetEntity=BriefMaPromo::class, mappedBy="promo")
     */
    private $briefMaPromos;

    

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        $this->referentil_promo = new ArrayCollection();
        $this->formateur = new ArrayCollection();
        $this->briefMaPromos = new ArrayCollection();
        
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

    /**
     * @return Collection|BriefMaPromo[]
     */
    public function getBriefMaPromos(): Collection
    {
        return $this->briefMaPromos;
    }

    public function addBriefMaPromo(BriefMaPromo $briefMaPromo): self
    {
        if (!$this->briefMaPromos->contains($briefMaPromo)) {
            $this->briefMaPromos[] = $briefMaPromo;
            $briefMaPromo->setPromo($this);
        }

        return $this;
    }

    public function removeBriefMaPromo(BriefMaPromo $briefMaPromo): self
    {
        if ($this->briefMaPromos->contains($briefMaPromo)) {
            $this->briefMaPromos->removeElement($briefMaPromo);
            // set the owning side to null (unless already changed)
            if ($briefMaPromo->getPromo() === $this) {
                $briefMaPromo->setPromo(null);
            }
        }

        return $this;
    }

   
}
