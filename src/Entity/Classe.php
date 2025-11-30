<?php

namespace App\Entity;

use App\Repository\ClasseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Classe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $niveau = null;

    #[ORM\Column(length: 20)]
    private ?string $anneeScolaire = null;

    #[ORM\OneToMany(targetEntity: Etudiant::class, mappedBy: 'classe')]
    private Collection $etudiants;

    #[ORM\ManyToMany(targetEntity: Enseignant::class, inversedBy: 'classes')]
    private Collection $enseignants;

    public function __construct()
    {
        $this->etudiants = new ArrayCollection();
        $this->enseignants = new ArrayCollection();
    }

    // Getters & Setters...
    public function getId(): ?int { return $this->id; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }

    public function getNiveau(): ?string { return $this->niveau; }
    public function setNiveau(string $niveau): self { $this->niveau = $niveau; return $this; }

    public function getAnneeScolaire(): ?string { return $this->anneeScolaire; }
    public function setAnneeScolaire(string $anneeScolaire): self { $this->anneeScolaire = $anneeScolaire; return $this; }

    public function getEtudiants(): Collection { return $this->etudiants; }
    public function addEtudiant(Etudiant $etudiant): self
    {
        if (!$this->etudiants->contains($etudiant)) {
            $this->etudiants->add($etudiant);
            $etudiant->setClasse($this);
        }
        return $this;
    }

    public function getEnseignants(): Collection { return $this->enseignants; }
    public function addEnseignant(Enseignant $enseignant): self
    {
        if (!$this->enseignants->contains($enseignant)) {
            $this->enseignants->add($enseignant);
        }
        return $this;
    }
    public function removeEnseignant(Enseignant $enseignant): self
    {
        $this->enseignants->removeElement($enseignant);
        return $this;
    }

    public function __toString(): string
    {
        return $this->nom . ' (' . $this->niveau . ')';
    }
}
