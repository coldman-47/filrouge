<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BriefRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 * collectionOperations = {
 *      "add_brief" = {
 *          "method" = "post",
 *          "path" = "/admin/brief/",
 *          "deserialize"=false
 *      },
 * }
 * )
 * @ORM\Entity(repositoryClass=BriefRepository::class)
 */
class Brief
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
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
    private $livrableAttendu;

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
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity=Formateur::class, inversedBy="briefs")
     */
    private $formateur;

    /**
     * @ORM\OneToMany(targetEntity=EtatBrief::class, mappedBy="brief")
     */
    private $etatBriefs;

    /**
     * @ORM\OneToMany(targetEntity=BriefMaPromo::class, mappedBy="brief")
     */
    private $briefMaPromos;

    /**
     * @ORM\OneToMany(targetEntity=BriefLivrable::class, mappedBy="brief")
     */
    private $briefLivrables;

    /**
     * @ORM\OneToMany(targetEntity=DetailBriefCompetence::class, mappedBy="brief")
     */
    private $detailBriefCompetences;

    public function __construct()
    {
        $this->etatBriefs = new ArrayCollection();
        $this->briefMaPromos = new ArrayCollection();
        $this->briefLivrables = new ArrayCollection();
        $this->detailBriefCompetences = new ArrayCollection();
       
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

    public function getLivrableAttendu(): ?string
    {
        return $this->livrableAttendu;
    }

    public function setLivrableAttendu(string $livrableAttendu): self
    {
        $this->livrableAttendu = $livrableAttendu;

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
        return $this->imagePromo;
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
     * @return Collection|DetailBriefCompetence[]
     */
    public function getDetailBriefCompetences(): Collection
    {
        return $this->detailBriefCompetences;
    }

    public function addDetailBriefCompetence(DetailBriefCompetence $detailBriefCompetence): self
    {
        if (!$this->detailBriefCompetences->contains($detailBriefCompetence)) {
            $this->detailBriefCompetences[] = $detailBriefCompetence;
            $detailBriefCompetence->setBrief($this);
        }

        return $this;
    }

    public function removeDetailBriefCompetence(DetailBriefCompetence $detailBriefCompetence): self
    {
        if ($this->detailBriefCompetences->contains($detailBriefCompetence)) {
            $this->detailBriefCompetences->removeElement($detailBriefCompetence);
            // set the owning side to null (unless already changed)
            if ($detailBriefCompetence->getBrief() === $this) {
                $detailBriefCompetence->setBrief(null);
            }
        }

        return $this;
    }

    
}
