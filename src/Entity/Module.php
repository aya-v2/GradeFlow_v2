<?php

namespace App\Entity;

use App\Repository\ModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModuleRepository::class)]
class Module
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $coefficient = null;

    #[ORM\Column(length: 20)]
    private ?string $semester = null;

    #[ORM\ManyToOne(inversedBy: 'modules')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Filiere $filiere = null;

    /**
     * @var Collection<int, Grade>
     */
    #[ORM\OneToMany(targetEntity: Grade::class, mappedBy: 'module', orphanRemoval: true)]
    private Collection $grades;

    /**
     * @var Collection<int, TeachingAssignment>
     */
    #[ORM\OneToMany(targetEntity: TeachingAssignment::class, mappedBy: 'module', orphanRemoval: true)]
    private Collection $teachingAssignments;

    public function __construct()
    {
        $this->grades = new ArrayCollection();
        $this->teachingAssignments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCoefficient(): ?float
    {
        return $this->coefficient;
    }

    public function setCoefficient(float $coefficient): static
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    public function getSemester(): ?string
    {
        return $this->semester;
    }

    public function setSemester(string $semester): static
    {
        $this->semester = $semester;

        return $this;
    }

    public function getFiliere(): ?Filiere
    {
        return $this->filiere;
    }

    public function setFiliere(?Filiere $filiere): static
    {
        $this->filiere = $filiere;

        return $this;
    }

    /**
     * @return Collection<int, Grade>
     */
    public function getGrades(): Collection
    {
        return $this->grades;
    }

    public function addGrade(Grade $grade): static
    {
        if (!$this->grades->contains($grade)) {
            $this->grades->add($grade);
            $grade->setModule($this);
        }

        return $this;
    }

    public function removeGrade(Grade $grade): static
    {
        if ($this->grades->removeElement($grade)) {
            // set the owning side to null (unless already changed)
            if ($grade->getModule() === $this) {
                $grade->setModule(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TeachingAssignment>
     */
    public function getTeachingAssignments(): Collection
    {
        return $this->teachingAssignments;
    }

    public function addTeachingAssignment(TeachingAssignment $teachingAssignment): static
    {
        if (!$this->teachingAssignments->contains($teachingAssignment)) {
            $this->teachingAssignments->add($teachingAssignment);
            $teachingAssignment->setModule($this);
        }

        return $this;
    }

    public function removeTeachingAssignment(TeachingAssignment $teachingAssignment): static
    {
        if ($this->teachingAssignments->removeElement($teachingAssignment)) {
            // set the owning side to null (unless already changed)
            if ($teachingAssignment->getModule() === $this) {
                $teachingAssignment->setModule(null);
            }
        }

        return $this;
    }
}
