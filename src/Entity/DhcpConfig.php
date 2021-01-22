<?php

namespace App\Entity;

use App\Repository\DhcpConfigRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DhcpConfigRepository::class)
 */
class DhcpConfig
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $logPath;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $host;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $password;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     */
    private $port;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $antennaPath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ataPath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $routerPath;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $staticPath;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * 
     */
    private $dhcpMainFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $antennaSubclass;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ataSubclass;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $routerSubclass;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dhcpInitScript;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogPath(): ?string
    {
        return $this->logPath;
    }

    public function setLogPath(string $logPath): self
    {
        $this->logPath = $logPath;

        return $this;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function setPort(int $port): self
    {
        $this->port = $port;

        return $this;
    }

    public function getAntennaPath(): ?string
    {
        return $this->antennaPath;
    }

    public function setAntennaPath(string $antennaPath): self
    {
        $this->antennaPath = $antennaPath;

        return $this;
    }

    public function getAtaPath(): ?string
    {
        return $this->ataPath;
    }

    public function setAtaPath(?string $ataPath): self
    {
        $this->ataPath = $ataPath;

        return $this;
    }

    public function getRouterPath(): ?string
    {
        return $this->routerPath;
    }

    public function setRouterPath(?string $routerPath): self
    {
        $this->routerPath = $routerPath;

        return $this;
    }

    public function getStaticPath(): ?string
    {
        return $this->staticPath;
    }

    public function setStaticPath(string $staticPath): self
    {
        $this->staticPath = $staticPath;

        return $this;
    }

    public function getDhcpMainFile(): ?string
    {
        return $this->dhcpMainFile;
    }

    public function setDhcpMainFile(string $dhcpMainFile): self
    {
        $this->dhcpMainFile = $dhcpMainFile;

        return $this;
    }

    public function getAntennaSubclass(): ?string
    {
        return $this->antennaSubclass;
    }

    public function setAntennaSubclass(string $antennaSubclass): self
    {
        $this->antennaSubclass = $antennaSubclass;

        return $this;
    }

    public function getAtaSubclass(): ?string
    {
        return $this->ataSubclass;
    }

    public function setAtaSubclass(?string $ataSubclass): self
    {
        $this->ataSubclass = $ataSubclass;

        return $this;
    }

    public function getRouterSubclass(): ?string
    {
        return $this->routerSubclass;
    }

    public function setRouterSubclass(?string $routerSubclass): self
    {
        $this->routerSubclass = $routerSubclass;

        return $this;
    }

    public function getDhcpInitScript(): ?string
    {
        return $this->dhcpInitScript;
    }

    public function setDhcpInitScript(string $dhcpInitScript): self
    {
        $this->dhcpInitScript = $dhcpInitScript;

        return $this;
    }
}
