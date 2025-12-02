<x-app-layout>
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6 mt-2">

                     <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold">Select MCQs for Quiz</h2>

                        <a href="{{ route('quiz.setting.listing') }}"
                        class="btn btn-light">
                            Back
                        </a>
                    </div>


            <form action="{{ route('quiz.setting.save') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                    <label class="font-semibold">Quiz Title</label>
                   <input type="text" name="quiz_title" class="border rounded w-full p-2"
       value="{{ old('quiz_title', $quiz->title ?? '') }}">
                        @error('quiz_title')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                </div>
                <div class="col-md-6  mb-3">
                    <label class="font-semibold">Total Questions</label>
                    <input type="number" name="question_count" id="question_count"
                           class="border rounded w-full p-2 bg-gray-100"
                            value="{{ old('question_count', $quiz->question_count ?? '') }}"
                            readonly>
                            @error('question_count')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-semibold">Marks Per Question</label>
                    <input type="number" name="marks_per_question" id="marks_per_question"
                           class="border rounded w-full p-2"
                           value="{{ old('marks_per_question',$quiz->marks_per_question ?? 1) }}"
                          >
                           @error('marks_per_question')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-semibold">Total Marks</label>
                    <input type="number" id="total_marks" class="border rounded w-full p-2 bg-gray-100"
                    value="{{ old('marks_per_question',$quiz->total_marks ?? '') }}"
                           readonly>
                           @error('total_marks')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-semibold">Time Per Question (in seconds)</label>
                    <input type="number" name="time_per_question" id="time_per_question"
                           class="border rounded w-full p-2"
                           value="{{ old('time_per_question',$quiz->time_per_question ?? 30) }}"
                           min="1">
                           @error('time_per_question')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                </div>
                 <div class="col-md-6 mb-3">
                    <label class="font-semibold">Total Time (Minutes)</label>
                    <input type="text" id="total_time_minutes"
                           class="border rounded w-full p-2 bg-gray-100"
                            value="{{ $quiz->quiz_time ?? '' }}"
                           readonly>
                           
                </div>
                </div>
                

               

                @foreach($mcqs as  $index => $mcq)
                <div class="border p-4 rounded mb-3">
                    <label class="flex items-start gap-3">
                        <input type="checkbox" name="mcq_ids[]" value="{{ $mcq->id }}"
                               {{ $mcq->quiz_selected ? 'checked' : '' }}>
                        <div>
                            <p class="font-semibold">Q{{ $index + 1 }}. {{ $mcq->question }}</p>

                            <ul class="ml-3 mt-2">
                                <li>A) {{ $mcq->option_a }}</li>
                                <li>B) {{ $mcq->option_b }}</li>
                                <li>C) {{ $mcq->option_c }}</li>
                                <li>D) {{ $mcq->option_d }}</li>
                            </ul>
                        </div>
                    </label>
                </div>
                @endforeach

                <div class="text-right">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Save Quiz
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
<script>
    function updateTotals() {
        let selectedCount = document.querySelectorAll('input[name="mcq_ids[]"]:checked').length;

        // update question count automatically
        document.getElementById('question_count').value = selectedCount;

        let marks = Number(document.getElementById('marks_per_question').value);
        let time = Number(document.getElementById('time_per_question').value);

        // update total marks
        document.getElementById('total_marks').value = selectedCount * marks;

        // update total time in minutes
        document.getElementById('total_time_minutes').value = ((selectedCount * time) / 60).toFixed(2);
    }

    // When selecting MCQs
    document.querySelectorAll('input[name="mcq_ids[]"]').forEach(cb => {
        cb.addEventListener('change', updateTotals);
    });

    // When typing marks/time
    document.getElementById('marks_per_question').addEventListener('input', updateTotals);
    document.getElementById('time_per_question').addEventListener('input', updateTotals);

    // Initial calculation on page load
    updateTotals();
</script>

</x-app-layout>
