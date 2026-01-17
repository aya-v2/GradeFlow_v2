<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class BulletinPdfService
{
    public function generateSemesterBulletin(
        string $studentName,
        string $studentId,
        string $className,
        string $schoolYear,
        string $semesterName,
        array $modules
    ): string {
        $options = new Options();
        $options->set([
            'defaultFont' => 'Helvetica',
            'isPhpEnabled' => false,
        ]);

        $dompdf = new Dompdf($options);

        // Generate HTML content
        $html = $this->generateBulletinHtml(
            $studentName,
            $studentId,
            $className,
            $schoolYear,
            $semesterName,
            $modules
        );

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    private function generateBulletinHtml(
        string $studentName,
        string $studentId,
        string $className,
        string $schoolYear,
        string $semesterName,
        array $modules
    ): string {
        $date = date('d/m/Y');
        $semesterAverage = $this->calculateSemesterAverage($modules);

        $modulesHtml = '';
        foreach ($modules as $module) {
            $result = $module['result'] ?? 'N/A';
            $final = $module['final'] !== null ? number_format($module['final'], 2) : 'N/A';
            
            $resultColor = $result === 'VALIDÉ' ? '#10b981' : '#ef4444';
            $modulesHtml .= <<<HTML
            <tr>
                <td style="border: 1px solid #ddd; padding: 10px;">
                    <strong>{$module['module']}</strong>
                </td>
                <td style="border: 1px solid #ddd; padding: 10px; text-align: center;">
                    {$module['coefficient']}
                </td>
                <td style="border: 1px solid #ddd; padding: 10px; text-align: center;">
                    {$final}
                </td>
                <td style="border: 1px solid #ddd; padding: 10px; text-align: center;">
                    <span style="color: {$resultColor}; font-weight: bold;">
                        {$result}
                    </span>
                </td>
            </tr>
            HTML;
        }

        $semesterAverageHtml = $semesterAverage !== null 
            ? '<tr style="background-color: #f3f4f6; font-weight: bold;"><td colspan="2">Moyenne du Semestre:</td><td style="text-align: center;">' . number_format($semesterAverage, 2) . '</td><td></td></tr>'
            : '';

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #1f2937;
        }
        .student-info {
            margin-bottom: 20px;
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 5px;
        }
        .student-info p {
            margin: 8px 0;
            font-size: 14px;
        }
        .student-info strong {
            display: inline-block;
            width: 120px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #1f2937;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        td {
            border: 1px solid #ddd;
            padding: 10px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Bulletin du Semestre</h1>
        <p>{$semesterName} - Année Scolaire {$schoolYear}</p>
    </div>

    <div class="student-info">
        <p><strong>Nom Étudiant:</strong> {$studentName}</p>
        <p><strong>ID Étudiant:</strong> {$studentId}</p>
        <p><strong>Classe:</strong> {$className}</p>
        <p><strong>Année Scolaire:</strong> {$schoolYear}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Module</th>
                <th style="text-align: center; width: 10%;">Coefficient</th>
                <th style="text-align: center; width: 15%;">Note Finale</th>
                <th style="text-align: center; width: 15%;">Résultat</th>
            </tr>
        </thead>
        <tbody>
            {$modulesHtml}
            {$semesterAverageHtml}
        </tbody>
    </table>

    <div class="footer">
        <p>Document généré le {$date}</p>
        <p>GradeFlow - Système de Gestion des Notes</p>
    </div>
</body>
</html>
HTML;
    }

    private function calculateSemesterAverage(array $modules): ?float
    {
        $weightedSum = 0;
        $totalCoefficient = 0;
        $hasAnyFinal = false;

        foreach ($modules as $module) {
            if ($module['final'] !== null) {
                $hasAnyFinal = true;
                $weightedSum += $module['final'] * $module['coefficient'];
                $totalCoefficient += $module['coefficient'];
            }
        }

        if ($hasAnyFinal && $totalCoefficient > 0) {
            return $weightedSum / $totalCoefficient;
        }

        return null;
    }
}
