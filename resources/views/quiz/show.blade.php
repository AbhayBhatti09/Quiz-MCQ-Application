<x-app-layout>

<div class="max-w-2xl mx-auto p-6">

    <h1 class="text-2xl font-bold mb-4">{{ $quiz->title }}</h1>

    <div class="text-right text-lg font-semibold mb-4">
        ⏳ Time Left: <span id="timer">10</span>
    </div>

    <form id="quizForm" method="POST" action="{{ route('quiz.submit', $quiz) }}">
        @csrf

        @foreach($mcqs as $index => $mcq)
            <div class="question mb-6 p-4 border rounded-lg"
                 style="{{ $index > 0 ? 'display:none;' : '' }}">

                <p class="font-semibold mb-2">
                    Q{{ $index + 1 }}. {{ $mcq->question }}
                </p>

                <input type="hidden" name="answers[{{ $mcq->id }}]" value="">

                @foreach($mcq->shuffled_options as $option)
                    <label class="block mb-1">
                        <input type="radio"
                               class="option"
                               name="answers[{{ $mcq->id }}]"
                               value="{{ $option }}">
                        {{ $option }}
                    </label>
                @endforeach

            </div>
        @endforeach

        <div class="flex justify-end mt-4">
            <button type="button" id="nextBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
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
// ------------------------------
// Question Navigation + Timer
// ------------------------------
let current = 0;
let timer = {{ $quiz->time_per_question ?? 30 }};
let countdown;

const questions = document.querySelectorAll('.question');
const nextBtn = document.getElementById('nextBtn');
const submitBtn = document.getElementById('submitBtn');
const timerElement = document.getElementById('timer');

// Start timer per question
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

function goToNextQuestion() {
    if (current < questions.length - 1) {
        current++;
        showQuestion(current);
        startTimer();
    } else {
        document.getElementById('quizForm').submit();
    }
}

function showQuestion(index) {
    questions.forEach((q, i) => {
        q.style.display = i === index ? 'block' : 'none';
    });

    nextBtn.style.display = index === questions.length - 1 ? 'none' : 'inline-block';
    submitBtn.style.display = index === questions.length - 1 ? 'inline-block' : 'none';
}

nextBtn.addEventListener('click', () => {
    clearInterval(countdown);
    goToNextQuestion();
});

// Timer formatting
function formatTime(sec) {
    if (sec < 60) return sec + " sec";

    const m = String(Math.floor(sec / 60)).padStart(2, '0');
    const s = String(sec % 60).padStart(2, '0');
    return `${m}:${s} min`;
}

// Disable button multiple click
submitBtn.addEventListener('click', function(e) {
    if (submitBtn.disabled) {
        e.preventDefault();
        return;
    }
    submitBtn.disabled = true;
    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
});

// Load first question
showQuestion(current);
startTimer();


// ------------------------------
// FULLSCREEN PROTECTION
// ------------------------------
function openFullscreen() {
    let el = document.documentElement;

    if (el.requestFullscreen) el.requestFullscreen();
    else if (el.mozRequestFullScreen) el.mozRequestFullScreen();
    else if (el.webkitRequestFullscreen) el.webkitRequestFullscreen();
    else if (el.msRequestFullscreen) el.msRequestFullscreen();
}

openFullscreen();

// Auto-submit if leaving fullscreen
document.addEventListener("fullscreenchange", () => {
    if (!document.fullscreenElement) {
        document.getElementById("quizForm").submit();
    }
});

// ------------------------------
// WINDOW RESIZE PROTECTION
// ------------------------------
let screenWidth = window.innerWidth;
let screenHeight = window.innerHeight;

window.addEventListener("resize", function () {
    if (
        window.innerWidth < screenWidth - 50 ||
        window.innerHeight < screenHeight - 50
    ) {
        document.getElementById("quizForm").submit();
    }
});

// ------------------------------
// TAB SWITCH PROTECTION
// ------------------------------
document.addEventListener("visibilitychange", function () {
    if (document.hidden) {
        document.getElementById("quizForm").submit();
    }
});

// ------------------------------
// PAGE CLOSE / RELOAD PROTECTION
// ------------------------------
window.addEventListener("beforeunload", function () {
    document.getElementById("quizForm").submit();
});
</script>

</x-app-layout>
