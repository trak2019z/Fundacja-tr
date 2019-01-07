<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pets
 *
 * @ORM\Table(name="pets")
 * @ORM\Entity
 */
class Pets
{
    /**
     * @var int
     *
     * @ORM\Column(name="pet_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $petId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=20, nullable=false)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="number", type="string", length=20, nullable=true)
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=5000, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="main_pic", type="string", length=100, nullable=false)
     */
    private $mainPic;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_of_birth", type="date", nullable=false)
     */
    private $dateOfBirth;

    /**
     * @var string|null
     *
     * @ORM\Column(name="state", type="string", length=10, nullable=true, options={"default"="avaliable"})
     */
    private $state = 'avaliable';

    public function getPetId(): ?int
    {
        return $this->petId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMainPic(): ?string
    {
        return $this->mainPic;
    }

    public function setMainPic(string $mainPic): self
    {
        $this->mainPic = $mainPic;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(\DateTimeInterface $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }


}
