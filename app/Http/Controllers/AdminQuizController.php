<?php

namespace App\Http\Controllers;

use App\Models\QuizAttempt;

class AdminQuizController extends Controller
{
    // Show all attempts
    public function index()
    {
        if (auth()->user()->role_id == 1) {
        // admin can see all
        $attempts = QuizAttempt::whereHas('user')->with('user','quiz')->latest()->get();
        
    } else {
        // user sees only their data
        $attempts = QuizAttempt::with('quiz')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
    }
       // ======== Add Score Calculation to Each Attempt ========= //
    foreach ($attempts as $attempt) {
        $quiz = $attempt->quiz;
        $marksPerQ = $quiz->marks_per_question ?? 0;
        if (!$quiz) {
                $attempt->scoremarks = 0; // or skip
                continue;
            }


        if ($quiz->negative_marking == 1) {
            // Wrong answers
            $wrong = ($attempt->total_attended ?? 0) - ($attempt->score ?? 0);

            // Apply negative value
            $finalCorrect = ($attempt->score ?? 0) - ($wrong * ($quiz->negative_value ?? 0));

            // Score marks (never negative)
            $attempt->scoremarks = max(0, $finalCorrect * $marksPerQ);

        } else {
            // No negative marking
            $attempt->scoremarks = ($attempt->score ?? 0) * $marksPerQ;
        }
    }

        return view('admin.quiz_attempts.index', compact('attempts'));
    }

    // Show attempt details
    public function show($id)
    {
        $attempt = QuizAttempt::with(['user', 'quiz', 'answers.mcq'])
            ->findOrFail($id);

          if (auth()->user()->role_id != 1 && $attempt->user_id != auth()->id()) {
                abort(403, 'Unauthorized access');
            }

        return view('admin.quiz_attempts.show', compact('attempt'));
    }

    public function destroy($id) {
    $attempt = QuizAttempt::findOrFail($id);
    $attempt->delete();
    return response()->json(['success' => true]);
}

}

