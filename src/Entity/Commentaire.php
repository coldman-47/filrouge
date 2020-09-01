<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentaireRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CommentaireRepository::class)
 */
class Commentaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"all_comment"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"all_comment"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"all_comment"})
     */
    private $createAt;

    /**
     * @ORM\ManyToOne(targetEntity=Formateur::class, inversedBy="commentaires")
     */
    private $formateur;

    /**
     * @ORM\OneToMany(targetEntity=FilDiscution::class, mappedBy="commentaire")
     */
    private $filDiscutions;

    public function __construct()
    {
        $this->filDiscutions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

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
     * @return Collection|FilDiscution[]
     */
    public function getFilDiscutions(): Collection
    {
        return $this->filDiscutions;
    }

    public function addFilDiscution(FilDiscution $filDiscution): self
    {
        if (!$this->filDiscutions->contains($filDiscution)) {
            $this->filDiscutions[] = $filDiscution;
            $filDiscution->setCommentaire($this);
        }

        return $this;
    }

    public function removeFilDiscution(FilDiscution $filDiscution): self
    {
        if ($this->filDiscutions->contains($filDiscution)) {
            $this->filDiscutions->removeElement($filDiscution);
            // set the owning side to null (unless already changed)
            if ($filDiscution->getCommentaire() === $this) {
                $filDiscution->setCommentaire(null);
            }
        }

        return $this;
    }
}
