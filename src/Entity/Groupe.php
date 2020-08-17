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
<<<<<<< HEAD
     * @ORM\Column(type="string",length=20)
     * @Groups({"groupe:read_All"})
=======
     * @ORM\Column(type="string", length=255)
>>>>>>> 2cb1960194d0a74dab2c30b777676b94734b5767
     */
    private $libelle;



    /**
<<<<<<< HEAD
     * @ORM\ManyToMany(targetEntity=Apprenant::class, inversedBy="groupes")
     * @ApiSubresource
     * 
     * @Groups({"groupe:read_All","groupe:read"})
=======
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="groupes")
     */
    private $promo;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, mappedBy="groupes")
     * @Groups({"promo:read"})
>>>>>>> 2cb1960194d0a74dab2c30b777676b94734b5767
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

    public function __construct()
    {
        $this->promo = new ArrayCollection();
        $this->apprenant = new ArrayCollection();
        $this->formateur = new ArrayCollection();
        $this->formateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
<<<<<<< HEAD

=======
>>>>>>> 2cb1960194d0a74dab2c30b777676b94734b5767
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }
<<<<<<< HEAD
=======

    public function getPromo(): ?Promo
    {
        return $this->promo;
    }

    public function setPromo(?Promo $promo): self
    {
        $this->promo = $promo;

        return $this;
    }

>>>>>>> 2cb1960194d0a74dab2c30b777676b94734b5767
    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenant(): Collection
    {
        return $this->apprenant;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
<<<<<<< HEAD
        if (!$this->apprenant->contains($apprenant)) {
            $this->apprenant[] = $apprenant;
=======
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
            $apprenant->addGroupe($this);
>>>>>>> 2cb1960194d0a74dab2c30b777676b94734b5767
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
<<<<<<< HEAD
        if ($this->apprenant->contains($apprenant)) {
            $this->apprenant->removeElement($apprenant);
=======
        if ($this->apprenants->contains($apprenant)) {
            $this->apprenants->removeElement($apprenant);
            $apprenant->removeGroupe($this);
>>>>>>> 2cb1960194d0a74dab2c30b777676b94734b5767
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

    public function getPromo(): ?Promo
    {
        return $this->promo;
    }

    public function setPromo(?Promo $promo): self
    {
        $this->promo = $promo;

        return $this;
    }


}
