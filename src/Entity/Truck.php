<?php

namespace App\Entity;

use App\Repository\TruckRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TruckRepository::class)
 */
class Truck
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\NotBlank(message="Maker field can not be empty!")
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "The Maker is too short. Minimum length is {{ limit }} characters",
     *      maxMessage = "The Maker cannot be longer than {{ limit }} characters"
     * )
     */
    private $maker;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(message="Plate field can not be empty!")
     * @Assert\Length(
     *      min = 2,
     *      max = 20,
     *      minMessage = "The Plate is too short. Minimum length is {{ limit }} characters",
     *      maxMessage = "The Plate cannot be longer than {{ limit }} characters"
     * )
     */
    private $plate;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Positive(message="Year field cannot have zero or negative amount of pages")
     */
    private $make_year;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Description field can not be empty!")
     */
    private $mechanic_notices;

    /**
     * @ORM\Column(type="integer")
     *  @Assert\Positive
     */
    private $mechanic_id;

    /**
     * @ORM\ManyToOne(targetEntity=Mechanick::class, inversedBy="trucks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mechanic;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaker(): ?string
    {
        return $this->maker;
    }

    public function setMaker(string $maker): self
    {
        $this->maker = $maker;

        return $this;
    }

    public function getPlate(): ?string
    {
        return $this->plate;
    }

    public function setPlate(string $plate): self
    {
        $this->plate = $plate;

        return $this;
    }

    public function getMakeYear(): ?int
    {
        return $this->make_year;
    }

    public function setMakeYear(int $make_year): self
    {
        $this->make_year = $make_year;

        return $this;
    }

    public function getMechanicNotices(): ?string
    {
        return $this->mechanic_notices;
    }

    public function setMechanicNotices(string $mechanic_notices): self
    {
        $this->mechanic_notices = $mechanic_notices;

        return $this;
    }

    public function getMechanicId(): ?int
    {
        return $this->mechanic_id;
    }

    public function setMechanicId(int $mechanic_id): self
    {
        $this->mechanic_id = $mechanic_id;

        return $this;
    }

    public function getMechanic(): ?Mechanick
    {
        return $this->mechanic;
    }

    public function setMechanic(?Mechanick $mechanic): self
    {
        $this->mechanic = $mechanic;

        return $this;
    }
}
