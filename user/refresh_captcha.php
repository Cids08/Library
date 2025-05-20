<?php
session_start();

function generateCaptcha() {
    $words = [
        'library', 'books', 'reading', 'student', 'campus',
        'learn', 'study', 'knowledge', 'wisdom', 'education',
        'course', 'lecture', 'chapter', 'volume', 'thesis',
        'degree', 'science', 'research', 'journal', 'academic',
        'scholar', 'professor', 'university', 'college', 'school'
    ];
    $captcha_word = $words[array_rand($words)] . rand(10, 99);
    return $captcha_word;
}

$captcha = generateCaptcha();
$_SESSION['captcha_text'] = $captcha;

echo $captcha;
?>