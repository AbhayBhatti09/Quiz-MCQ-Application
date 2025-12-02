<x-app-layout>

<div class="max-w-4xl mx-auto py-10">

    <h1 class="text-3xl font-bold mb-6">Quiz</h1>

    @if($quizs->isEmpty())

        <!-- Empty State Card -->
        <div class="bg-gradient-to-br from-indigo-50 to-blue-50 shadow-lg border rounded-xl p-10 text-center">

            <h2 class="text-2xl font-bold text-gray-800 mb-2">
                No Quiz Available
            </h2>

            <p class="text-gray-600 text-lg">
                Quizzes added by admin will appear here.
            </p>

            

        </div>

    @else

        @foreach($quizs as $quiz)
            <div class="bg-white shadow-xl rounded-xl p-6 mb-6 border hover:shadow-2xl transition">

                <!-- Title -->
                <h2 class="text-2xl font-semibold mb-2">
                    {{ $quiz->title ?? 'Untitled Quiz' }}
                </h2>

                <!-- Description -->
                <p class="text-gray-600 mb-2">
                    <strong>Description </strong>
                    {!! $quiz->description ?? 'Quiz rules and details will be shown here.' !!}
                </p>

              <!-- Rules -->
                <p class="text-red-600 mb-2 mt-2">
                       <strong>Rules </strong>
                    {!! $quiz->rules ?? '1. No negative marking.<br>2. Avoid refreshing — it will restart the quiz.<br>3. Attempt all questions carefully.<br>4. Follow instructions on each question.<br>5. Submit before time ends.' !!}
                </p>

                <!-- Info Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 mt-2">

                    <!-- Question Count -->
                    <div class="bg-gray-100 p-4 rounded-lg text-center">
                        <h3 class="text-lg font-semibold">Questions</h3>
                        <p class="text-xl font-bold">
                            {{ $quiz->question_count ?? '—' }}
                        </p>
                    </div>

                    <!-- Total Marks -->
                    <div class="bg-gray-100 p-4 rounded-lg text-center">
                        <h3 class="text-lg font-semibold">Total Marks</h3>
                        <p class="text-xl font-bold">
                            {{ $quiz->total_marks ?? '—' }}
                        </p>
                    </div>

                    <!-- Time Duration -->
                    <div class="bg-gray-100 p-4 rounded-lg text-center">
                        <h3 class="text-lg font-semibold">Time</h3>
                        <p class="text-xl font-bold">
                            {{ isset($quiz->quiz_time) ? $quiz->quiz_time / 60 : '—' }} min
                        </p>
                    </div>

                </div>

                <!-- Start Button -->
                <a href="{{ route('quiz.show', $quiz->id) }}"
                   class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                    Start Quiz
                </a>

            </div>
        @endforeach

    @endif

</div>

</x-app-layout>
