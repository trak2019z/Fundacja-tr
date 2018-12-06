<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Logging
 *
 * @ORM\Table(name="logging")
 * @ORM\Entity
 */
class Logging
{
    /**
     * @var int
     *
     * @ORM\Column(name="logging_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $loggingId;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=80, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=20, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $password;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type_of_account", type="string", length=15, nullable=true, options={"default"="guest"})
     * @Assert\NotBlank()
     */
    private $typeOfAccount = 'guest';

    public function getLoggingId(): ?int
    {
        return $this->loggingId;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function getTypeOfAccount(): ?string
    {
        return $this->typeOfAccount;
    }

    public function setTypeOfAccount(?string $typeOfAccount): self
    {
        $this->typeOfAccount = $typeOfAccount;

        return $this;
    }


}
