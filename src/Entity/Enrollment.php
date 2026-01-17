<?php

namespace App\Entity;

use App\Repository\EnrollmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnrollmentRepository::class)]
class Enrollment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'enrollments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $student = null;

    #[ORM\ManyToOne(inversedBy: 'enrollments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Classe $associatedClass = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $enrollmentDate = null;

    public function __construct()
    {
        // Automatically set the date to "Today" when a new Enrollment is created
        $this->enrollmentDate = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): ?User
    {
        return $this->student;
    }

    public function setStudent(?User $student): static
    {
        $this->student = $student;

        return $this;
    }

    public function getAssociatedClass(): ?Classe
    {
        return $this->associatedClass;
    }

    public function setAssociatedClass(?Classe $associatedClass): static
    {
        $this->associatedClass = $associatedClass;

        return $this;
    }

    public function getEnrollmentDate(): ?\DateTime
    {
        return $this->enrollmentDate;
    }

    public function setEnrollmentDate(\DateTime $enrollmentDate): static
    {
        $this->enrollmentDate = $enrollmentDate;

        return $this;
    }
}
