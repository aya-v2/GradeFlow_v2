<?php

namespace App\Entity;

use App\Repository\TeachingAssignmentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeachingAssignmentRepository::class)]
class TeachingAssignment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'teachingAssignments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $teacher = null;

    #[ORM\ManyToOne(inversedBy: 'teachingAssignments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Classe $associatedClass = null;

    #[ORM\ManyToOne(inversedBy: 'teachingAssignments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Module $module = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeacher(): ?User
    {
        return $this->teacher;
    }

    public function setTeacher(?User $teacher): static
    {
        $this->teacher = $teacher;

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

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): static
    {
        $this->module = $module;

        return $this;
    }
}
