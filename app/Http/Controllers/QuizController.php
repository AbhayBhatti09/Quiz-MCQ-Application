<?php

namespace App\Http\Controllers;
use App\Models\Quizzes as Quiz;
use App\Models\Mcq;
use session;
use App\Mail\QuizResultMail;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use Illuminate\Support\Facades\Mail;


use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index(){
        //  $quizs = Quiz::where('is_active',true)->first(); 
        $quizs=Quiz::where('is_active',true)->latest()->get();
         
    return view('quiz.index', compact('quizs'));
    }

  public function show($quiz_id = null)
{
  
    // $quiz = Quiz::where()first();


    // $mcqs = Mcq::where('quiz_selected',true)->inRandomOrder()->get();
     $quiz = Quiz::with('categories', 'mcqs')
                ->where('is_active', true)
                ->when($quiz_id, fn($query) => $query->where('id', $quiz_id))
                ->firstOrFail();
    
    // Get all MCQs linked to this quiz
    $mcqs = $quiz->mcqs()->inRandomOrder()->get();
   
    // Save IDs in session
    session(['quiz_mcq_ids' => $mcqs->pluck('id')->toArray()]);

    // Shuffle options
    $mcqs = $mcqs->map(function($mcq) {
        $options = [
            'A' => $mcq->option_a,
            'B' => $mcq->option_b,
            'C' => $mcq->option_c,
            'D' => $mcq->option_d,
        ];

        $mcq->shuffled_options = collect($options)->shuffle()->toArray();

        return $mcq;
    });

    return view('quiz.show', compact('quiz', 'mcqs'));
}



 public function submit(Request $request, Quiz $quiz)
{
    $answers = $request->input('answers', []);
    $ids = array_keys($answers);

    $mcqs = Mcq::whereIn('id', $ids)
        ->orderByRaw('FIELD(id, ' . implode(',', $ids) . ')')
        ->get();

    // count 
    $totalQuestions = $quiz->mcqs()->count();
    $totalattended = count(array_filter($answers));
    $notAttended = $totalQuestions - $totalattended;

    $score = 0;
    $review = [];

    // Create a new quiz attempt
    $attempt = QuizAttempt::create([
        'user_id' => auth()->id(),
        'quiz_id' => $quiz->id,
        'total_questions' => $totalQuestions,
        'total_attended' => $totalattended,
        'not_attended' => $notAttended,
    ]);
    
    foreach ($mcqs as $mcq) {
        $userAnswer = $answers[$mcq->id] ?? null;

        $isCorrect = false;
        if ($userAnswer === $mcq->correct_answer_text) {
            $score++;
            $isCorrect = true;
        }

        $review[] = [
            'question' => $mcq->question,
            'options' => [
                'a' => $mcq->option_a,
                'b' => $mcq->option_b,
                'c' => $mcq->option_c,
                'd' => $mcq->option_d,
            ],
            'correct_answer' => $mcq->correct_answer_text,
            'user_answer' => $userAnswer,
            'is_correct' => $isCorrect,
        ];
      //  dd($userAnswer);
        // Save each answer
        QuizAttemptAnswer::create([
            'attempt_id' => $attempt->id,
            'mcq_id' => $mcq->id,
            'selected_option' => $userAnswer,
            'is_correct' => $isCorrect,
        ]);
    }
  
    // Update attempt score
    $attempt->update(['score' => $score]);

    // Save review in session
    session(['review_data' => $review]);
    if(!empty($quiz)){
        $totalMarks=$quiz->total_marks;
        $scoremarks= $quiz->marks_per_question*$score;
        
    }

    // Send email
    $this->sendQuizEmail(auth()->user(), $quiz, $score, count($mcqs));

    // return view('quiz.score', [
    //     'quiz' => $quiz,
    //     'score' => $score,
    //     'total' => count($mcqs),
    //     'totalMarks'=>$totalMarks ?? 0,
    //     'scoremarks'=>$scoremarks ?? 0,
    // ]);
     return redirect()->route('quiz.score', ['quiz' => $quiz->id]);
}

 public function review()
{
    // Get review data from session
    $review = session('review_data', []);

    if (empty($review)) {
        return redirect()->route('quiz.index')->with('error', 'No review data found.');
    }

    return view('quiz.review', compact('review'));
}

private  function sendQuizEmail($user, $quiz, $score, $total){
        try {
        Mail::to($user->email)->queue(
            new QuizResultMail($quiz, $score, $total)
        );
    } catch (\Exception $e) {
        \Log::error('Quiz result email failed: '.$e->getMessage());
    }
    }

    public function showScore(Quiz $quiz)
{
    $review = session('review_data', []);
    $attempt = QuizAttempt::where('user_id', auth()->id())
        ->where('quiz_id', $quiz->id)
        ->latest()
        ->first();

    if (!$attempt) {
        return redirect()->route('quiz.index')->with('error', 'No quiz attempt found.');
    }

    $totalMarks = $quiz->total_marks ?? 0;
    // If negative marking
    if($quiz->negative_marking==1){
        // negative marks
        $WrongAnswer= ($attempt->total_attended)-($attempt->score); // wrong answer find
        $totaltrueAnswer=($attempt->score)-( $WrongAnswer* $quiz->negative_value); //

        $scoremarks=($quiz->marks_per_question ?? 0) * ($totaltrueAnswer ?? 0);
    }else{
        $scoremarks = ($quiz->marks_per_question ?? 0) * ($attempt->score ?? 0);
    }

    return view('quiz.score', [
        'quiz' => $quiz,
        'score' => $attempt->score ?? 0,
        'total' => $attempt->quiz->mcqs->count() ?? 0,
        'totalMarks' => $totalMarks ?? 0,
        'scoremarks' => $scoremarks ?? 0,
        'review' => $review,
        'total_attended' => $attempt->total_attended ?? 0,
        'not_attended' => $attempt->not_attended ?? 0,
    ]);
}

public function quizDetails($id)
{
    $quiz = Quiz::findOrFail($id);

    return response()->json([
        'title' => $quiz->title,
        'total_questions' => $quiz->question_count,
        'total_marks' => $quiz->total_marks,
        'time_per_question' => $quiz->time_per_question,
    ]);
}




}
