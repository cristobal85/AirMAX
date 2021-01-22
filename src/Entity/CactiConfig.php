<?php

namespace App\Entity;

use App\Repository\CactiConfigRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CactiConfigRepository::class)
 */
class CactiConfig
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
    private $host;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="integer")
     */
    private $port;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phpBinPath;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $installPath;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $urlGraph;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPhpBinPath(): ?string
    {
        return $this->phpBinPath;
    }

    public function setPhpBinPath(string $phpBinPath): self
    {
        $this->phpBinPath = $phpBinPath;

        return $this;
    }

    public function getInstallPath(): ?string
    {
        return $this->installPath;
    }

    public function setInstallPath(string $installPath): self
    {
        $this->installPath = $installPath;

        return $this;
    }

    public function getUrlGraph(): ?string
    {
        return $this->urlGraph;
    }

    public function setUrlGraph(string $urlGraph): self
    {
        $this->urlGraph = $urlGraph;

        return $this;
    }
}
