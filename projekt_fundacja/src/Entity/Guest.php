<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Guest
 *
 * @ORM\Table(name="guest", indexes={@ORM\Index(name="logging_id", columns={"logging_id"})})
 * @ORM\Entity
 */
class Guest
{
    /**
     * @var int
     *
     * @ORM\Column(name="guest_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $guestId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=20, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=40, nullable=false)
     */
    private $surname;

    /**
     * @var \Logging
     *
     * @ORM\ManyToOne(targetEntity="Logging")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="logging_id", referencedColumnName="logging_id")
     * })
     */
    private $logging;

    public function getGuestId(): ?int
    {
        return $this->guestId;
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getLogging(): ?Logging
    {
        return $this->logging;
    }

    public function setLogging(?Logging $logging): self
    {
        $this->logging = $logging;

        return $this;
    }


}
