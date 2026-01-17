<?php

namespace App\Entity;


use App\Repository\ClasseRepository;
use App\Entity\User;
use App\Entity\Enrollment;
use App\Entity\TeachingAssignment;
use App\Entity\SchoolYear;
use App\Entity\Filiere;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClasseRepository::class)]
class Classe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $level = null;

    #[ORM\ManyToOne(inversedBy: 'classes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SchoolYear $schoolYear = null;

    #[ORM\ManyToOne(inversedBy: 'classes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Filiere $filiere = null;

    /**
     * @var Collection<int, Enrollment>
     */
    #[ORM\OneToMany(targetEntity: Enrollment::class, mappedBy: 'associatedClass', orphanRemoval: true)]
    private Collection $enrollments;

    /**
     * @var Collection<int, TeachingAssignment>
     */
    #[ORM\OneToMany(targetEntity: TeachingAssignment::class, mappedBy: 'associatedClass', orphanRemoval: true)]
    private Collection $teachingAssignments;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'classe')]
    private Collection $users;

    public function __construct()
    {
        $this->students = new ArrayCollection();
        $this->enrollments = new ArrayCollection();
        $this->teachingAssignments = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getSchoolYear(): ?SchoolYear
    {
        return $this->schoolYear;
    }

    public function setSchoolYear(?SchoolYear $schoolYear): static
    {
        $this->schoolYear = $schoolYear;

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
     * @return Collection<int, Enrollment>
     */
    public function getEnrollments(): Collection
    {
        return $this->enrollments;
    }

    public function addEnrollment(Enrollment $enrollment): static
    {
        if (!$this->enrollments->contains($enrollment)) {
            $this->enrollments->add($enrollment);
            $enrollment->setAssociatedClass($this);
        }

        return $this;
    }

    public function removeEnrollment(Enrollment $enrollment): static
    {
        if ($this->enrollments->removeElement($enrollment)) {
            // set the owning side to null (unless already changed)
            if ($enrollment->getAssociatedClass() === $this) {
                $enrollment->setAssociatedClass(null);
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
            $teachingAssignment->setAssociatedClass($this);
        }

        return $this;
    }

    public function removeTeachingAssignment(TeachingAssignment $teachingAssignment): static
    {
        if ($this->teachingAssignments->removeElement($teachingAssignment)) {
            // set the owning side to null (unless already changed)
            if ($teachingAssignment->getAssociatedClass() === $this) {
                $teachingAssignment->setAssociatedClass(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setClasse($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getClasse() === $this) {
                $user->setClasse(null);
            }
        }

        return $this;
    }

    #[ORM\OneToMany(mappedBy: 'classe', targetEntity: User::class)]
    private Collection $students;

    /**
     * @return Collection<int, User>
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(User $student): static
    {
        if (!$this->students->contains($student)) {
            $this->students->add($student);
            $student->setClasse($this);
        }

        return $this;
    }

    public function removeStudent(User $student): static
    {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getClasse() === $this) {
                $student->setClasse(null);
            }
        }

        return $this;
    }
}
