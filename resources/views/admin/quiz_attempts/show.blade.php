<x-app-layout>
<div class="max-w-3xl mx-auto p-6 mt-10 bg-white shadow-lg rounded-lg">

    <h1 class="text-3xl font-bold mb-6 text-center">
        Quiz Attempt Review
    </h1>

   <div class="mb-6 p-6 rounded-2xl  border bg-white mt-6">

    <h2 class="text-2xl font-bold text-center mb-6 text-indigo-700">
        📝 User Attempt Details
    </h2>

    <div class="space-y-5 text-gray-800">

        <div class="flex justify-between border-b pb-2">
            <strong>👤 Candidate</strong>
            <span>{{ $attempt->user->name ?? 'N/A' }}</span>
        </div>

        <div class="flex justify-between border-b pb-2">
            <strong>📘 Quiz</strong>
            <span>{{ $attempt->quiz->title ?? 'N/A' }}</span>
        </div>

        <div class="flex justify-between border-b pb-2">
            <strong>✔️ Correct Answers</strong>
            <span class="text-green-600 font-semibold">
                {{ $attempt->score ?? 0 }} / {{ $attempt->answers->count() ?? 0 }}
            </span>
        </div>

        <div class="flex justify-between border-b pb-2">
            <strong>📝 Attended Questions</strong>
            <span>{{ $attempt->total_attended ?? 0 }}</span>
        </div>

        <div class="flex justify-between border-b pb-2">
            <strong>❌ Not Attended</strong>
            <span>{{ $attempt->not_attended ?? 0 }}</span>
        </div>

        <div class="flex justify-between border-b pb-2">
            <strong>⚠️ Wrong Answers</strong>
            <span class="text-red-600 font-semibold">
                {{ ($attempt->total_attended ?? 0) - ($attempt->score ?? 0) }}
            </span>
        </div>
        <div class="flex justify-between border-b pb-2">
            <strong>Negative marking(%)</strong>
            <span class="text-red-600 font-semibold">
                {{ $attempt->quiz->negative_value ?? 'No Negative Marking' }}
            </span>
        </div>


        {{-- MARKS CALCULATION --}}
        @php
            $quiz = $attempt->quiz;
            $marksPerQ = $quiz->marks_per_question ?? 0;

            if ($quiz->negative_marking == 1) {
                $wrong = ($attempt->total_attended ?? 0) - ($attempt->score ?? 0);
                $finalCorrect = ($attempt->score ?? 0) - ($wrong * ($quiz->negative_value ?? 0));
                $scoreMarks = max(0, $finalCorrect * $marksPerQ);
            } else {
                $scoreMarks = ($attempt->score ?? 0) * $marksPerQ;
            }

            $totalMarks = $quiz->total_marks ?? 0;
            $percentage = $totalMarks > 0 ? (($scoreMarks / $totalMarks) * 100) : 0;
        @endphp

        <div class="flex justify-between border-b pb-2">
            <strong>🏆 Marks Obtained</strong>
            <span class="font-semibold text-indigo-700">{{ $scoreMarks }} / {{ $totalMarks }}</span>
        </div>

        <div class="flex justify-between">
            <strong>📊 Percentage</strong>
            <span class="font-bold 
                @if($percentage >= 75)
                    text-green-600
                @elseif($percentage >= 50)
                    text-yellow-600
                @else
                    text-red-600
                @endif
            ">
                {{ number_format($percentage, 2) }}%
            </span>
        </div>

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
                @if(auth()->user()->role_id == 1)
                    <p class="text-gray-500 mt-2">User skipped this question.</p>
                @else
                    <p class="text-gray-500 mt-2">You skipped this question.</p>
                @endif
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
