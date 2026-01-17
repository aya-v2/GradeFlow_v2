<?php

namespace App\Controller\Admin;

use App\Entity\SchoolYear;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SchoolYearCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SchoolYear::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof SchoolYear) {
            return;
        }

        if ($entityInstance->isCurrent()) {
            $this->validateCurrentYear($entityManager, $entityInstance);
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof SchoolYear) {
            return;
        }

        if ($entityInstance->isCurrent()) {
            $this->validateCurrentYear($entityManager, $entityInstance);
        }

        parent::updateEntity($entityManager, $entityInstance);
    }

    private function validateCurrentYear(EntityManagerInterface $entityManager, SchoolYear $newYear): void
    {
        // Find the currently active year (that is NOT the one we are saving, in case of update)
        $repository = $entityManager->getRepository(SchoolYear::class);
        
        // If we are updating, exclude the current entity from the check to avoid confusion, 
        // though logic is: "Are there ANY OTHER years that are current?"
        // Actually, the requirement is "until every teacher publishes all CURRENT YEAR grades".
        // This implies validating the *previous* year (the one that is currently active) is done.
        
        $currentActiveYear = $repository->findOneBy(['isCurrent' => true]);

        // If there is no active year, or if the active year is the one we are editing (and it was already active),
        // then maybe we don't need to block? 
        // But if we are switching from Year A (Current) to Year B (New Current), we must validate Year A.
        
        if (!$currentActiveYear || $currentActiveYear === $newYear) {
            // If there's no other current year, we are fine, or we are just updating the existing current year.
            return;
        }

        // Validate Year A (currentActiveYear)
        foreach ($currentActiveYear->getClasses() as $class) {
            $students = $class->getStudents();
            
            // Get modules via TeachingAssignment
            $modules = [];
            foreach ($class->getTeachingAssignments() as $assignment) {
                $modules[] = $assignment->getModule();
            }

            if (empty($modules)) {
                continue;
            }

            foreach ($students as $student) {
                // Ensure student has ROLE_STUDENT? Assuming yes if in Class.
                
                foreach ($modules as $module) {
                    $grade = $entityManager->getRepository(\App\Entity\Grade::class)->findOneBy([
                        'student' => $student,
                        'module' => $module
                    ]);

                    if (!$grade || !$grade->isFinalized() || $grade->getCcGrade() === null || $grade->getExamGrade() === null) {
                        throw new \RuntimeException(sprintf(
                            "Impossible de définir la nouvelle année scolaire comme active. Les notes pour l'étudiant %s (Classe: %s, Module: %s) ne sont pas complètes ou finalisées pour l'année en cours (%s).",
                            $student->getUserIdentifier(),
                            $class->getName(),
                            $module->getName(),
                            $currentActiveYear->getName()
                        ));
                    }
                }
            }
        }
        
        // If validation passes, we should probably unset isCurrent on the old year?
        // The prompt says "can't start a new year until...".
        // Assuming the system handles toggling off the old year, or we should do it manually.
        // For now, I strictly verify the condition.
        // Update: EasyAdmin/App logic might handle unique constraint on isCurrent via listeners, but safe to untoggle here?
        // I will just perform validation as requested.
        
        // Deactivate the old year
        $currentActiveYear->setIsCurrent(false);
        // We don't need to flush here, the entityManager will flush changes at end of request? 
        // persistEntity just persists the new one. updateEntity flushes.
        // Safest to explicitly persist the modification to the old year?
        // But persistEntity only talks about $entityInstance.
        // Let's rely on validation blocking the flow first.
    }
}
