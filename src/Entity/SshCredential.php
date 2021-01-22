<?php

namespace App\Entity;

use App\Repository\SshCredentialRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SshCredentialRepository::class)
 */
class SshCredential
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
     * @ORM\OneToMany(targetEntity=Router::class, mappedBy="sshCredential")
     */
    private $router;

    /**
     * @ORM\OneToMany(targetEntity=Ata::class, mappedBy="sshCredential")
     */
    private $atas;

    /**
     * @ORM\OneToMany(targetEntity=Antenna::class, mappedBy="sshCredential")
     */
    private $antennas;

    /**
     * @ORM\OneToMany(targetEntity=Ap::class, mappedBy="sshCredential")
     */
    private $aps;

    public function __construct()
    {
        $this->router = new ArrayCollection();
        $this->atas = new ArrayCollection();
        $this->antennas = new ArrayCollection();
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

    /**
     * @return Collection|Router[]
     */
    public function getRouter(): Collection
    {
        return $this->router;
    }

    public function addRouter(Router $router): self
    {
        if (!$this->router->contains($router)) {
            $this->router[] = $router;
            $router->setSshCredential($this);
        }

        return $this;
    }

    public function removeRouter(Router $router): self
    {
        if ($this->router->contains($router)) {
            $this->router->removeElement($router);
            // set the owning side to null (unless already changed)
            if ($router->getSshCredential() === $this) {
                $router->setSshCredential(null);
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
            $ata->setSshCredential($this);
        }

        return $this;
    }

    public function removeAta(Ata $ata): self
    {
        if ($this->atas->contains($ata)) {
            $this->atas->removeElement($ata);
            // set the owning side to null (unless already changed)
            if ($ata->getSshCredential() === $this) {
                $ata->setSshCredential(null);
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
            $antenna->setSshCredential($this);
        }

        return $this;
    }

    public function removeAntenna(Antenna $antenna): self
    {
        if ($this->antennas->contains($antenna)) {
            $this->antennas->removeElement($antenna);
            // set the owning side to null (unless already changed)
            if ($antenna->getSshCredential() === $this) {
                $antenna->setSshCredential(null);
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
            $ap->setSshCredential($this);
        }

        return $this;
    }

    public function removeAp(Ap $ap): self
    {
        if ($this->aps->contains($ap)) {
            $this->aps->removeElement($ap);
            // set the owning side to null (unless already changed)
            if ($ap->getSshCredential() === $this) {
                $ap->setSshCredential(null);
            }
        }

        return $this;
    }
    
    
    public function __toString() {
        return $this->title;
    }
}
