<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Debts
 *
 * @ORM\Table(name="debts", indexes={@ORM\Index(name="pet_id", columns={"pet_id"})})
 * @ORM\Entity
 */
class Debts
{
    /**
     * @var int
     *
     * @ORM\Column(name="debt_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $debtId;

    /**
     * @var float
     *
     * @ORM\Column(name="debt_value", type="float", precision=6, scale=2, nullable=false)
     */
    private $debtValue;

    /**
     * @var float|null
     *
     * @ORM\Column(name="paid", type="float", precision=6, scale=2, nullable=true)
     */
    private $paid;

    /**
     * @var \Pets
     *
     * @ORM\ManyToOne(targetEntity="Pets")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pet_id", referencedColumnName="pet_id")
     * })
     */
    private $pet;

    public function getDebtId(): ?int
    {
        return $this->debtId;
    }

    public function getDebtValue(): ?float
    {
        return $this->debtValue;
    }

    public function setDebtValue(float $debtValue): self
    {
        $this->debtValue = $debtValue;

        return $this;
    }

    public function getPaid(): ?float
    {
        return $this->paid;
    }

    public function setPaid(?float $paid): self
    {
        $this->paid = $paid;

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
