<x-app-layout>

<div class="max-w-2xl mx-auto p-6">

    <h1 class="text-2xl font-bold mb-4 text-center">{{ $quiz->title }}</h1>

    <!-- Start Screen -->
    <div id="startScreen" class="text-center p-10 border rounded-xl shadow bg-white">
        <p class="text-xl font-semibold mb-4">Your quiz will start in fullscreen mode.</p>
        <button id="startQuizBtn"
            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Start Quiz
        </button>
    </div>

    <!-- Actual Quiz -->
    <form id="quizForm" method="POST" action="{{ route('quiz.submit', $quiz) }}" style="display:none;">
        @csrf

        <div class="text-right text-lg font-semibold mb-4">
            @if($quiz->time_type == 1)
                ⏳ Time Left (This Question): <span id="timer"></span>
            @else
                ⏳ Total Time Left: <span id="timer"></span>
            @endif
        </div>


        @foreach($mcqs as $index => $mcq)
            <div class="question mb-6 p-4 border rounded-lg bg-white"
                 style="{{ $index > 0 ? 'display:none;' : '' }}">

                <p class="font-semibold mb-2">
                    Q{{ $index + 1 }}. {{ $mcq->question }}
                </p>

                <input type="hidden" name="answers[{{ $mcq->id }}]" value="">

                @foreach($mcq->shuffled_options as $option)
                    <label class="block mb-2 cursor-pointer">
                        <input type="radio"
                               class="option"
                               name="answers[{{ $mcq->id }}]"
                               value="{{ $option }}">
                        {{ $option }}
                    </label>
                @endforeach

            </div>
        @endforeach

        <div class="flex justify-between mt-4 items-center">

    <!-- LEFT SIDE -->
    <div id="leftButtons">
        @if($quiz->time_type == 0)
        <button type="button" id="prevBtn"
            class="bg-gray-500 text-white px-4 py-2 rounded-lg">
            Previous
        </button>
        @endif
    </div>

    <!-- RIGHT SIDE -->
    <div class="flex gap-2">
        <button type="button" id="nextBtn"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            Next
        </button>

        <button type="submit" id="submitBtn"
            class="bg-green-600 text-white px-4 py-2 rounded-lg"
            style="display:none;">
            Submit
        </button>
    </div>

</div>


    </form>

</div>

<script>
// ====================================
// VARIABLES
// ====================================
let current = 0;

// per question time (default)
let perQuestionTime = {{ $quiz->time_per_question ?? 30 }};

// quiz-level time in seconds
let quizTotalTime = {{ $quiz->quiz_time ?? 0 }};

// mode
let isPerQuestion = {{ $quiz->time_type == 1 ? 'true' : 'false' }};

let timer = isPerQuestion ? perQuestionTime : quizTotalTime;

let countdown;
let autoSubmitting = false;

const questions = document.querySelectorAll('.question');
const timerElement = document.getElementById('timer');
const nextBtn = document.getElementById('nextBtn');
const prevBtn = document.getElementById('prevBtn'); // only exists if type=0
const submitBtn = document.getElementById('submitBtn');
const startScreen = document.getElementById('startScreen');
const startQuizBtn = document.getElementById('startQuizBtn');
const quizForm = document.getElementById('quizForm');


// ====================================
// SAFE SUBMIT
// ====================================
function safeSubmit() {
    if (!autoSubmitting) {
        autoSubmitting = true;
        quizForm.submit();
    }
}


// ====================================
// FULLSCREEN
// ====================================
function openFullscreen() {
    let el = document.documentElement;
    if (el.requestFullscreen) el.requestFullscreen();
}


// ====================================
// START QUIZ
// ====================================
startQuizBtn.addEventListener('click', () => {
    openFullscreen();

    startScreen.style.display = "none";
    quizForm.style.display = "block";

    showQuestion(0);
    startTimer();
});


// ====================================
// TIMER
// ====================================
function startTimer() {
    clearInterval(countdown);

    // per question or full quiz timer
    timer = isPerQuestion ? perQuestionTime : quizTotalTime;

  timerElement.innerText = formatTime(timer);

    countdown = setInterval(() => {
        timer--;
        timerElement.innerText = formatTime(timer);

        if (timer <= 0) {
            clearInterval(countdown);

            if (isPerQuestion) {
                goToNextQuestion();
            } else {
                safeSubmit();
            }
        }

        if (!isPerQuestion) {
            quizTotalTime = timer; // update global value
        }
    }, 1000);
}


function formatTime(sec) {
    return sec < 60 
        ? sec + " sec"
        : `${Math.floor(sec / 60)}:${String(sec % 60).padStart(2, '0')} min`;
}


// ====================================
// QUESTION NAVIGATION
// ====================================
function showQuestion(index) {
    questions.forEach((q, i) => {
        q.style.display = i === index ? 'block' : 'none';
    });

    if (isPerQuestion) {
        nextBtn.style.display = index === questions.length - 1 ? 'none' : 'inline-block';
        submitBtn.style.display = index === questions.length - 1 ? 'inline-block' : 'none';
    } else {
        // quiz-level mode
        nextBtn.style.display = index === questions.length - 1 ? 'none' : 'inline-block';
        submitBtn.style.display = index === questions.length - 1 ? 'inline-block' : 'none';
        prevBtn.style.display = index === 0 ? 'none' : 'inline-block';
    }
}

function goToNextQuestion() {
    if (current < questions.length - 1) {
        current++;
        showQuestion(current);

        if (isPerQuestion) startTimer();
    } else {
        safeSubmit();
    }
}

function goToPrevQuestion() {
    if (current > 0) {
        current--;
        showQuestion(current);
    }
}


// ====================================
// BUTTON EVENTS
// ====================================
nextBtn.addEventListener('click', () => {
    if (isPerQuestion) clearInterval(countdown);
    goToNextQuestion();
});

if (!isPerQuestion) {
    prevBtn.addEventListener('click', () => {
        goToPrevQuestion();
    });
}

submitBtn.addEventListener('click', function (e) {
    e.preventDefault();
    submitBtn.disabled = true;
    safeSubmit();
});


// ====================================
// SECURITY ANTI-CHEAT
// ====================================
document.addEventListener("fullscreenchange", () => {
    if (!document.fullscreenElement) safeSubmit();
});

document.addEventListener("visibilitychange", () => {
    if (document.hidden) safeSubmit();
});

window.addEventListener("beforeunload", function () {
    safeSubmit();
});

</script>

</x-app-layout>
