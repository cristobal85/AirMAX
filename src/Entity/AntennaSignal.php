<?php

namespace App\Entity;

use App\Repository\AntennaSignalRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass=AntennaSignalRepository::class)
 * @Vich\Uploadable
 */
class AntennaSignal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $snapshotDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;
    
    /**
     * 
     * @Vich\UploadableField(mapping="images_upload", fileNameProperty="image")
     * 
     * @var File
     * 
     * @Assert\File(
     *     maxSize = "10240k",
     *     mimeTypes = {"image/png","image/jpeg","image/jpg","image/gif",},
     *     mimeTypesMessage = "Por favor, selecciona una imagen válida."
     * )
     */
    private $imageFile;

    /**
     * @ORM\ManyToOne(targetEntity=Antenna::class, inversedBy="antennaSignals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $antenna;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;
    
    public function __construct() {
        $this->updatedAt = new \DateTime();
        $this->snapshotDate = new \DateTime();
    }
    
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSnapshotDate(): ?\DateTimeInterface
    {
        return $this->snapshotDate;
    }

    public function setSnapshotDate(\DateTimeInterface $snapshotDate): self
    {
        $this->snapshotDate = $snapshotDate;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }
    
    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function setImageFile(?File $file = null): void
    {
        $this->imageFile = $file;

        if (null !== $file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }
    
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getAntenna(): ?Antenna
    {
        return $this->antenna;
    }

    public function setAntenna(?Antenna $antenna): self
    {
        $this->antenna = $antenna;

        return $this;
    }
    
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    
    public function __toString() {
        return $this->snapshotDate->format('d/m/Y');
    }
}
