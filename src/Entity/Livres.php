<?php

namespace App\Entity;

use App\Repository\LivresRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivresRepository::class)]
class Livres
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $anneePublication = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $resume = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column]
    private ?bool $disponibilite = null;

    #[ORM\OneToMany(targetEntity: Commentaires::class, mappedBy: 'livres', orphanRemoval: true)]
    private Collection $commentaires;

    #[ORM\ManyToOne(inversedBy: 'livres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EtatsLivres $etatsLivres = null;

    #[ORM\ManyToMany(targetEntity: Auteurs::class, mappedBy: 'livres')]
    private Collection $auteurs;

    #[ORM\OneToMany(targetEntity: Notes::class, mappedBy: 'livres', orphanRemoval: true)]
    private Collection $notes;

    #[ORM\OneToMany(targetEntity: Emprunts::class, mappedBy: 'livres', orphanRemoval: true)]
    private Collection $emprunts;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->auteurs = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->emprunts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAnneePublication(): ?int
    {
        return $this->anneePublication;
    }

    public function setAnneePublication(int $anneePublication): static
    {
        $this->anneePublication = $anneePublication;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(string $resume): static
    {
        $this->resume = $resume;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function isDisponibilite(): ?bool
    {
        return $this->disponibilite;
    }

    public function setDisponibilite(bool $disponibilite): static
    {
        $this->disponibilite = $disponibilite;

        return $this;
    }

    /**
     * @return Collection<int, Commentaires>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaires $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setLivres($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getLivres() === $this) {
                $commentaire->setLivres(null);
            }
        }

        return $this;
    }

    public function getEtatsLivres(): ?EtatsLivres
    {
        return $this->etatsLivres;
    }

    public function setEtatsLivres(?EtatsLivres $etatsLivres): static
    {
        $this->etatsLivres = $etatsLivres;

        return $this;
    }

    /**
     * @return Collection<int, Auteurs>
     */
    public function getAuteurs(): Collection
    {
        return $this->auteurs;
    }

    public function addAuteur(Auteurs $auteur): static
    {
        if (!$this->auteurs->contains($auteur)) {
            $this->auteurs->add($auteur);
            $auteur->addLivre($this);
        }

        return $this;
    }

    public function removeAuteur(Auteurs $auteur): static
    {
        if ($this->auteurs->removeElement($auteur)) {
            $auteur->removeLivre($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Notes>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Notes $note): static
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->setLivres($this);
        }

        return $this;
    }

    public function removeNote(Notes $note): static
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getLivres() === $this) {
                $note->setLivres(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Emprunts>
     */
    public function getEmprunts(): Collection
    {
        return $this->emprunts;
    }

    public function addEmprunt(Emprunts $emprunt): static
    {
        if (!$this->emprunts->contains($emprunt)) {
            $this->emprunts->add($emprunt);
            $emprunt->setLivres($this);
        }

        return $this;
    }

    public function removeEmprunt(Emprunts $emprunt): static
    {
        if ($this->emprunts->removeElement($emprunt)) {
            // set the owning side to null (unless already changed)
            if ($emprunt->getLivres() === $this) {
                $emprunt->setLivres(null);
            }
        }

        return $this;
    }
}
