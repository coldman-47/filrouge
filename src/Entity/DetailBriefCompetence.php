<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DetailBriefCompetenceRepository;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=DetailBriefCompetenceRepository::class)
 * @Table(
 *  name = "detail_brief_competence",
 * uniqueConstraints = {
 *         @UniqueConstraint(name = "competence_brief_idx", columns = {"competence_id","brief_id"}),
 * }
 * )
 */
class DetailBriefCompetence
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Brief::class, inversedBy="detailBriefCompetences")
     * @ORM\JoinColumn(nullable=false)
     */
    private $brief;

    /**
     * @ORM\ManyToOne(targetEntity=Competence::class, inversedBy="detailBriefCompetences")
     * @ORM\JoinColumn(nullable=false)
     */
    private $competence;

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

    public function getCompetence(): ?Competence
    {
        return $this->competence;
    }

    public function setCompetence(?Competence $competence): self
    {
        $this->competence = $competence;

        return $this;
    }
}
