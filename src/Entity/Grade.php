<?php

namespace App\Entity;

use App\Repository\GradeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GradeRepository::class)]
class Grade
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'grades')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $student = null;

    #[ORM\ManyToOne(inversedBy: 'grades')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Module $module = null;

    #[ORM\Column(nullable: true)]
    private ?float $ccGrade = null;

    #[ORM\Column(nullable: true)]
    private ?float $examGrade = null;

    #[ORM\Column]
    private ?int $isPublished = null;

    public const STATE_DRAFT = 0;
    public const STATE_CC_APPROVED = 1;
    public const STATE_FINALIZED = 2;

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

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): static
    {
        $this->module = $module;

        return $this;
    }

    public function getCcGrade(): ?float
    {
        return $this->ccGrade;
    }

    public function setCcGrade(?float $ccGrade): static
    {
        $this->ccGrade = $ccGrade;

        return $this;
    }

    public function getExamGrade(): ?float
    {
        return $this->examGrade;
    }

    public function setExamGrade(?float $examGrade): static
    {
        $this->examGrade = $examGrade;

        return $this;
    }

    public function getIsPublished(): ?int
    {
        return $this->isPublished;
    }

    public function setIsPublished(int $isPublished): static
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function isDraft(): bool
    {
        return $this->isPublished === null || $this->isPublished === self::STATE_DRAFT;
    }

    public function isCcApproved(): bool
    {
        return $this->isPublished === self::STATE_CC_APPROVED;
    }

    public function isFinalized(): bool
    {
        return $this->isPublished === self::STATE_FINALIZED;
    }
}
