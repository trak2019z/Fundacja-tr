<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Additionals
 *
 * @ORM\Table(name="additionals", indexes={@ORM\Index(name="pet_id", columns={"pet_id"})})
 * @ORM\Entity
 */
class Additionals
{
    /**
     * @var int
     *
     * @ORM\Column(name="news_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $newsId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="news_date", type="date", nullable=false)
     */
    private $newsDate;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=5000, nullable=false)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="title", type="string", length=300, nullable=true)
     */
    private $title;

    /**
     * @var \Pets
     *
     * @ORM\ManyToOne(targetEntity="Pets")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pet_id", referencedColumnName="pet_id")
     * })
     */
    private $pet;

    public function getNewsId(): ?int
    {
        return $this->newsId;
    }

    public function getNewsDate(): ?\DateTimeInterface
    {
        return $this->newsDate;
    }

    public function setNewsDate(\DateTimeInterface $newsDate): self
    {
        $this->newsDate = $newsDate;

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

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
