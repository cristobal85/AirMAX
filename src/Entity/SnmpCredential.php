<?php

namespace App\Entity;

use App\Repository\SnmpCredentialRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SnmpCredentialRepository::class)
 */
class SnmpCredential
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
     * @ORM\Column(type="string", length=255)
     */
    private $community;

    /**
     * @ORM\OneToMany(targetEntity=Ap::class, mappedBy="snmpCredential")
     */
    private $aps;

    /**
     * @ORM\OneToMany(targetEntity=Antenna::class, mappedBy="snmpCredential")
     */
    private $antennas;

    public function __construct()
    {
        $this->aps = new ArrayCollection();
        $this->antennas = new ArrayCollection();
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

    public function getCommunity(): ?string
    {
        return $this->community;
    }

    public function setCommunity(string $community): self
    {
        $this->community = $community;

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
            $ap->setSnmpCredential($this);
        }

        return $this;
    }

    public function removeAp(Ap $ap): self
    {
        if ($this->aps->contains($ap)) {
            $this->aps->removeElement($ap);
            // set the owning side to null (unless already changed)
            if ($ap->getSnmpCredential() === $this) {
                $ap->setSnmpCredential(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Antenna[]
     */
    public function getAntennas(): Collection
    {
        return $this->antennas;
    }

    public function addAntenna(Antenna $antenna): self
    {
        if (!$this->antennas->contains($antenna)) {
            $this->antennas[] = $antenna;
            $antenna->setSnmpCredential($this);
        }

        return $this;
    }

    public function removeAntenna(Antenna $antenna): self
    {
        if ($this->antennas->contains($antenna)) {
            $this->antennas->removeElement($antenna);
            // set the owning side to null (unless already changed)
            if ($antenna->getSnmpCredential() === $this) {
                $antenna->setSnmpCredential(null);
            }
        }

        return $this;
    }
    
    public function __toString() {
        return $this->title;
    }
}
