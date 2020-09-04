<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BriefRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *  collectionOperations = {
 *      "getBriefs" = {
 *          "method" = "get",
 *          "path" = "/formateur/briefs/",
 *          "normalization_context" = {"groups" = {"briefs"}}
 *      },
 *      "getBriefByGroupPromo"={
 *          "method" = "get",
 *          "path"="/formateur/promo/{id}/groupe/{ID}/briefs",
 *          "deserialize" = false
 *      },
 *      "add_brief" = {
 *          "method" = "post",
 *          "path" = "/formateur/brief/",
 *          "deserialize"=false
 *      },
 *      "getBriefByPromo" = {
 *          "method" = "get",
 *          "path" = "formateur/promo/{id}/briefs",
 *          "deserialize"=false
 *      },
 *      "addlivrablesByGroupeApprenant" = {
 *          "method" = "post",
 *          "path" = "/apprenants/{id}/groupe/{id2}/livrables",
 *          "deserialize"=false
 *      },
 *      "duplicateBrief" = {
 *          "method" = "post",
 *          "path" = "/formateur/briefs/{id}",
 *          "deserialize"=false
 *      }
 *  },
 * itemOperations = {
 *      "get",
 *      "editBriefByPromo"={
 *          "method" = "PUT",
 *          "path"="/api/formateur/promo/{id1}/brief/{id2}",
 *           "deserialize" = false
 *      },
 *      "getOnebriefByPromoApprenant"={
 *          "method" = "GET",
 *          "path"="apprenant/{id}/promo/{id2}/briefs/{id3}",
 *          "deserialize" = false
 *         
 *      },
 *      "assignationBrief"={
 *          "method" = "PUT",
 *          "security" = "is_granted('ROLE_ADMIN')",
 *          "security_message" = "Accès refusé!",
 *          "path"="/api/formateur/promo/{id}/brief/{id2}",
 *          "deserialize" = false
 *      }
 *  }
 * )
 * @ORM\Entity(repositoryClass=BriefRepository::class)
 */
