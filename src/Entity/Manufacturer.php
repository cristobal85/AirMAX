<?php

namespace App\Entity;

use App\Repository\ManufacturerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ManufacturerRepository::class)
 */
class Manufacturer
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
     * @ORM\OneToMany(targetEntity=Router::class, mappedBy="manufacturer")
     */
    private $routers;

    /**
     * @ORM\OneToMany(targetEntity=Ata::class, mappedBy="manufacturer")
     */
    private $atas;

    /**
     * @ORM\OneToMany(targetEntity=Antenna::class, mappedBy="manufacturer")
     */
    private $antennas;

    /**
     * @ORM\OneToMany(targetEntity=Ap::class, mappedBy="manufacturer")
     */
    private $aps;

    public function __construct()
    {
        $this->routers = new ArrayCollection();
        $this->atas = new ArrayCollection();
        $this->antennas = new ArrayCollection();
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

    /**
     * @return Collection|Router[]
     */
    public function getRouters(): Collection
    {
        return $this->routers;
    }

    public function addRouter(Router $router): self
    {
        if (!$this->routers->contains($router)) {
            $this->routers[] = $router;
            $router->setManufacturer($this);
        }

        return $this;
    }

    public function removeRouter(Router $router): self
    {
        if ($this->routers->contains($router)) {
            $this->routers->removeElement($router);
            // set the owning side to null (unless already changed)
            if ($router->getManufacturer() === $this) {
                $router->setManufacturer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ata[]
     */
    public function getAtas(): Collection
    {
        return $this->atas;
    }

    public function addAta(Ata $ata): self
    {
        if (!$this->atas->contains($ata)) {
            $this->atas[] = $ata;
            $ata->setManufacturer($this);
        }

        return $this;
    }

    public function removeAta(Ata $ata): self
    {
        if ($this->atas->contains($ata)) {
            $this->atas->removeElement($ata);
            // set the owning side to null (unless already changed)
            if ($ata->getManufacturer() === $this) {
                $ata->setManufacturer(null);
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
            $antenna->setManufacturer($this);
        }

        return $this;
    }

    public function removeAntenna(Antenna $antenna): self
    {
        if ($this->antennas->contains($antenna)) {
            $this->antennas->removeElement($antenna);
            // set the owning side to null (unless already changed)
            if ($antenna->getManufacturer() === $this) {
                $antenna->setManufacturer(null);
            }
        }

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
            $ap->setManufacturer($this);
        }

        return $this;
    }

    public function removeAp(Ap $ap): self
    {
        if ($this->aps->contains($ap)) {
            $this->aps->removeElement($ap);
            // set the owning side to null (unless already changed)
            if ($ap->getManufacturer() === $this) {
                $ap->setManufacturer(null);
            }
        }

        return $this;
    }
    
    public function __toString() {
        return $this->name;
    }
}
