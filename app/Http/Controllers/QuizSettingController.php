<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mcq;
use App\Models\Quizzes as Quiz;
use App\Models\Category;



class QuizSettingController extends Controller
{
    public function index(){
        return view('quiz.setting.index');
    }

    public function create(){
         $mcqs = Mcq::all();
         $categories = Category::with('mcqs')->get();
        return view('quiz.setting.create',compact('mcqs','categories'));
    }

    //     public function edit()
    // {
    //     $mcqs = Mcq::all();
    //     $quiz=Quiz::find(1);
      
    //     return view('quiz.setting.edit', compact('mcqs','quiz'));
    // }

    public function save(Request $request)
    {
      
         $request->validate([
                'quiz_title'        => 'required|string|max:255',
                'question_count'    => 'required|integer|min:1',
                'marks_per_question'=> 'required|integer|min:1',
                'time_per_question' => 'required|integer|min:1',
                'mcq_ids'           => 'required|array|min:1', // must select at least one
                'mcq_ids.*'         => 'exists:mcqs,id',
            ], [
                'mcq_ids.required' => 'Please select at least one MCQ.',
                'mcq_ids.min'      => 'Select at least one MCQ.',
            ]);
        // Reset all
        Mcq::query()->update(['quiz_selected' => 0]);

        // Save selected
        if ($request->mcq_ids) {
            Mcq::whereIn('id', $request->mcq_ids)->update(['quiz_selected' => 1,'marks'=>$request->marks_per_question,'mcq_per_timer'=>$request->time_per_question]);
        }
        $total_marks = $request->question_count * $request->marks_per_question;
        $total_time  = $request->question_count * $request->time_per_question;
        
        Quiz::updateOrCreate(
        ['id' => 1], // You can update this logic to support multiple quizzes
        [
            'title'           => $request->quiz_title,
            'question_count'  => $request->question_count,
            'total_marks'     => $total_marks,
            'quiz_time'       => $total_time,
            'marks_per_question'=>$request->marks_per_question,
            'time_per_question'=>$request->time_per_question,
          
        ]
    );

        return redirect()->route('quiz.setting.listing')->with('success', 'Quiz updated successfully!');
    }
        // public function view()
        // {
        //     $mcqs = Mcq::where('quiz_selected', 1)->get();
        //     $quiz=Quiz::find(1);
        //     return view('quiz.setting.view', compact('mcqs','quiz'));
        // }

        // Show All Quiz

        public function listing(){
             $quizzes = Quiz::with('category')->latest()->get();

            return view('quiz.setting.listing', compact('quizzes'));
        }

        // store 

