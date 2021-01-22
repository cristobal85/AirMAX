<?php

namespace App\Entity;

use App\Repository\ApRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=ApRepository::class)
 * @UniqueEntity("title")
 */
class Ap
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255,  unique=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ssid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ip;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @ORM\Column(type="float")
     */
    private $latitude;

    /**
     * @ORM\Column(type="float")
     */
    private $longitude;

    /**
     * @ORM\ManyToOne(targetEntity=Cpd::class, inversedBy="aps")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cpd;

    /**
     * @ORM\ManyToOne(targetEntity=CactiTemplate::class, inversedBy="aps")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cactiTemplate;

    /**
     * @ORM\ManyToOne(targetEntity=SnmpCredential::class, inversedBy="aps")
     * @ORM\JoinColumn(nullable=false)
     */
    private $snmpCredential;

    /**
     * @ORM\ManyToOne(targetEntity=Manufacturer::class, inversedBy="aps")
     * @ORM\JoinColumn(nullable=false)
     */
    private $manufacturer;

    /**
     * @ORM\ManyToOne(targetEntity=SshCredential::class, inversedBy="aps")
     */
    private $sshCredential;

    /**
     * @ORM\ManyToOne(targetEntity=HttpCredential::class, inversedBy="aps")
     * @ORM\JoinColumn(nullable=false)
     */
    private $httpCredential;

    /**
     * @ORM\OneToMany(targetEntity=Antenna::class, mappedBy="ap")
     */
    private $antennas;

    public function __construct()
    {
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

    public function getSsid(): ?string
    {
        return $this->ssid;
    }

    public function setSsid(string $ssid): self
    {
        $this->ssid = $ssid;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

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

    public function getCpd(): ?Cpd
    {
        return $this->cpd;
    }

    public function setCpd(?Cpd $cpd): self
    {
        $this->cpd = $cpd;

        return $this;
    }

    public function getCactiTemplate(): ?CactiTemplate
    {
        return $this->cactiTemplate;
    }

    public function setCactiTemplate(?CactiTemplate $cactiTemplate): self
    {
        $this->cactiTemplate = $cactiTemplate;

        return $this;
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
            $antenna->setAp($this);
        }

        return $this;
    }

    public function removeAntenna(Antenna $antenna): self
    {
        if ($this->antennas->contains($antenna)) {
            $this->antennas->removeElement($antenna);
            // set the owning side to null (unless already changed)
            if ($antenna->getAp() === $this) {
                $antenna->setAp(null);
            }
        }

        return $this;
    }
}
