<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/db.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

/* =========================
   STYLES
========================= */

function titleStyle($sheet, $cell)
{
    $sheet->getStyle($cell)->applyFromArray([
        'font' => ['bold' => true, 'size' => 18, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
    ]);
}

function headerStyle($sheet, $range)
{
    $sheet->getStyle($range)->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '334155']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
    ]);
}

function zebra($sheet, $start, $end, $a = 'A', $b = 'B')
{
    for ($i = $start; $i <= $end; $i++) {
        if ($i % 2 == 0) {
            $sheet->getStyle("$a$i:$b$i")->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F1F5F9']
                ]
            ]);
        }
    }
}

function fetch($pdo, $sql, $params)
{
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/* =========================
   INPUT
========================= */

try {

    $userID = $_GET["userID"] ?? "";
    $semesterID = $_GET["semesterID"] ?? "";

    if (!$userID || !$semesterID) {
        throw new Exception("Missing parameters");
    }

    $baseParams = [
        ':user_id' => $userID,
        ':semester_id' => $semesterID
    ];

    /* =========================
       EXECUTE ALL QUERIES (1–17)
    ========================= */

    $q1 = fetch($pdo, "SELECT ROUND(SUM(duration_seconds)/3600,2) total FROM fact_study_session fs JOIN dim_user du ON fs.user_sk=du.user_sk JOIN dim_semester ds ON fs.semester_sk=ds.semester_sk WHERE du.user_id=:user_id AND ds.semester_id=:semester_id AND fs.status='completed'", $baseParams)[0]['total'];

    $q2 = fetch($pdo, "SELECT ROUND(AVG((score*100.0)/total_questions),2) avg_score FROM fact_quiz fq JOIN dim_user du ON fq.user_sk=du.user_sk JOIN dim_semester ds ON fq.semester_sk=ds.semester_sk WHERE du.user_id=:user_id AND ds.semester_id=:semester_id", $baseParams)[0]['avg_score'];

    $q3 = fetch($pdo, "SELECT COUNT(*) total_tasks, SUM(status='completed') completed_tasks FROM fact_task ft JOIN dim_user du ON ft.user_sk=du.user_sk JOIN dim_semester ds ON ft.semester_sk=ds.semester_sk WHERE du.user_id=:user_id AND ds.semester_id=:semester_id", $baseParams)[0];

    $q4 = fetch($pdo, "SELECT current_streak,longest_streak FROM users WHERE user_id=:user_id", [':user_id' => $userID])[0];

    $q5 = fetch($pdo, "SELECT dd.full_date, SUM(fdp.total_minutes) minutes FROM fact_daily_progress fdp JOIN dim_date dd ON fdp.date_sk=dd.date_sk JOIN dim_user du ON fdp.user_sk=du.user_sk JOIN dim_semester ds ON fdp.semester_sk=ds.semester_sk WHERE du.user_id=:user_id AND ds.semester_id=:semester_id GROUP BY dd.full_date ORDER BY dd.full_date", $baseParams);

    $q6 = fetch($pdo, "SELECT dsub.name, ROUND(SUM(duration_seconds)/3600,2) hours FROM fact_study_session fs JOIN dim_subject dsub ON fs.subject_sk=dsub.subject_sk JOIN dim_user du ON fs.user_sk=du.user_sk JOIN dim_semester ds ON fs.semester_sk=ds.semester_sk WHERE du.user_id=:user_id AND ds.semester_id=:semester_id GROUP BY dsub.subject_sk ORDER BY hours DESC", $baseParams);

    $q7 = fetch($pdo, "SELECT ROUND(SUM(CASE WHEN fdp.total_minutes>=u.daily_goal_minutes THEN 1 ELSE 0 END)*100.0/COUNT(*),2) score FROM fact_daily_progress fdp JOIN dim_user du ON fdp.user_sk=du.user_sk JOIN users u ON du.user_id=u.user_id JOIN dim_semester ds ON fdp.semester_sk=ds.semester_sk WHERE du.user_id=:user_id AND ds.semester_id=:semester_id", $baseParams)[0]['score'];

    $q8 = fetch($pdo, "SELECT start_hour, COUNT(*) sessions FROM fact_study_session fs JOIN dim_user du ON fs.user_sk=du.user_sk JOIN dim_semester ds ON fs.semester_sk=ds.semester_sk WHERE du.user_id=:user_id AND ds.semester_id=:semester_id GROUP BY start_hour", $baseParams);

    $q9 = fetch($pdo, "SELECT ROUND(SUM(total_minutes>=u.daily_goal_minutes)*100.0/COUNT(*),2) rate FROM fact_daily_progress fdp JOIN dim_user du ON fdp.user_sk=du.user_sk JOIN users u ON du.user_id=u.user_id JOIN dim_semester ds ON fdp.semester_sk=ds.semester_sk WHERE du.user_id=:user_id AND ds.semester_id=:semester_id", $baseParams)[0]['rate'];

    $q10 = fetch($pdo, "SELECT ROUND(AVG(target_duration_minutes),2) target, ROUND(AVG(duration_seconds)/60,2) actual FROM fact_study_session fs JOIN dim_user du ON fs.user_sk=du.user_sk JOIN dim_semester ds ON fs.semester_sk=ds.semester_sk WHERE du.user_id=:user_id AND ds.semester_id=:semester_id AND fs.status='completed'", $baseParams)[0];

    $q11 = fetch($pdo, "SELECT status, COUNT(*) total FROM fact_study_session fs JOIN dim_user du ON fs.user_sk=du.user_sk JOIN dim_semester ds ON fs.semester_sk=ds.semester_sk WHERE du.user_id=:user_id AND ds.semester_id=:semester_id GROUP BY status", $baseParams);

    $q12 = fetch($pdo, "SELECT COUNT(*) completed, SUM(is_late=0) on_time FROM fact_task ft JOIN dim_user du ON ft.user_sk=du.user_sk JOIN dim_semester ds ON ft.semester_sk=ds.semester_sk WHERE du.user_id=:user_id AND ds.semester_id=:semester_id AND ft.status='completed'", $baseParams)[0];

    $q13 = fetch($pdo, "SELECT dd.full_date, ROUND(AVG(score*100.0/total_questions),2) avg_score FROM fact_quiz fq JOIN dim_date dd ON fq.date_sk=dd.date_sk JOIN dim_user du ON fq.user_sk=du.user_sk JOIN dim_semester ds ON fq.semester_sk=ds.semester_sk WHERE du.user_id=:user_id AND ds.semester_id=:semester_id GROUP BY dd.full_date", $baseParams);

    $q14 = fetch($pdo, "SELECT dsub.name, ROUND(AVG(score*100.0/total_questions),2) mastery FROM fact_quiz fq JOIN dim_subject dsub ON fq.subject_sk=dsub.subject_sk JOIN dim_user du ON fq.user_sk=du.user_sk JOIN dim_semester ds ON fq.semester_sk=ds.semester_sk WHERE du.user_id=:user_id AND ds.semester_id=:semester_id GROUP BY dsub.subject_sk", $baseParams);

    $q15 = fetch($pdo, "SELECT ROUND(s.actual_duration_seconds/60,2) study, ROUND(q.score*100.0/q.score,2) quiz FROM quizzes q JOIN sessions s ON q.session_id=s.session_id WHERE s.user_id=:user_id AND s.semester_id=:semester_id", $baseParams);

    $q16 = fetch($pdo, "SELECT dd.full_date, SUM(fx.xp_change) xp FROM fact_xp fx JOIN dim_date dd ON fx.date_sk=dd.date_sk JOIN dim_user du ON fx.user_sk=du.user_sk JOIN dim_semester ds ON fx.semester_sk=ds.semester_sk WHERE du.user_id=:user_id AND ds.semester_id=:semester_id GROUP BY dd.full_date", $baseParams);

    $q17 = fetch($pdo, "SELECT dxr.category, SUM(fx.xp_change) xp FROM fact_xp fx JOIN dim_xp_reason dxr ON fx.reason_sk=dxr.reason_sk JOIN dim_user du ON fx.user_sk=du.user_sk JOIN dim_semester ds ON fx.semester_sk=ds.semester_sk WHERE du.user_id=:user_id AND ds.semester_id=:semester_id GROUP BY dxr.category", $baseParams);

    /* =========================
       EXCEL
    ========================= */

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle("Learnify Report");

    $sheet->getColumnDimension('A')->setWidth(32);
    $sheet->getColumnDimension('B')->setWidth(22);

    /* =========================
       TITLE
    ========================= */

    $sheet->setCellValue("A1", "LEARNIFY FULL ANALYTICS DASHBOARD");
    $sheet->mergeCells("A1:D2");
    titleStyle($sheet, "A1");

    /* =========================
       KPI BLOCK (ALL METRICS)
    ========================= */

    $sheet->fromArray([
        ["Metric", "Value"],
        ["Study Hours", $q1],
        ["Quiz Average", $q2 . "%"],
        ["Tasks Completed", $q3['completed_tasks']],
        ["Total Tasks", $q3['total_tasks']],
        ["Current Streak", $q4['current_streak']],
        ["Longest Streak", $q4['longest_streak']],
        ["Consistency Score", $q7 . "%"],
        ["Goal Achievement", $q9 . "%"],
        ["Avg Target Time", $q10['target']],
        ["Avg Actual Time", $q10['actual']]
    ], NULL, "A4");

    headerStyle($sheet, "A4:B4");

    /* =========================
       STUDY TREND
    ========================= */

    $sheet->setCellValue("A16", "STUDY TREND");
    $sheet->fromArray(["Date", "Minutes"], NULL, "A17");
    headerStyle($sheet, "A17:B17");

    $row = 18;
    foreach ($q5 as $r) {
        $sheet->setCellValue("A$row", $r['full_date']);
        $sheet->setCellValue("B$row", $r['minutes']);
        $row++;
    }
    zebra($sheet, 18, $row - 1);

    /* =========================
       SUBJECTS
    ========================= */

    $start = $row + 2;
    $sheet->setCellValue("A$start", "SUBJECT BREAKDOWN");
    $sheet->fromArray(["Subject", "Hours"], NULL, "A" . ($start + 1));
    headerStyle($sheet, "A" . ($start + 1) . ":B" . ($start + 1));

    $row = $start + 2;
    foreach ($q6 as $r) {
        $sheet->setCellValue("A$row", $r['name']);
        $sheet->setCellValue("B$row", $r['hours']);
        $row++;
    }
    zebra($sheet, $start + 2, $row - 1);

    /* =========================
       XP BREAKDOWN
    ========================= */

    $startXP = $row + 2;
    $sheet->setCellValue("A$startXP", "XP BREAKDOWN");
    $sheet->fromArray(["Category", "XP"], NULL, "A" . ($startXP + 1));
    headerStyle($sheet, "A" . ($startXP + 1) . ":B" . ($startXP + 1));

    $row = $startXP + 2;
    foreach ($q17 as $r) {
        $sheet->setCellValue("A$row", $r['category']);
        $sheet->setCellValue("B$row", $r['xp']);
        $row++;
    }
    zebra($sheet, $startXP + 2, $row - 1);

    /* =========================
       OUTPUT
    ========================= */

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="learnify_full_report.xlsx"');

    $writer = new Xlsx($spreadsheet);
    $writer->save("php://output");
    exit;

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}