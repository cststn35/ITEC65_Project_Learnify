<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/runQuery.php';


$apiKey = 'AIzaSyCURFdGVkMxDCbw6MXxGG3xwZqZs9C42l8'; //free key lang naman, saka kona asikasuhin ung .env

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

        // safer shell command
        $safePath = escapeshellarg($file["tmp_name"]);

        $text = shell_exec(
            "pdftotext $safePath -"
        );
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

    //validate extracted text
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

    //upload questions into database
    $result = runQuery($pdo, "
    INSERT INTO quizzes (session_id)
    VALUES (:session_id);
", [
        "session_id" => 1,
    ]);

    $quizID = $pdo->lastInsertId();

    $sql = "INSERT INTO questions (quiz_id, question, choice_a, choice_b, choice_c, choice_d, correct_answer)
VALUES (:quiz_id, :question, :choice_a, :choice_b, :choice_c, :choice_d, :correct_answer)";

    foreach ($quiz as $q) {
        if (
            !isset($q["question"]) ||
            !isset($q["choices"]) ||
            count($q["choices"]) < 4 ||
            !isset($q["answer"])
        ) {
            continue;
        }

        $params = [
            "quiz_id" => $quizID,
            "question" => $q["question"],
            "choice_a" => $q["choices"][0],
            "choice_b" => $q["choices"][1],
            "choice_c" => $q["choices"][2],
            "choice_d" => $q["choices"][3],
            "correct_answer" => $q["answer"]
        ];

        $result = runQuery($pdo, $sql, $params);

        if (!($result->rowCount() > 0)) {
            echo json_encode([
                "success" => false,
            ]);
            exit;
        }
    }

    //sucess response
    echo json_encode([
        "success" => true,
        "quiz" => $quiz
    ]);
}


