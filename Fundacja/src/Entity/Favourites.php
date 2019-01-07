<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Favourites
 *
 * @ORM\Table(name="favourites", indexes={@ORM\Index(name="pet_id", columns={"pet_id"}), @ORM\Index(name="guest_id", columns={"guest_id"})})
 * @ORM\Entity
 */
class Favourites
{
    /**
     * @var int
     *
     * @ORM\Column(name="favourite_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $favouriteId;

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

    public function getFavouriteId(): ?int
    {
        return $this->favouriteId;
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
