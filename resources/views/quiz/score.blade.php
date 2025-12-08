<x-app-layout>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">

                    <!-- Heading -->
                    <h2 class="text-center fw-bold mb-4">Quiz Result</h2>

                    <!-- Quiz Title -->
                    <h4 class="text-center fw-semibold text-primary mb-4">
                        {{ $quiz->title }}
                    </h4>

                    <!-- Score Section -->
                    <div class="row g-4">

                        <!-- Correct MCQ -->
                        <div class="col-12 col-md-6">
                            <div class="p-4 text-center bg-light rounded-4 shadow-sm">
                                <p class="fw-semibold text-secondary mb-1">Correct MCQs</p>
                                <h2 class="fw-bold text-success">
                                    {{ $score }} / {{ $total }}
                                </h2>
                            </div>
                        </div>

                        <!-- Score -->
                        <div class="col-12 col-md-6">
                            <div class="p-4 text-center bg-light rounded-4 shadow-sm">
                                <p class="fw-semibold text-secondary mb-1">Score</p>
                                <h2 class="fw-bold text-primary">
                                    {{ $scoremarks }} / {{ $totalMarks }}
                                </h2>
                            </div>
                        </div>

                    </div>

                    <!-- Percentage -->
                    @if($totalMarks > 0)
                    <div class="text-center mt-4">
                        <p class="fw-semibold text-secondary">Percentage</p>
                        <h3 class="fw-bold text-info">
                            {{ number_format(($scoremarks / $totalMarks) * 100, 2) }}%
                        </h3>
                    </div>
                    @endif

                    <!-- Score Message -->
                    <div class="text-center mt-4">
                        <h4 class="fw-bold 
                            @if($score == $total)
                                text-success
                            @elseif($score > 7)
                                text-primary
                            @elseif($score >= 5)
                                text-warning
                            @else
                                text-danger
                            @endif
                        ">
                            @if(number_format(($scoremarks / $totalMarks) * 100, 2) == 100)
                                Excellent!!! 🎉
                            @elseif(number_format(($scoremarks / $totalMarks) * 100, 2) > 80)
                                Good Work!! 👍
                            @elseif(number_format(($scoremarks / $totalMarks) * 100, 2) >= 50)
                                Keep Improving!! 🙂
                            @else
                                Please Learn More!! 📘
                            @endif
                        </h4>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex flex-column flex-md-row justify-content-center gap-3 mt-4">

                        <a href="{{ route('quiz.review', $quiz->id) }}"
                           class="btn btn-success px-4 py-2  ">
                           Check Your Answers
                        </a>

                        <a href="{{ route('quiz.index') }}"
                           class="btn btn-primary px-4 py-2  ">
                           Back to Quiz
                        </a>

                        <a href="{{ route('quiz.show', $quiz->id) }}"
                           class="btn btn-success px-4 py-2   text-white">
                           Try Again
                        </a>

                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

</x-app-layout>