        public function store(Request $request)
{

   
    // Validate request
    $request->validate([
        'quiz_title'         => 'required|string|max:255',
        'question_count'     => 'required|integer|min:1',
        'marks_per_question' => 'required|integer|min:1',
        'time_per_question'  => 'required|integer|min:1',
        'category_ids'       => 'required|array|min:1',
        'category_ids.*'     => 'exists:categories,id',
        'mcq_ids'            => 'required|array|min:1',
        'mcq_ids.*'          => 'exists:mcqs,id',
       // 'negative_marking' => 'required|boolean',
       // 'negative_value' => 'required_if:negative_marking,1|in:0.10,0.20,0.25,0.50',
    ], [
        'mcq_ids.required' => 'Please select at least one MCQ.',
        'mcq_ids.min'      => 'Select at least one MCQ.',
        'category_ids.required' => 'Please select at least one category.',
    ]);
    if ($request->time_type == 1) {
        // Time Per Question
        $quiz_time = $request->question_count * $request->time_per_question;
    } else {
        // Time For Whole Quiz
        $quiz_time = $request->quiz_time*60;
    }
 
    // Create or update quiz
    $quiz = Quiz::updateOrCreate(
        ['id' => $request->quiz_id ?? null], // null if creating new
        [
            'title'              => $request->quiz_title,
            'question_count'     => $request->question_count,
            'marks_per_question' => $request->marks_per_question,
            'time_per_question'  => $request->time_per_question,
            'total_marks'        => $request->question_count * $request->marks_per_question,
            'quiz_time'          => $quiz_time,
            'description'        => $request->description ?? '',
            'rules'              => $request->rules ?? '',
            'time_type'          => $request->time_type ?? 1,
            'negative_marking'=>$request->negative_marking ?? 0,
            'negative_value'=>$request->negative_value ?? 0,
        ]
    );

    // Sync categories (pivot table: quiz_category)
    $quiz->categories()->sync($request->category_ids);

    // Sync MCQs with pivot data (quiz_mcq)
    $mcqData = [];
    foreach ($request->mcq_ids as $mcqId) {
        $mcqData[$mcqId] = [
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
    $quiz->mcqs()->sync($mcqData);


    return redirect()->route('quiz.setting.listing')
                     ->with('success', 'Quiz created/updated successfully!');
}


    // Edit quiz dynamically
public function edit(Quiz $quiz)
{
    $categories = Category::with('mcqs')->get();
    $mcqs = Mcq::all();

    // Pre-select categories & MCQs
    $selectedCategoryIds = $quiz->categories->pluck('id')->toArray();
    $selectedMcqIds = $quiz->mcqs->pluck('id')->toArray();

    return view('quiz.setting.create', compact(
        'categories','mcqs','quiz','selectedCategoryIds','selectedMcqIds'
    ));
}

// View quiz dynamically
public function view(Quiz $quiz)
{
    $categories = $quiz->categories;
    $mcqs = $quiz->mcqs;

    return view('quiz.setting.view', compact('quiz','categories','mcqs'));
}

// Update quiz dynamically
public function update(Request $request, Quiz $quiz)
{
    $request->validate([
        'quiz_title'         => 'required|string|max:255',
        'marks_per_question' => 'required|integer|min:1',
        'time_per_question'  => 'required|integer|min:1',
        'category_ids'       => 'required|array|min:1',
        'category_ids.*'     => 'exists:categories,id',
        'mcq_ids'            => 'required|array|min:1',
        'mcq_ids.*'          => 'exists:mcqs,id',
    ]);
    if ($request->time_type == 1) {
        // Time Per Question
        $quiz_time = count($request->mcq_ids) * $request->time_per_question;
    } else {
        // Time For Whole Quiz
        $quiz_time = $request->quiz_time*60;
    }
    $quiz->update([
        'title'                     => $request->quiz_title,
        'question_count'            => count($request->mcq_ids),
        'marks_per_question'        => $request->marks_per_question,
        'time_per_question'         => $request->time_per_question,
        'description'               =>$request->description ?? '',
        'rules'                     => $request->rules ?? '',
        'time_type'                 => $request->time_type ?? 1,
        'total_marks'               => count($request->mcq_ids) * $request->marks_per_question,
        'quiz_time'                 => $quiz_time,
        'negative_marking'          =>$request->negative_marking ?? 0,
        'negative_value'            =>$request->negative_value ?? 0,
    ]);

    $quiz->categories()->sync($request->category_ids);
    $quiz->mcqs()->sync($request->mcq_ids);

    return redirect()->route('quiz.setting.listing')->with('success','Quiz updated successfully!');
}

public function destroy($id)
{
    $quiz = Quiz::findOrFail($id);
    $quiz->delete();

    return redirect()->route('quiz.setting.listing')
                     ->with('success', 'Quiz deleted successfully!');
}
public function toggleActive(Request $request)
{
    $quizId = $request->quiz_id;
    $status = $request->status;

    // Update only the selected quiz
    $quiz = Quiz::findOrFail($quizId);
    $quiz->is_active = $status;
    $quiz->save();

    return response()->json(['success' => true]);
}



}
