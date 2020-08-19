<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BriefLivrableRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=BriefLivrableRepository::class)
 */
class BriefLivrable
{

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @Id
     * @ORM\ManyToOne(targetEntity=Livrable::class, inversedBy="briefLivrables")
     * @ORM\JoinColumn(nullable=false)
     */
    private $livrable;

    /**
     * @Id
     * @ORM\ManyToOne(targetEntity=Brief::class, inversedBy="briefLivrables")
     * @ORM\JoinColumn(nullable=false)
     */
    private $brief;

   

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getLivrable(): ?Livrable
    {
        return $this->livrable;
    }

    public function setLivrable(?Livrable $livrable): self
    {
        $this->livrable = $livrable;

        return $this;
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
}
