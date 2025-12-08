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
            ⏳ Time Left: <span id="timer">10</span>
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

        <div class="flex justify-end mt-4 gap-2">
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

    </form>

</div>

<script>
// =============================
// VARIABLES
// =============================
let current = 0;
let timer = {{ $quiz->time_per_question ?? 30 }};
let countdown;
let autoSubmitting = false;

const questions = document.querySelectorAll('.question');
const quizForm = document.getElementById('quizForm');
const timerElement = document.getElementById('timer');
const nextBtn = document.getElementById('nextBtn');
const submitBtn = document.getElementById('submitBtn');
const startQuizBtn = document.getElementById('startQuizBtn');
const startScreen = document.getElementById('startScreen');

// Store initial screen size
let screenWidth = window.innerWidth;
let screenHeight = window.innerHeight;


// =============================
// FULLSCREEN FUNCTION
// =============================
function openFullscreen() {
    let el = document.documentElement;
    if (el.requestFullscreen) el.requestFullscreen();
    else if (el.webkitRequestFullscreen) el.webkitRequestFullscreen();
    else if (el.msRequestFullscreen) el.msRequestFullscreen();
}


// =============================
// SAFE SUBMIT FUNCTION
// =============================
function safeSubmit() {
    if (!autoSubmitting) {
        autoSubmitting = true;
        quizForm.submit();
    }
}


// =============================
// START QUIZ BUTTON
// =============================
startQuizBtn.addEventListener('click', () => {
    openFullscreen();

    startScreen.style.display = "none";
    quizForm.style.display = "block";

    showQuestion(0);
    startTimer();
});


// =============================
// TIMER FUNCTIONS
// =============================
function startTimer() {
    clearInterval(countdown);
    timer = {{ $quiz->time_per_question ?? 30 }};
    timerElement.innerText = formatTime(timer);

    countdown = setInterval(() => {
        timer--;
        timerElement.innerText = formatTime(timer);

        if (timer <= 0) {
            clearInterval(countdown);
            goToNextQuestion();
        }
    }, 1000);
}

function formatTime(sec) {
    return sec < 60 ? sec + " sec" : `${Math.floor(sec/60)}:${String(sec%60).padStart(2,'0')} min`;
}


// =============================
// QUESTION NAVIGATION
// =============================
function showQuestion(index) {
    questions.forEach((q, i) => {
        q.style.display = i === index ? 'block' : 'none';
    });

    nextBtn.style.display = index === questions.length - 1 ? 'none' : 'inline-block';
    submitBtn.style.display = index === questions.length - 1 ? 'inline-block' : 'none';
}

function goToNextQuestion() {
    if (current < questions.length - 1) {
        current++;
        showQuestion(current);
        startTimer();
    } else {
        safeSubmit();
    }
}

nextBtn.addEventListener('click', () => {
    clearInterval(countdown);
    goToNextQuestion();
});


// =============================
// SUBMIT BUTTON CLICK
// =============================
submitBtn.addEventListener('click', function(e) {
    e.preventDefault(); // prevent default just in case
    submitBtn.disabled = true;
    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
    safeSubmit();
});


// =============================
// SECURITY / ANTI-CHEAT
// =============================

// Exit fullscreen → auto submit
document.addEventListener("fullscreenchange", () => {
    if (!document.fullscreenElement) {
        safeSubmit();
    }
});

// Tab switch / minimize
document.addEventListener("visibilitychange", () => {
    if (document.hidden) {
        safeSubmit();
    }
});

// Window resize
window.addEventListener("resize", () => {
    if (window.innerWidth < screenWidth - 50 || window.innerHeight < screenHeight - 50) {
        safeSubmit();
    }
});

// Page reload / close
window.addEventListener("beforeunload", function () {
    safeSubmit();
});

</script>

</x-app-layout>
