<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dead
 *
 * @ORM\Table(name="dead", indexes={@ORM\Index(name="pet_id", columns={"pet_id"})})
 * @ORM\Entity
 */
class Dead
{
    /**
     * @var int
     *
     * @ORM\Column(name="death_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $deathId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_of_pass_away", type="date", nullable=false)
     */
    private $dateOfPassAway;

    /**
     * @var \Pets
     *
     * @ORM\ManyToOne(targetEntity="Pets")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pet_id", referencedColumnName="pet_id")
     * })
     */
    private $pet;

    public function getDeathId(): ?int
    {
        return $this->deathId;
    }

    public function getDateOfPassAway(): ?\DateTimeInterface
    {
        return $this->dateOfPassAway;
    }

    public function setDateOfPassAway(\DateTimeInterface $dateOfPassAway): self
    {
        $this->dateOfPassAway = $dateOfPassAway;

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
