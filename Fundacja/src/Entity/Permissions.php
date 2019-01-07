<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Permissions
 *
 * @ORM\Table(name="permissions", indexes={@ORM\Index(name="worker_id", columns={"worker_id"})})
 * @ORM\Entity
 */
class Permissions
{
    /**
     * @var int
     *
     * @ORM\Column(name="permission_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $permissionId;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="add_pets", type="boolean", nullable=true)
     */
    private $addPets;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="edit_pets", type="boolean", nullable=true)
     */
    private $editPets;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="delete_pets", type="boolean", nullable=true)
     */
    private $deletePets;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="move_pets", type="boolean", nullable=true)
     */
    private $movePets;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="add_debt", type="boolean", nullable=true)
     */
    private $addDebt;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="change_debt", type="boolean", nullable=true)
     */
    private $changeDebt;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="accept_reservation", type="boolean", nullable=true)
     */
    private $acceptReservation;

    /**
     * @var \Workers
     *
     * @ORM\ManyToOne(targetEntity="Workers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="worker_id", referencedColumnName="worker_id")
     * })
     */
    private $worker;

    public function getPermissionId(): ?int
    {
        return $this->permissionId;
    }

    public function getAddPets(): ?bool
    {
        return $this->addPets;
    }

    public function setAddPets(?bool $addPets): self
    {
        $this->addPets = $addPets;

        return $this;
    }

    public function getEditPets(): ?bool
    {
        return $this->editPets;
    }

    public function setEditPets(?bool $editPets): self
    {
        $this->editPets = $editPets;

        return $this;
    }

    public function getDeletePets(): ?bool
    {
        return $this->deletePets;
    }

    public function setDeletePets(?bool $deletePets): self
    {
        $this->deletePets = $deletePets;

        return $this;
    }

    public function getMovePets(): ?bool
    {
        return $this->movePets;
    }

    public function setMovePets(?bool $movePets): self
    {
        $this->movePets = $movePets;

        return $this;
    }

    public function getAddDebt(): ?bool
    {
        return $this->addDebt;
    }

    public function setAddDebt(?bool $addDebt): self
    {
        $this->addDebt = $addDebt;

        return $this;
    }

    public function getChangeDebt(): ?bool
    {
        return $this->changeDebt;
    }

    public function setChangeDebt(?bool $changeDebt): self
    {
        $this->changeDebt = $changeDebt;

        return $this;
    }

    public function getAcceptReservation(): ?bool
    {
        return $this->acceptReservation;
    }

    public function setAcceptReservation(?bool $acceptReservation): self
    {
        $this->acceptReservation = $acceptReservation;

        return $this;
    }

    public function getWorker(): ?Workers
    {
        return $this->worker;
    }

    public function setWorker(?Workers $worker): self
    {
        $this->worker = $worker;

        return $this;
    }


}
