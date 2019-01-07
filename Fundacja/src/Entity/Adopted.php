<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Adopted
 *
 * @ORM\Table(name="adopted", indexes={@ORM\Index(name="pet_id", columns={"pet_id"})})
 * @ORM\Entity
 */
class Adopted
{
    /**
     * @var int
     *
     * @ORM\Column(name="adoption_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $adoptionId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_of_adoption", type="date", nullable=false)
     */
    private $dateOfAdoption;

    /**
     * @var \Pets
     *
     * @ORM\ManyToOne(targetEntity="Pets")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pet_id", referencedColumnName="pet_id")
     * })
     */
    private $pet;

    public function getAdoptionId(): ?int
    {
        return $this->adoptionId;
    }

    public function getDateOfAdoption(): ?\DateTimeInterface
    {
        return $this->dateOfAdoption;
    }

    public function setDateOfAdoption(\DateTimeInterface $dateOfAdoption): self
    {
        $this->dateOfAdoption = $dateOfAdoption;

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
