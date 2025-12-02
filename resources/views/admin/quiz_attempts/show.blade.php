<x-app-layout>
<div class="max-w-3xl mx-auto p-6 mt-10 bg-white shadow-lg rounded-lg">

    <h1 class="text-3xl font-bold mb-6 text-center">
        Quiz Attempt Review
    </h1>

   <div class=" mb-6 p-4 border rounded-lg p-6 mt-4 mb-2">
    <h2 class="text-xl font-bold text-center mb-4">User attempt Details</h2>
     <hr class="mb-6">
    <div class="space-y-2"> 
      <p><strong>Candidate Name:</strong> {{ $attempt->user->name ?? 'N/A' }}</p>

<p><strong>Quiz:</strong> {{ $attempt->quiz->title ?? 'N/A' }}</p>

<p><strong>Score:</strong> 
    {{ $attempt->score ?? 0 }} / 
    {{ $attempt->answers->count() ?? 0 }}
</p>

<p><strong>Marks:</strong>
    @php
        $scoreMarks = ($attempt->score ?? 0) * ($attempt->quiz->marks_per_question ?? 0);
        $totalMarks = $attempt->quiz->total_marks ?? 0;
    @endphp

    {{ $scoreMarks }} of {{ $totalMarks }}
</p>

<p><strong>Percentage:</strong>
    @if($totalMarks > 0)
        {{ number_format(($scoreMarks / $totalMarks) * 100, 2) }}%
    @else
        N/A
    @endif
</p>

    </div>
</div>

    

    @foreach($attempt->answers as $index => $answer)
        @php
            $question = $answer->mcq;
            $correct = $question->correct_answer_text;
            $userAns = $answer->selected_option;
            $is_score=$answer->is_correct;

            $options = [
                'a' => $question->option_a,
                'b' => $question->option_b,
                'c' => $question->option_c,
                'd' => $question->option_d,
            ];
        @endphp

        <div class="mb-6 p-4 border rounded-lg">
            <p class="font-semibold text-lg mb-2">
                Q{{ $index + 1 }}. {{ $question->question }}
            </p>

            @foreach($options as $key => $option)
                @php
                    $isCorrect = ($option === $correct);
                    $isUser = ($option === $userAns);
                @endphp

                <div class="relative p-3 rounded mt-2 border
                    @if($isCorrect && $isUser && $is_score) bg-green-300 border-green-400
                    @elseif($isCorrect && !$is_score) bg-gray-300 border-gray-400
                    @elseif($isUser) bg-red-100 border-red-300
                    @else bg-gray-100
                    @endif
                ">
                    <span class="font-medium">{{ $option }}</span>

                    {{-- Wrong user answer --}}
                    @if($isUser && !$isCorrect)
                        <span class="absolute right-3 top-3 text-xs bg-red-300 text-white px-2 py-1 rounded">
                            ❌ User Answer
                        </span>
                    @endif

                    {{-- Correct answer but not selected --}}
                    @if($isCorrect && !$is_score)
                        <span class="absolute right-3 top-3 text-xs bg-gray-500 text-white px-2 py-1 rounded">
                            ✔ Correct Answer
                        </span>
                    @endif

                    {{-- Correct & selected --}}
                    @if($isCorrect && $isUser && $is_score)
                        <span class="absolute right-3 top-3 text-xs bg-green-600 text-white px-2 py-1 rounded">
                            ✔ User Answer
                        </span>
                    @endif
                </div>
            @endforeach

            @if($userAns === null)
                <p class="text-gray-500 mt-2">User skipped this question.</p>
            @endif
        </div>
    @endforeach

    <div class="text-center mt-6">
        <a href="{{ route('admin.attempts.index') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            Back to Attempts
        </a>
    </div>

</div>
</x-app-layout>
