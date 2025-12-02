<?php

namespace App\Http\Controllers;

use App\Models\Mcq;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\McqRequest;


class McqController extends Controller
{
    public function index(Request $request)
    {
    //     $search = $request->input('search');
       
    //     // return view('mcq.index', compact('mcqs'));
    //    $mcqs = MCQ::when($search, function ($query) use ($search) {
    //     $query->where(function ($q) use ($search) {
    //         $q->where('question', 'like', "%{$search}%")
    //           ->orWhere('option_a', 'like', "%{$search}%")
    //           ->orWhere('option_b', 'like', "%{$search}%")
    //           ->orWhere('option_c', 'like', "%{$search}%")
    //           ->orWhere('option_d', 'like', "%{$search}%");
    //     });
    // })
    // ->orderBy('id', 'DESC')
    // ->paginate(5) // 5 items per page
    // ->withQueryString(); // keeps search value during pagination

    // return view('mcq.index', compact('mcqs', 'search'));
     $mcqs = MCQ::with('category')->latest()->get();

    // Add full answer text for display
    $mcqs->map(function($mcq) {
        $label = strtoupper($mcq->correct_answer);
        $text  = $mcq->{'option_'.$mcq->correct_answer} ?? '';
        $mcq->correct_answer_full = "({$label}) - {$text}";
        return $mcq;
    });

    return view('mcq.index', compact('mcqs'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('mcq.create',compact('categories'));
    }

    public function store(McqRequest $request)
    {
      //  dd($request->all());
        $request->validate([
                //'question' => 'required',
                'option_a' => 'required',
                'option_b' => 'required',
                'option_c' => 'required',
                'option_d' => 'required',
                'correct_answer' => 'required|in:a,b,c,d',
                'category_id' => 'required|exists:categories,id',
            ],[
                'category_id.required' => 'Please select a category.',
                'category_id.exists'   => 'Selected category does not exist.',
            ]);
        $data=$request->all();
       
        $letter = $data['correct_answer'];
        $data['correct_answer_text'] = $data["option_{$letter}"];
        Mcq::create($data);

        return redirect()->route('mcq.index')->with('success', 'MCQ Created Successfully!');
    }

    public function edit(Mcq $mcq)
    {
        $categories = Category::all();
        return view('mcq.edit', compact('mcq','categories'));
    }

    public function update(McqRequest  $request, Mcq $mcq)
    {
        $request->validate([
            'question' => 'required',
            'option_a' => 'required',
            'option_b' => 'required',
            'option_c' => 'required',
            'option_d' => 'required',
            'correct_answer' => 'required|in:a,b,c,d',
        ]);
        $data=$request->all();
        $letter = $data['correct_answer'];
        $data['correct_answer_text'] = $data["option_{$letter}"];
        $mcq->update($data);

        return redirect()->route('mcq.index')->with('success', 'MCQ Updated!');
        
    }
    //delete
        public function destroy(Mcq $mcq)
        {
            $mcq->delete();

            return redirect()->route('mcq.index')
                ->with('success', 'MCQ deleted successfully!');
        }
}
