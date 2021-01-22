<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @UniqueEntity("code", message="El código del cliente ya se encuentra en la base de datos.")
 */
class Client
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", unique=true)
     * @Assert\Unique
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $adress;

    /**
     * @ORM\OneToMany(targetEntity=Antenna::class, mappedBy="client")
     */
    private $antennas;

    /**
     * @ORM\OneToMany(targetEntity=Ata::class, mappedBy="client")
     */
    private $atas;

    /**
     * @ORM\OneToMany(targetEntity=Router::class, mappedBy="client")
     */
    private $routers;

    public function __construct()
    {
        $this->antennas = new ArrayCollection();
        $this->atas = new ArrayCollection();
        $this->routers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = strtoupper($name);

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = strtoupper($adress);

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
            $antenna->setClient($this);
        }

        return $this;
    }

    public function removeAntenna(Antenna $antenna): self
    {
        if ($this->antennas->contains($antenna)) {
            $this->antennas->removeElement($antenna);
            // set the owning side to null (unless already changed)
            if ($antenna->getClient() === $this) {
                $antenna->setClient(null);
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
            $ata->setClient($this);
        }

        return $this;
    }

    public function removeAta(Ata $ata): self
    {
        if ($this->atas->contains($ata)) {
            $this->atas->removeElement($ata);
            // set the owning side to null (unless already changed)
            if ($ata->getClient() === $this) {
                $ata->setClient(null);
            }
        }

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
            $router->setClient($this);
        }

        return $this;
    }

    public function removeRouter(Router $router): self
    {
        if ($this->routers->contains($router)) {
            $this->routers->removeElement($router);
            // set the owning side to null (unless already changed)
            if ($router->getClient() === $this) {
                $router->setClient(null);
            }
        }

        return $this;
    }
    
    public function __toString() {
        return $this->name;
    }
}
