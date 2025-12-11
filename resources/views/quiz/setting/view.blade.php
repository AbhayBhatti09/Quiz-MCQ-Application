<x-app-layout>
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">

            <div class="flex justify-between items-center mb-4">
               <h2 class="text-xl font-bold mb-3"> Quiz Summary</h2>

                <a href="{{ route('quiz.setting.listing') }}" class="btn btn-light">
                    Back
                </a>
            </div>

        @if($quiz)
<div class="mb-6">
   

    <table class="w-full border rounded-lg overflow-hidden">
        <tbody>
            <tr class="border-b bg-gray-50">
                <td class="p-3 font-semibold">Title</td>
                <td class="p-3">{{ $quiz->title }}</td>
            </tr>
            <tr class="border-b">
                <td class="p-3 font-semibold">Total Questions</td>
                <td class="p-3">{{ $quiz->question_count }}</td>
            </tr>
            <tr class="border-b bg-gray-50">
                <td class="p-3 font-semibold">Total Marks</td>
                <td class="p-3">{{ $quiz->total_marks }}</td>
            </tr>
             <tr class="border-b">
                <td class="p-3 font-semibold">Mark per Question</td>
                <td class="p-3">{{ $quiz->marks_per_question }}</td>
            </tr>
           
           @if($quiz->time_type == 1)
            <tr class="border-b">
                <td class="p-3 font-semibold">Time per Question</td>
                <td class="p-3">{{ $quiz->time_per_question }} sec</td>
            </tr>
            @endif
            <tr class="bg-gray-50">
                <td class="p-3 font-semibold">Total Time</td>
                <td class="p-3">{{ ($quiz->quiz_time)/60 }} minutes</td>
            </tr>
        </tbody>
    </table>
</div>
@endif



            {{-- MCQ LIST --}}
            @forelse($mcqs as $index => $mcq)
                <div class="border p-4 rounded mb-3">
                    <p class="font-semibold">Q{{ $index + 1 }}. {{ $mcq->question }}</p>
                    <ul class="ml-3 mt-2">
                        <li>A) {{ $mcq->option_a }}</li>
                        <li>B) {{ $mcq->option_b }}</li>
                        <li>C) {{ $mcq->option_c }}</li>
                        <li>D) {{ $mcq->option_d }}</li>
                    </ul>
                </div>
            @empty
                <p>No MCQs selected for quiz.</p>
            @endforelse

          

        </div>
    </div>
</div>
</x-app-layout>
