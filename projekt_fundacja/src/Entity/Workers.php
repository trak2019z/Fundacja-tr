<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Workers
 *
 * @ORM\Table(name="workers", indexes={@ORM\Index(name="logging_id", columns={"logging_id"})})
 * @ORM\Entity
 */
class Workers
{
    /**
     * @var int
     *
     * @ORM\Column(name="worker_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $workerId;

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
     * @Assert\NotBlank()
     */
    private $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="position", type="string", length=40, nullable=false)
     * @Assert\NotBlank()
     */
    private $position;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_of_birth", type="date", nullable=false)
     * @Assert\NotBlank()
     */
    private $dateOfBirth;

    /**
     * @var \Logging
     *
     * @ORM\ManyToOne(targetEntity="Logging")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="logging_id", referencedColumnName="logging_id")
     * })
     * @Assert\NotBlank()
     * @Assert\Type("\DateTime")
     */
    private $logging;

    public function getWorkerId(): ?int
    {
        return $this->workerId;
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

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

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
