<?php
session_start();

if (isset($_SESSION['start_study'])) {
    unset($_SESSION['start_study']);
    echo json_encode([
        'status' => "Start study unset as there are no start study"
    ]);
    exit;
}
echo json_encode([
    'status' => "No start study yet"
]);

