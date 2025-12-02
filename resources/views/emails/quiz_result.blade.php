<h2>Your Quiz Result</h2>

<p><strong>Quiz:</strong> {{ $quiz->title }}</p>

<p>
    <strong>Score:</strong> {{ $score }} out of {{ $total }}
</p>
@php
    $percent = ($total > 0) ? round(($score / $total) * 100, 2) : 0;

    // Custom Message
    if ($score == $total) {
        $message = "Excellent! You got a perfect score!";
    } elseif ($score >= 7) {
        $message = "Good work! Keep it up!";
    } elseif ($score >= 5) {
        $message = "Not bad. You can improve with a little more practice!";
    } else {
        $message = "Don't worry. Keep trying and you will get better!";
    }
@endphp

<p>
    <strong>Percentage:</strong> {{ $percent }}%
</p>

<p>
    <strong>Message:</strong> {{ $message }}
</p>
<p>Thank you for attempting the quiz!</p>
