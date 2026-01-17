<?php

namespace App\Service;

/**
 * Service for calculating final grades based on CC and Exam grades.
 * 
 * Final grade is calculated as: (CC + Exam) / 2
 * Both CC and Exam grades must be present for a final grade to be calculated.
 */
class GradeCalculationService
{
    /**
     * Calculate the final grade from CC (assessment) and Exam grades.
     *
     * @param float|null $ccGrade The continuous control / assessment grade
     * @param float|null $examGrade The exam grade
     * @return float|null The calculated final grade, or null if either grade is missing
     */
    public function calculateFinal(?float $ccGrade, ?float $examGrade): ?float
    {
        // Both grades must be present to calculate final grade
        if ($ccGrade === null || $examGrade === null) {
            return null;
        }

        // Final = (CC * 0.3) + (Exam * 0.7)
        return ($ccGrade * 0.3) + ($examGrade * 0.7);
    }

    /**
     * Determine if a student passed based on the final grade.
     * Pass threshold is 10.0
     *
     * @param float|null $finalGrade The final grade
     * @return string|null 'VALIDÉ' if passed, 'RATTRAPAGE' if failed, null if grade is null
     */
    public function determineResult(?float $finalGrade): ?string
    {
        if ($finalGrade === null) {
            return null;
        }

        return $finalGrade >= 10.0 ? 'VALIDÉ' : 'RATTRAPAGE';
    }
}
