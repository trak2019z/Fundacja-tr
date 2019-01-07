<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservations
 *
 * @ORM\Table(name="reservations", indexes={@ORM\Index(name="guest_id", columns={"guest_id"}), @ORM\Index(name="pet_id", columns={"pet_id"})})
 * @ORM\Entity
 */
class Reservations
{
    /**
     * @var int
     *
     * @ORM\Column(name="reservation_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $reservationId;

    /**
     * @var string
     *
     * @ORM\Column(name="accepted", type="string", length=8, nullable=false, options={"default"="unknown"})
     */
    private $accepted = 'unknown';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="to_when", type="date", nullable=true)
     */
    private $toWhen;

    /**
     * @var \Pets
     *
     * @ORM\ManyToOne(targetEntity="Pets")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pet_id", referencedColumnName="pet_id")
     * })
     */
    private $pet;

    /**
     * @var \Guests
     *
     * @ORM\ManyToOne(targetEntity="Guests")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="guest_id", referencedColumnName="guest_id")
     * })
     */
    private $guest;

    public function getReservationId(): ?int
    {
        return $this->reservationId;
    }

    public function getAccepted(): ?string
    {
        return $this->accepted;
    }

    public function setAccepted(string $accepted): self
    {
        $this->accepted = $accepted;

        return $this;
    }

    public function getToWhen(): ?\DateTimeInterface
    {
        return $this->toWhen;
    }

    public function setToWhen(?\DateTimeInterface $toWhen): self
    {
        $this->toWhen = $toWhen;

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

    public function getGuest(): ?Guests
    {
        return $this->guest;
    }

    public function setGuest(?Guests $guest): self
    {
        $this->guest = $guest;

        return $this;
    }


}
