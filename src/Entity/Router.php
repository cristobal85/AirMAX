<?php

namespace App\Entity;

use App\Repository\RouterRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RouterRepository::class)
 */
class Router implements DeviceEntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(pattern="/^([0-9A-Fa-f]{2}[:]){5}([0-9A-Fa-f]{2})$/", message="El formato debe ser XX:XX:XX:XX:XX:XX")
     */
    private $mac;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active = true;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="routers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity=Manufacturer::class, inversedBy="routers")
     * @ORM\JoinColumn(nullable=true)
     */
    private $manufacturer;

    /**
     * @ORM\ManyToOne(targetEntity=SshCredential::class, inversedBy="router")
     * @ORM\JoinColumn(nullable=true)
     */
    private $sshCredential;

    /**
     * @ORM\ManyToOne(targetEntity=HttpCredential::class, inversedBy="routers")
     * @ORM\JoinColumn(nullable=true)
     */
    private $httpCredential;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMac(): ?string
    {
        return $this->mac;
    }

    public function setMac(string $mac): self
    {
        $this->mac = $mac;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getManufacturer(): ?Manufacturer
    {
        return $this->manufacturer;
    }

    public function setManufacturer(?Manufacturer $manufacturer): self
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    public function getSshCredential(): ?SshCredential
    {
        return $this->sshCredential;
    }

    public function setSshCredential(?SshCredential $sshCredential): self
    {
        $this->sshCredential = $sshCredential;

        return $this;
    }

    public function getHttpCredential(): ?HttpCredential
    {
        return $this->httpCredential;
    }

    public function setHttpCredential(?HttpCredential $httpCredential): self
    {
        $this->httpCredential = $httpCredential;

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
    
    public function __toString() {
        return $this->mac;
    }
}
