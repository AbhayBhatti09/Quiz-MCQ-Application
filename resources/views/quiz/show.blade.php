<x-app-layout>

<div class="max-w-5xl mx-auto p-6">

    <h1 class="text-2xl font-bold mb-4 text-center">{{ $quiz->title }}</h1>

    <!-- Start Screen -->
    <div id="startScreen" class="text-center p-10 border rounded-xl shadow bg-white">
        <p class="text-xl font-semibold mb-4">Your quiz will start in fullscreen mode.</p>
        <button id="startQuizBtn"
            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Start Quiz
        </button>
    </div>

    <!-- QUIZ AREA -->
    <div id="quizArea" class="gap-4 flex flex-col-reverse sm:flex-row" style="display:none;">

        <!-- LEFT SIDE — MCQ QUESTIONS -->
        <div class="lg:w-2/3 sm:w-full">
            <form id="quizForm" method="POST" action="{{ route('quiz.submit', $quiz) }}">
                @csrf

                <div class="text-right lg:text-lg sm:text-sm font-semibold mb-4">
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
                    <div id="leftButtons">
                        @if($quiz->time_type == 0)
                            <button type="button" id="prevBtn"
                                class="bg-gray-500 text-white px-4 py-2 rounded-lg">
                                Previous
                            </button>
                        @endif
                    </div>

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

        <!-- VERTICAL LINE -->
        <div class="w-px bg-gray-300"></div>
          
        <!-- RIGHT SIDE — MCQ SUMMARY PANEL -->
        <div class="lg:w-1/3  sm:w-full">

            <div class="text-right">
                <h2 class="lg:text-xl sm:text-sm font-semibold mb-4">Quiz Summary</h2>
            </div>
            <div class="border rounded-lg bg-white shadow ">
                <p class=" p-2">Total Questions: <strong>{{ count($mcqs) }}</strong></p>
            <p class=" p-2">Answered: <strong id="answeredCount">0</strong></p>
            <p class=" p-2">Not Answered: <strong id="notAnsweredCount">{{ count($mcqs) }}</strong></p>

            <h3 class="font-semibold mb-2 p-2">Question Status</h3>

            <div class="grid grid-cols-5 gap-2 p-2" id="questionStatusBox">
                @foreach($mcqs as $index => $mcq)
                    <div class="p-2 border text-center rounded bg-red-100 cursor-pointer"
                         data-q="{{ $index }}">
                        {{ $index+1 }}
                    </div>
                @endforeach
            </div>

            </div>
            
        </div>

    </div>

</div>


<script>
// ====================================
// VARIABLES
// ====================================
let current = 0;
let perQuestionTime = {{ $quiz->time_per_question ?? 30 }};
let quizTotalTime = {{ $quiz->quiz_time ?? 0 }};
let isPerQuestion = {{ $quiz->time_type == 1 ? 'true' : 'false' }};
let timer = isPerQuestion ? perQuestionTime : quizTotalTime;

let countdown;
let autoSubmitting = false;

const questions = document.querySelectorAll('.question');
const timerElement = document.getElementById('timer');
const nextBtn = document.getElementById('nextBtn');
const prevBtn = document.getElementById('prevBtn');
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
    document.getElementById('quizArea').style.display = "flex";

    showQuestion(0);
    startTimer();
});


// ====================================
// TIMER
// ====================================
function startTimer() {
    clearInterval(countdown);

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

        if (!isPerQuestion) quizTotalTime = timer;

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

    nextBtn.style.display = index === questions.length - 1 ? 'none' : 'inline-block';
    submitBtn.style.display = index === questions.length - 1 ? 'inline-block' : 'none';

    if (!isPerQuestion) {
        prevBtn.style.display = index === 0 ? 'none' : 'inline-block';
    }

    current = index;
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

if (!isPerQuestion && prevBtn) {
    prevBtn.addEventListener('click', () => {
        goToPrevQuestion();
    });
}

submitBtn.addEventListener('click', function (e) {
    e.preventDefault();
    submitBtn.disabled = true;
    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
    safeSubmit();
});


// ====================================
// CLICK SUMMARY PANEL NUMBER → GO TO QUESTION
// ====================================
if (isPerQuestion) {
    document.querySelectorAll('#questionStatusBox div').forEach(item => {
        item.classList.add('disabled');
    });
}
document.querySelectorAll('#questionStatusBox div').forEach(box => {
    box.addEventListener('click', () => {
        let qIndex = parseInt(box.getAttribute('data-q'));

        // If perQuestion mode → disable clicks & stop here
        if (isPerQuestion) {
       
            return; // ❌ do not showQuestion
        }

        // PerQuiz mode → allow clicking
        showQuestion(qIndex);
        current = qIndex;
    });
});


// ====================================
// STATUS UPDATE ON OPTION SELECT
// ====================================
document.querySelectorAll('.option').forEach(option => {
    option.addEventListener('change', function () {

        let answered = 0;
        let total = document.querySelectorAll('.question').length;

        document.querySelectorAll('.question').forEach((q, index) => {
            let selected = q.querySelector('input[type="radio"]:checked');
            let box = document.querySelector(`[data-q="${index}"]`);

            if (selected) {
                answered++;
                box.classList.remove('bg-red-100');
                box.classList.add('bg-green-200');
            } else {
                box.classList.remove('bg-green-200');
                box.classList.add('bg-red-100');
            }
        });

        document.getElementById('answeredCount').innerText = answered;
        document.getElementById('notAnsweredCount').innerText = total - answered;
    });
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
<style>
    #questionStatusBox div.disabled {
   
   cursor: not-allowed; 
}
</style>
</x-app-layout>
