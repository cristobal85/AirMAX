<?php

namespace App\Entity;

use App\Repository\HttpCredentialRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HttpCredentialRepository::class)
 */
class HttpCredential
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
     * @ORM\Column(type="string", length=255)
     */
    private $protocol;

    /**
     * @ORM\OneToMany(targetEntity=Router::class, mappedBy="httpCredential")
     */
    private $routers;

    /**
     * @ORM\OneToMany(targetEntity=Ata::class, mappedBy="httpCredential")
     */
    private $atas;

    /**
     * @ORM\OneToMany(targetEntity=Antenna::class, mappedBy="httpCredential")
     */
    private $antennas;

    /**
     * @ORM\OneToMany(targetEntity=Ap::class, mappedBy="httpCredential")
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

    public function getProtocol(): ?string
    {
        return $this->protocol;
    }

    public function setProtocol(string $protocol): self
    {
        $this->protocol = $protocol;

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
            $router->setHttpCredential($this);
        }

        return $this;
    }

    public function removeRouter(Router $router): self
    {
        if ($this->routers->contains($router)) {
            $this->routers->removeElement($router);
            // set the owning side to null (unless already changed)
            if ($router->getHttpCredential() === $this) {
                $router->setHttpCredential(null);
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
            $ata->setHttpCredential($this);
        }

        return $this;
    }

    public function removeAta(Ata $ata): self
    {
        if ($this->atas->contains($ata)) {
            $this->atas->removeElement($ata);
            // set the owning side to null (unless already changed)
            if ($ata->getHttpCredential() === $this) {
                $ata->setHttpCredential(null);
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
            $antenna->setHttpCredential($this);
        }

        return $this;
    }

    public function removeAntenna(Antenna $antenna): self
    {
        if ($this->antennas->contains($antenna)) {
            $this->antennas->removeElement($antenna);
            // set the owning side to null (unless already changed)
            if ($antenna->getHttpCredential() === $this) {
                $antenna->setHttpCredential(null);
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
            $ap->setHttpCredential($this);
        }

        return $this;
    }

    public function removeAp(Ap $ap): self
    {
        if ($this->aps->contains($ap)) {
            $this->aps->removeElement($ap);
            // set the owning side to null (unless already changed)
            if ($ap->getHttpCredential() === $this) {
                $ap->setHttpCredential(null);
            }
        }

        return $this;
    }
    
    public function __toString() {
        return $this->title;
    }
}
