<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 * @ApiResource(
 *  normalizationContext={
 * "groups"={
 *          "groupe:read_All"
 *      }
 *  },
 *  collectionOperations = {
 *      "get_groupes"={
 *          "method" = "get",
 *          "path" = "/admin/groupes",
 *          "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *          "security_message" = "Accès refusé!"
 *      },
 *      "add_groupes" = {
 *          "method" = "post",
 *          "path" = "/admin/groupes",
 *          "deserialise"=false,
 *          "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *          "security_message" = "Accès refusé!"
 *      },
 *      "get_groupes_apprenants"={
 *          "method" = "get",
 *          "path" = "admin/groupes/apprenants",
 *          "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *          "security_message" = "Accès refusé!",
 *          "normalization_context"={"groups"={"groupe:read"}}
 *      }
 *  },
 * itemOperations = {
 *      "get"={
 *          "path" = "/admin/groupes/{id}",
 *          "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *          "security_message" = "Accès refusé!"
 *      },
 *      "update_groupe"={
 *          "method"="put",
 *          "path" = "/admin/groupes/{id}",
 *         "deserialize"=false,
 *          "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *          "security_message" = "Accès refusé!"
 *      },
 *       "delete_groupe"={
 *          "method"="delete",
 *          "path" = "/admin/groupes/{id}/apprenants",
 *          "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *          "security_message" = "Accès refusé!"
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
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, mappedBy="groupes")
     *@Groups({"promo:read","promo:read_All","promo:read_Attente"})
     */
    private $apprenant;
    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, mappedBy="groupe")
     * @ApiSubresource
     *  @Groups({"groupe:read_All"})
     */
    private $formateurs;
    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="groupes")
     * @ApiSubresource
     *@Groups({"groupe:read_All"}) 
     */
    private $promo;

    /**
     * @ORM\OneToMany(targetEntity=EtatBrief::class, mappedBy="groupe")
     */
    private $etatBriefs;

    public function __construct()
    {
        $this->promo = new ArrayCollection();
        $this->apprenant = new ArrayCollection();
        $this->formateur = new ArrayCollection();
        $this->formateurs = new ArrayCollection();
        $this->etatBriefs = new ArrayCollection();
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
     * @return Collection|Apprenant[]
     */
    public function getApprenant(): Collection
    {
        return $this->apprenant;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
            $apprenant->addGroupe($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->contains($apprenant)) {
            $this->apprenants->removeElement($apprenant);
            $apprenant->removeGroupe($this);
        }

        return $this;
    }

    /**
     * @return Collection|Formateur[]
     */
    public function getFormateurs(): Collection
    {
        return $this->formateurs;
    }
    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateurs->contains($formateur)) {
            $this->formateurs[] = $formateur;
            $formateur->addGroupe($this);
        }
        return $this;
    }
    public function removeFormateur(Formateur $formateur): self
    {
        if ($this->formateurs->contains($formateur)) {
            $this->formateurs->removeElement($formateur);
            $formateur->removeGroupe($this);
        }

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
            $etatBrief->setGroupe($this);
        }

        return $this;
    }

    public function removeEtatBrief(EtatBrief $etatBrief): self
    {
        if ($this->etatBriefs->contains($etatBrief)) {
            $this->etatBriefs->removeElement($etatBrief);
            // set the owning side to null (unless already changed)
            if ($etatBrief->getGroupe() === $this) {
                $etatBrief->setGroupe(null);
            }
        }

        return $this;
    }
}
