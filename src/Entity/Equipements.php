<?php

namespace App\Entity;

use App\Repository\EquipementsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipementsRepository::class)]
class Equipements
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\ManyToMany(targetEntity: SallesTravail::class, inversedBy: 'equipements')]
    private Collection $sallesTravail;

    public function __construct()
    {
        $this->sallesTravail = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection<int, SallesTravail>
     */
    public function getSallesTravail(): Collection
    {
        return $this->sallesTravail;
    }

    public function addSallesTravail(SallesTravail $sallesTravail): static
    {
        if (!$this->sallesTravail->contains($sallesTravail)) {
            $this->sallesTravail->add($sallesTravail);
        }

        return $this;
    }

    public function removeSallesTravail(SallesTravail $sallesTravail): static
    {
        $this->sallesTravail->removeElement($sallesTravail);

        return $this;
    }
}
