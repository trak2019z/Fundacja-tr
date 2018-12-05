<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Photos
 *
 * @ORM\Table(name="photos", indexes={@ORM\Index(name="pet_id", columns={"pet_id"})})
 * @ORM\Entity
 */
class Photos
{
    /**
     * @var int
     *
     * @ORM\Column(name="photo_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $photoId;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=100, nullable=false)
     */
    private $photo;

    /**
     * @var \Pets
     *
     * @ORM\ManyToOne(targetEntity="Pets")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pet_id", referencedColumnName="pet_id")
     * })
     */
    private $pet;

    public function getPhotoId(): ?int
    {
        return $this->photoId;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getPet(): ?Pets
    {
        return $this->pet;
    }

    public function setPet(?Pets $pet): self
    {
        $this->pet = $pet;

        return $this;
    }


}
