<x-app-layout>
    <div class="max-w-2xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">

        <h1 class="text-3xl font-bold mb-4 text-center">Quiz Result</h1>

       <div class="bg-white shadow-md rounded-xl p-6 text-center max-w-xl mx-auto">

    <!-- Quiz Title -->
    <p class="text-2xl font-bold text-gray-800">
        {{ $quiz->title }}
    </p>

    <!-- Score Box -->
    <!-- <div class="mt-4 bg-gray-100 rounded-lg p-4">
        <p class="text-xl font-semibold text-gray-700">
            MCQ Correct:
        </p>
        <p class="text-3xl font-bold text-green-600 mt-1">
            {{ $score }} / {{ $total }}
        </p>

        <p class="text-lg font-semibold text-gray-700 mt-4">
            Exam Marks:
        </p>
        <p class="text-2xl font-bold text-blue-600">
            {{ $scoremarks }} / {{ $totalMarks }}
        </p>

        @if($totalMarks > 0)
        <p class="text-lg font-semibold text-gray-600 mt-3">
            Percentage:
            <span class="text-indigo-600 font-bold">
                {{ number_format(($scoremarks / $totalMarks) * 100, 2) }}%
            </span>
        </p>
        @endif
    </div> -->
     <div class="bg-white rounded-2xl shadow-lg p-6">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    <div class="text-center p-4 bg-gray-50 rounded-xl shadow-sm">
                        <p class="text-lg font-semibold text-gray-600">Correct MCQs</p>
                        <p class="text-4xl font-extrabold text-green-600">
                            {{ $score }} / {{ $total }}
                        </p>
                    </div>

                    <div class="text-center p-4 bg-gray-50 rounded-xl shadow-sm">
                        <p class="text-lg font-semibold text-gray-600">Score</p>
                        <p class="text-4xl font-extrabold text-blue-600">
                            {{ $scoremarks }} / {{ $totalMarks }}
                        </p>
                    </div>

                </div>

                <!-- Percentage -->
                @if($totalMarks > 0)
                <div class="text-center mt-6">
                    <p class="text-lg font-semibold text-gray-600">Percentage</p>
                    <p class="text-3xl font-bold text-indigo-600">
                        {{ number_format(($scoremarks / $totalMarks) * 100, 2) }}%
                    </p>
                </div>
                @endif
            </div>

    <!-- Score Message -->
    <p class="text-xl mt-6 font-bold
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


        <div class="flex justify-center gap-4 mt-6">
            <a href="{{ route('quiz.review', $quiz->id) }}"
                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                Check Your Answers
                </a>

            <a href="{{ route('quiz.index') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
               Back to Quiz
            </a>

            <a href="{{ route('quiz.show', $quiz->id) }}"
               class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
               Try Again
            </a>
        </div>

    </div>
</x-app-layout>
