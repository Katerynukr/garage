<?php

namespace App\Entity;

use App\Repository\MechanickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MechanickRepository::class)
 */
class Mechanick
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Name field can not be empty!")
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "The name is too short. Minimum length is {{ limit }} characters",
     *      maxMessage = "The name cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Type(
     *     type="alpha",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Surname field can not be empty!")
     * @Assert\Length(
     *      min = 2,
     *      max = 64,
     *      minMessage = "The surname is too short. Minimum length is {{ limit }} characters",
     *      maxMessage = "The surname cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Type(
     *     type="alpha",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $surname;

    /**
     * @ORM\OneToMany(targetEntity=Truck::class, mappedBy="mechanic")
     */
    private $trucks;

    public function __construct()
    {
        $this->trucks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Truck[]
     */
    public function getTrucks(): Collection
    {
        return $this->trucks;
    }

    public function addTruck(Truck $truck): self
    {
        if (!$this->trucks->contains($truck)) {
            $this->trucks[] = $truck;
            $truck->setMechanic($this);
        }

        return $this;
    }

    public function removeTruck(Truck $truck): self
    {
        if ($this->trucks->removeElement($truck)) {
            // set the owning side to null (unless already changed)
            if ($truck->getMechanic() === $this) {
                $truck->setMechanic(null);
            }
        }

        return $this;
    }
}