class Brief
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"briefs"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"briefs", "formbrief"})
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"briefs"})
     */
    private $nomBrief;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $contexte;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $modalitePedagogique;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $critereEvaluation;

    /**
     * @ORM\Column(type="blob",nullable=true)
     * @Groups({"briefs"})
     */
    private $imagePromo;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $archiver;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $createAt;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Groups({"briefs"})
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity=Formateur::class, inversedBy="briefs")
     * @Groups({"briefs"})
     */
    private $formateur;

    /**
     * @ORM\OneToMany(targetEntity=EtatBrief::class, mappedBy="brief", cascade={"persist"})
     * @Groups({"briefs"})
     */
    private $etatBriefs;

    /**
     * @ORM\OneToMany(targetEntity=BriefMaPromo::class, mappedBy="brief", cascade={"persist"})
     * @Groups({"briefs"})
     */
    private $briefMaPromos;

    /**
     * @ORM\OneToMany(targetEntity=BriefLivrable::class, mappedBy="brief")
     * @Groups({"briefs"})
     */
    private $briefLivrables;

    /**
     * @ORM\OneToMany(targetEntity=Ressource::class, mappedBy="brief")
     * @Groups({"briefs"})
     */
    private $ressources;

    /**
     * @ORM\ManyToMany(targetEntity=Niveau::class, inversedBy="briefs")
     * @Groups({"briefs"})
     */
    private $niveau;

    public function __construct()
    {
        $this->etatBriefs = new ArrayCollection();
        $this->briefMaPromos = new ArrayCollection();
        $this->briefLivrables = new ArrayCollection();

        $this->ressources = new ArrayCollection();
        $this->niveau = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNomBrief(): ?string
    {
        return $this->nomBrief;
    }

    public function setNomBrief(string $nomBrief): self
    {
        $this->nomBrief = $nomBrief;

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

    public function getContexte(): ?string
    {
        return $this->contexte;
    }

    public function setContexte(string $contexte): self
    {
        $this->contexte = $contexte;

        return $this;
    }

    public function getModalitePedagogique(): ?string
    {
        return $this->modalitePedagogique;
    }

    public function setModalitePedagogique(string $modalitePedagogique): self
    {
        $this->modalitePedagogique = $modalitePedagogique;

        return $this;
    }

    public function getCritereEvaluation(): ?string
    {
        return $this->critereEvaluation;
    }

    public function setCritereEvaluation(string $critereEvaluation): self
    {
        $this->critereEvaluation = $critereEvaluation;

        return $this;
    }

    public function getImagePromo()
    {
        $ip = $this->imagePromo;
        if (!is_resource($ip)) {
            return $ip;
        }
        return base64_encode(stream_get_contents($ip));
    }

    public function setImagePromo($imagePromo): self
    {
        $this->imagePromo = $imagePromo;

        return $this;
    }

    public function getArchiver(): ?bool
    {
        return $this->archiver;
    }

    public function setArchiver(bool $archiver): self
    {
        $this->archiver = $archiver;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getFormateur(): ?Formateur
    {
        return $this->formateur;
    }

    public function setFormateur(?Formateur $formateur): self
    {
        $this->formateur = $formateur;

        return $this;
    }

    /**
     * @return Collection|EtatBrief[]
     */
    public function getEtatBriefs(): Collection
    {
        return $this->etatBriefs;
    }

    public function addEtatBrief(EtatBrief $etatBrief): self
    {
        if (!$this->etatBriefs->contains($etatBrief)) {
            $this->etatBriefs[] = $etatBrief;
            $etatBrief->setBrief($this);
        }

        return $this;
    }

    public function removeEtatBrief(EtatBrief $etatBrief): self
    {
        if ($this->etatBriefs->contains($etatBrief)) {
            $this->etatBriefs->removeElement($etatBrief);
            // set the owning side to null (unless already changed)
            if ($etatBrief->getBrief() === $this) {
                $etatBrief->setBrief(null);
            }
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
            $briefMaPromo->setBrief($this);
        }

        return $this;
    }

    public function removeBriefMaPromo(BriefMaPromo $briefMaPromo): self
    {
        if ($this->briefMaPromos->contains($briefMaPromo)) {
            $this->briefMaPromos->removeElement($briefMaPromo);
            // set the owning side to null (unless already changed)
            if ($briefMaPromo->getBrief() === $this) {
                $briefMaPromo->setBrief(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|BriefLivrable[]
     */
    public function getBriefLivrables(): Collection
    {
        return $this->briefLivrables;
    }

    public function addBriefLivrable(BriefLivrable $briefLivrable): self
    {
        if (!$this->briefLivrables->contains($briefLivrable)) {
            $this->briefLivrables[] = $briefLivrable;
            $briefLivrable->setBrief($this);
        }

        return $this;
    }

    public function removeBriefLivrable(BriefLivrable $briefLivrable): self
    {
        if ($this->briefLivrables->contains($briefLivrable)) {
            $this->briefLivrables->removeElement($briefLivrable);
            // set the owning side to null (unless already changed)
            if ($briefLivrable->getBrief() === $this) {
                $briefLivrable->setBrief(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection|Ressource[]
     */
    public function getRessources(): Collection
    {
        return $this->ressources;
    }

    public function addRessource(Ressource $ressource): self
    {
        if (!$this->ressources->contains($ressource)) {
            $this->ressources[] = $ressource;
            $ressource->setBrief($this);
        }

        return $this;
    }

    public function removeRessource(Ressource $ressource): self
    {
        if ($this->ressources->contains($ressource)) {
            $this->ressources->removeElement($ressource);
            // set the owning side to null (unless already changed)
            if ($ressource->getBrief() === $this) {
                $ressource->setBrief(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Niveau[]
     */
    public function getNiveau(): Collection
    {
        return $this->niveau;
    }

    public function addNiveau(Niveau $niveau): self
    {
        if (!$this->niveau->contains($niveau)) {
            $this->niveau[] = $niveau;
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        if ($this->niveau->contains($niveau)) {
            $this->niveau->removeElement($niveau);
        }

        return $this;
    }
}
