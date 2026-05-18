<?php
session_start();
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/runQuery.php';
require_once __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();
$dotenv->required('GEMINI_API_KEY');

use Smalot\PdfParser\Parser;

$apiKey = $_ENV['GEMINI_API_KEY'];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $count = $_POST["count"] ?? "five";
    //validate upload
    if (!isset($_FILES["file"])) {
        http_response_code(400);

        echo json_encode([
            "success" => false,
            "error" => "No file uploaded"
        ]);
        exit;
    }

    $file = $_FILES["file"];

    if ($file["error"] !== UPLOAD_ERR_OK) {
        echo json_encode([
            "success" => false,
            "error" => "Upload failed"
        ]);
        exit;
    }

    //file extension validation
    $allowedExtensions = ["txt", "pdf", "docx"];

    $ext = strtolower(
        pathinfo($file["name"], PATHINFO_EXTENSION)
    );

    if (!in_array($ext, $allowedExtensions)) {
        echo json_encode([
            "success" => false,
            "error" => "Only TXT, PDF, and DOCX allowed"
        ]);
        exit;
    }

    $text = "";

    //extraction via text
    if ($ext === "txt") {
        $text = file_get_contents($file["tmp_name"]);
    }

    //extraction via pdf
    if ($ext === "pdf") {
        try {
            $parser = new Parser();

            // parse PDF directly from temp file
            $pdf = $parser->parseFile($file["tmp_name"]);

            // extract text
            $text = $pdf->getText();

            // basic cleanup
            $text = preg_replace('/\s+/', ' ', $text);
            $text = trim($text);

        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "error" => "Could not extract text"
            ]);
        }
    }

    //extraction via docx
    if ($ext === "docx") {

        $zip = new ZipArchive();

        if ($zip->open($file["tmp_name"]) === TRUE) {

            $xml = $zip->getFromName(
                "word/document.xml"
            );

            $zip->close();

            if ($xml) {
                $text = strip_tags($xml);
            }
        }
    }

    //validate extracted text (extra measure)
    $text = trim($text);

    if (empty($text)) {
        echo json_encode([
            "success" => false,
            "error" => "Could not extract text"
        ]);
        exit;
    }

    //limit text size to prevent token abuse
    $text = substr($text, 0, 5000);

    //prompt for Gemini AI
    $prompt = "
    You are a JSON generator.

    You MUST output ONLY valid JSON.

    ABSOLUTE RULES:
    - Output must start with [ and end with ]
    - No markdown
    - No explanations
    - No extra text before or after JSON
    - No code blocks
    - No trailing commas
    - Keep each question concise.
    - Do not exceed 1 sentence per question.
    - Keep choices short (1–6 words each where possible).

    TASK:
    Generate exactly $count multiple-choice questions from the content.

    DISTRIBUTION RULES (approximate but must be followed as closely as possible):
    - 40% easy
    - 40% medium
    - 20% hard

    TYPE RULES:
    - At least 20% situational questions
    - At least 20% fill-in-the-blank MCQ questions
    - Remaining concept-based MCQ questions

    MCQ RULES:
    - Exactly 4 choices per question
    - Only one correct answer
    - Answer must exactly match one choice

    Format (STRICTLY DO NOT CHANGE):

    [
    {
        \"question\": \"Question text\",
        \"choices\": [
        \"Choice A\",
        \"Choice B\",
        \"Choice C\",
        \"Choice D\"
        ],
        \"answer\": \"Correct Answer\"
    }
    ]

    Content:

    $text
    ";

    //payload for gemini
    $payload = [
        "contents" => [
            [
                "parts" => [
                    [
                        "text" => $prompt
                    ]
                ]
            ]
        ],

        // More deterministic output
        "generationConfig" => [
            "temperature" => 0.3,
            "topP" => 0.8,
            "maxOutputTokens" => 8096
        ]
    ];

    $url =
        "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=$apiKey";

    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json"
        ],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 60
    ]);

    $response = curl_exec($ch);

    //check for curl error
    if (curl_errno($ch)) {

        echo json_encode([
            "success" => false,
            "error" => curl_error($ch)
        ]);

        curl_close($ch);
        exit;
    }

    curl_close($ch);


    //decoding gemini response into associative array
    $result = json_decode($response, true);

    $output =
        $result["candidates"][0]["content"]["parts"][0]["text"]
        ?? "";

    //cleaning ai markdown if there's any
    $output = trim($output);

    $output = preg_replace('/```json|```/', '', $output);

    if (preg_match('/\[[\s\S]*\]/', $output, $matches)) {
        $output = $matches[0];
    }

    //validating json
    $quiz = json_decode($output, true); //this is where the quiz is generated

    if (json_last_error() !== JSON_ERROR_NONE) {

        echo json_encode([
            "success" => false,
            "error" => "Invalid JSON from AI",
            "raw_response" => $output
        ]);

        exit;
    }


    unset($_SESSION["quizzes"]); //to avoid appending old questions
    //store questions first in the php session, will save it to db once the study session is created
    foreach ($quiz as $q) {
        if (
            !isset($q["question"]) ||
            !isset($q["choices"]) ||
            count($q["choices"]) < 4 ||
            !isset($q["answer"])
        ) {
            continue;
        }

        $_SESSION["quizzes"][] = [
            "question" => $q["question"],
            "choice_a" => $q["choices"][0],
            "choice_b" => $q["choices"][1],
            "choice_c" => $q["choices"][2],
            "choice_d" => $q["choices"][3],
            "correct_answer" => $q["answer"]
        ];
    }

    //success response
    echo json_encode([
        "success" => true,
        "quiz" => $_SESSION["quizzes"]
    ]);
}


