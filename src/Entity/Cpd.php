<?php

namespace App\Entity;

use App\Repository\CpdRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CpdRepository::class)
 */
class Cpd
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
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $latitude;

    /**
     * @ORM\Column(type="float")
     */
    private $longitude;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity=Ap::class, mappedBy="cpd")
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

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
            $ap->setCpd($this);
        }

        return $this;
    }

    public function removeAp(Ap $ap): self
    {
        if ($this->aps->contains($ap)) {
            $this->aps->removeElement($ap);
            // set the owning side to null (unless already changed)
            if ($ap->getCpd() === $this) {
                $ap->setCpd(null);
            }
        }

        return $this;
    }
    
    public function __toString() {
     return $this->name;
 }
}
