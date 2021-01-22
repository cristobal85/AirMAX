<?php

namespace App\Entity;

use App\Repository\CactiTemplateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CactiTemplateRepository::class)
 */
class CactiTemplate
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
    private $title;

    /**
     * @ORM\Column(type="integer")
     */
    private $hostTemplate;

    /**
     * @ORM\Column(type="integer")
     */
    private $treeId;

    /**
     * @ORM\Column(type="integer")
     */
    private $snmpVersion;

    /**
     * @ORM\OneToMany(targetEntity=Ap::class, mappedBy="cactiTemplate")
     */
    private $aps;

    public function __construct()
    {
        $this->aps = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getHostTemplate(): ?int
    {
        return $this->hostTemplate;
    }

    public function setHostTemplate(int $hostTemplate): self
    {
        $this->hostTemplate = $hostTemplate;

        return $this;
    }

    public function getTreeId(): ?int
    {
        return $this->treeId;
    }

    public function setTreeId(int $treeId): self
    {
        $this->treeId = $treeId;

        return $this;
    }

    public function getSnmpVersion(): ?int
    {
        return $this->snmpVersion;
    }

    public function setSnmpVersion(int $snmpVersion): self
    {
        $this->snmpVersion = $snmpVersion;

        return $this;
    }

    /**
     * @return Collection|Ap[]
     */
    public function getAps(): Collection
    {
        return $this->aps;
    }

    public function addAp(Ap $ap): self
    {
        if (!$this->aps->contains($ap)) {
            $this->aps[] = $ap;
            $ap->setCactiTemplate($this);
        }

        return $this;
    }

    public function removeAp(Ap $ap): self
    {
        if ($this->aps->contains($ap)) {
            $this->aps->removeElement($ap);
            // set the owning side to null (unless already changed)
            if ($ap->getCactiTemplate() === $this) {
                $ap->setCactiTemplate(null);
            }
        }

        return $this;
    }
}
