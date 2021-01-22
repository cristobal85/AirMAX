<?php

namespace App\Entity;

use App\Repository\AntennaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=AntennaRepository::class)
 * @UniqueEntity("mac", message="La MAC ya se encuentra en la base de datos.")
 */
class Antenna implements DeviceEntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Regex(pattern="/^([0-9A-Fa-f]{2}[:]){5}([0-9A-Fa-f]{2})$/", message="El formato debe ser XX:XX:XX:XX:XX:XX")
     */
    private $mac;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $longitude;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active = true;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cactiNodeId;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="antennas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity=AntennaSignal::class, mappedBy="antenna", cascade={"persist","remove"}, orphanRemoval=true)
     */
    private $antennaSignals;

    /**
     * @ORM\ManyToOne(targetEntity=Manufacturer::class, inversedBy="antennas")
     * @ORM\JoinColumn(nullable=true)
     */
    private $manufacturer;

    /**
     * @ORM\ManyToOne(targetEntity=SshCredential::class, inversedBy="antennas")
     * @ORM\JoinColumn(nullable=true)
     */
    private $sshCredential;

    /**
     * @ORM\ManyToOne(targetEntity=HttpCredential::class, inversedBy="antennas")
     * @ORM\JoinColumn(nullable=true)
     */
    private $httpCredential;

    /**
     * @ORM\ManyToOne(targetEntity=Ap::class, inversedBy="antennas")
     * @ORM\JoinColumn(nullable=true)
     */
    private $ap;

    /**
     * @ORM\ManyToOne(targetEntity=SnmpCredential::class, inversedBy="antennas")
     */
    private $snmpCredential;

    public function __construct()
    {
        $this->antennaSignals = new ArrayCollection();
    }

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

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getCactiNodeId(): ?int
    {
        return $this->cactiNodeId;
    }

    public function setCactiNodeId(?int $cactiNodeId): self
    {
        $this->cactiNodeId = $cactiNodeId;

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

    /**
     * @return Collection|AntennaSignal[]
     */
    public function getAntennaSignals(): Collection
    {
        return $this->antennaSignals;
    }

    public function addAntennaSignal(AntennaSignal $antennaSignal): self
    {
        if (!$this->antennaSignals->contains($antennaSignal)) {
            $this->antennaSignals[] = $antennaSignal;
            $antennaSignal->setAntenna($this);
        }

        return $this;
    }

    public function removeAntennaSignal(AntennaSignal $antennaSignal): self
    {
        if ($this->antennaSignals->contains($antennaSignal)) {
            $this->antennaSignals->removeElement($antennaSignal);
            // set the owning side to null (unless already changed)
            if ($antennaSignal->getAntenna() === $this) {
                $antennaSignal->setAntenna(null);
            }
        }

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

    public function getAp(): ?Ap
    {
        return $this->ap;
    }

    public function setAp(?Ap $ap): self
    {
        $this->ap = $ap;

        return $this;
    }
    
    public function __toString() {
        return $this->mac;
    }

    public function getSnmpCredential(): ?SnmpCredential
    {
        return $this->snmpCredential;
    }

    public function setSnmpCredential(?SnmpCredential $snmpCredential): self
    {
        $this->snmpCredential = $snmpCredential;

        return $this;
    }
}
