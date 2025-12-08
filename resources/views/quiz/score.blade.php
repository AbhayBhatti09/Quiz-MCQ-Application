<x-app-layout>
    <div class="max-w-3xl mx-auto mt-6 p-4 sm:p-6 bg-white shadow-lg rounded-xl">

        <h1 class="text-2xl sm:text-3xl font-bold mb-6 text-center">Quiz Result</h1>

        <div class="bg-white shadow-md rounded-xl p-4 sm:p-6 text-center">

            <!-- Quiz Title -->
            <p class="text-xl sm:text-2xl font-bold text-gray-800 mb-4">
                {{ $quiz->title }}
            </p>

            <!-- Score Section -->
            <div class="bg-white rounded-2xl shadow-md p-4 sm:p-6">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">

                    <!-- Correct MCQ -->
                    <div class="text-center p-4 bg-gray-50 rounded-xl shadow-sm">
                        <p class="text-base sm:text-lg font-semibold text-gray-600">Correct MCQs</p>
                        <p class="text-3xl sm:text-4xl font-extrabold text-green-600">
                            {{ $score }} / {{ $total }}
                        </p>
                    </div>

                    <!-- Score -->
                    <div class="text-center p-4 bg-gray-50 rounded-xl shadow-sm">
                        <p class="text-base sm:text-lg font-semibold text-gray-600">Score</p>
                        <p class="text-3xl sm:text-4xl font-extrabold text-blue-600">
                            {{ $scoremarks }} / {{ $totalMarks }}
                        </p>
                    </div>

                </div>

                <!-- Percentage -->
                @if($totalMarks > 0)
                <div class="text-center mt-6">
                    <p class="text-base sm:text-lg font-semibold text-gray-600">Percentage</p>
                    <p class="text-2xl sm:text-3xl font-bold text-indigo-600">
                        {{ number_format(($scoremarks / $totalMarks) * 100, 2) }}%
                    </p>
                </div>
                @endif
            </div>

            <!-- Score Message -->
            <p class="text-lg sm:text-xl mt-6 font-bold
                @if($score == $total)
                    text-green-700
                @elseif($score > 7)
                    text-blue-600
                @elseif($score >= 5)
                    text-yellow-600
                @else
                    text-red-600
                @endif
            ">
                @if(number_format(($scoremarks / $totalMarks) * 100, 2) == 100)
                    Excellent!!!
                @elseif(number_format(($scoremarks / $totalMarks) * 100, 2) > 80)
                    Good Work!!
                @elseif(number_format(($scoremarks / $totalMarks) * 100, 2) >= 50)
                    Keep Improving!!
                @else
                    Please Learn More!!
                @endif
            </p>

        </div>

        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 mt-6">

            <a href="{{ route('quiz.review', $quiz->id) }}"
               class="w-full sm:w-auto text-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
               Check Your Answers
            </a>

            <a href="{{ route('quiz.index') }}"
               class="w-full sm:w-auto text-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
               Back to Quiz
            </a>

            <a href="{{ route('quiz.show', $quiz->id) }}"
               class="w-full sm:w-auto text-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
               Try Again
            </a>

        </div>

    </div>
</x-app-layout>
