<x-app-layout>

<div class="max-w-4xl mx-auto py-10">

    <h1 class="text-3xl font-bold mb-6">My Quiz Schedule</h1>

    @if($schedules->isEmpty())

        <div class="bg-gradient-to-br from-indigo-50 to-blue-50 shadow-lg border rounded-xl p-10 text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">No Scheduled Quizzes</h2>
            <p class="text-gray-600 text-lg">
                Scheduled quizzes will be shown here.
            </p>
        </div>

    @else

        @foreach($schedules as $schedule)
           <div class="bg-white shadow-xl rounded-xl p-6 mb-6 border hover:shadow-2xl transition">

    <!-- Quiz Title -->
    <h2 class="text-2xl font-semibold mb-2">
        {{ $schedule->quiz->title }}
    </h2>

    <!-- Description -->
    <p class="text-gray-600 mb-2">
        <strong>Description:</strong>
        {!! $schedule->quiz->description ?? 'No description available.' !!}
    </p>

    <!-- Rules -->
    <p class="text-red-600 mb-2 mt-2">
        <strong>Rules:</strong>
        {!! $schedule->quiz->rules ??
            '1. No negative marking.<br>
             2. Do not refresh page.<br>
             3. Attempt all questions.<br>
             4. Submit before time ends.' !!}
    </p>

    <!-- Info Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 mt-2">

        <!-- Scheduled At -->
        <div class="bg-gray-100 p-6 rounded-lg text-center">
            <h3 class="text-lg font-semibold">Scheduled At</h3>
            <p class="text-xl font-bold">
                {{ \Carbon\Carbon::parse($schedule->schedule_at)->format('d M Y, h:i A') }}
            </p>
        </div>

        <!-- Status -->
        <div class="bg-gray-100 p-4 rounded-lg text-center">
            <h3 class="text-lg font-semibold">Status</h3>
            <p class="text-xl font-bold">

                @if($schedule->is_processed)
                    <span class="text-green-600">Quiz Available</span>
                @else
                    <span class="text-red-600">Not Yet Available</span>
                @endif

            </p>
        </div>

        <!-- Countdown -->
        <div class="bg-gray-100 p-6 rounded-lg text-center">
            <h3 class="text-lg font-semibold">Countdown</h3>
            <p id="countdown-card-{{ $schedule->id }}"
               class="text-3xl font-extrabold text-red-600 tracking-widest">
               Loading...
            </p>
        </div>

    </div>

    <!-- More Info -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

        <!-- Questions -->
        <div class="bg-gray-100 p-4 rounded-lg text-center">
            <h3 class="text-lg font-semibold">Questions</h3>
            <p class="text-xl font-bold">
                {{ $schedule->quiz->question_count ?? "—" }}
            </p>
        </div>

        <!-- Marks -->
        <div class="bg-gray-100 p-4 rounded-lg text-center">
            <h3 class="text-lg font-semibold">Total Marks</h3>
            <p class="text-xl font-bold">
                {{ $schedule->quiz->total_marks ?? "—" }}
            </p>
        </div>

        <!-- Time -->
        <div class="bg-gray-100 p-4 rounded-lg text-center">
            <h3 class="text-lg font-semibold">Time Duration</h3>
            <p class="text-xl font-bold">
                {{ isset($schedule->quiz->quiz_time) ? $schedule->quiz->quiz_time / 60 : '—' }} min
            </p>
        </div>

    </div>

    <!-- Start Button -->
    @if($schedule->is_processed)
        <button onclick="openScheduleModal('{{ $schedule->schedule_at }}',
                '{{ route('quiz.show', $schedule->quiz_id) }}')"
                class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
            Start Quiz
        </button>
    @else
        <!-- <span class="text-gray-500 text-lg">Not Available</span> -->
    @endif

</div>


            <!-- Time data for JS -->
            <script>
                window.scheduleTimes = window.scheduleTimes || [];
                window.scheduleTimes.push({
                    id: "{{ $schedule->id }}",
                    time: "{{ $schedule->schedule_at }}"
                });
            </script>

        @endforeach

    @endif

</div>


<!-- ========== MODAL ========== -->
<div id="scheduleModal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">

    <div class="bg-white p-6 rounded-2xl shadow-2xl w-96 text-center transform scale-95 opacity-0 transition-all"
         id="scheduleModalContent">

        <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>

        <div class="flex justify-center mb-4">
            <svg class="w-12 h-12 text-red-500 animate-pulse" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 8v4l3 3m6 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <h3 class="text-2xl font-bold mb-2">Quiz Will Start Soon!</h3>
        <p class="mb-4 text-gray-600">Please wait until the scheduled start time.</p>

        <p class="mb-1 text-gray-500">Remaining Time:</p>
        <p id="countdownModal" class="text-3xl font-extrabold text-red-600"></p>

        <button id="startNowBtn" disabled
                class="bg-gray-400 text-white px-6 py-2 rounded-full mt-3 cursor-not-allowed text-lg font-semibold">
            Please wait...
        </button>

    </div>

</div>



<script>
/* --------------------------
   1) AUTO COUNTDOWN IN CARDS
-----------------------------*/
function startCardCountdown() {
    if (!window.scheduleTimes) return;

    setInterval(() => {
        const now = new Date().getTime();

        window.scheduleTimes.forEach(item => {
            const target = new Date(item.time).getTime();
            const diff = target - now;

            const el = document.getElementById("countdown-card-" + item.id);
            if (!el) return;

            if (diff <= 0) {
                el.innerHTML = "00:00:00";
                return;
            }

            let h = Math.floor(diff / (1000 * 60 * 60));
            let m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            let s = Math.floor((diff % (1000 * 60)) / 1000);

            el.innerHTML = `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
        });

    }, 1000);
}

startCardCountdown();



/* --------------------------
   2) MODAL COUNTDOWN
-----------------------------*/
let modalInterval;
let quizUrl = "";

function openScheduleModal(scheduleTime, url) {
    quizUrl = url;

    document.getElementById("scheduleModal").classList.remove("hidden");

    const content = document.getElementById("scheduleModalContent");
    setTimeout(() => {
        content.classList.add("scale-100", "opacity-100");
    }, 10);

    startModalCountdown(scheduleTime);
}

function closeModal() {
    const content = document.getElementById("scheduleModalContent");
    content.classList.remove("scale-100", "opacity-100");

    setTimeout(() => {
        document.getElementById("scheduleModal").classList.add("hidden");
    }, 300);

    clearInterval(modalInterval);
}

function startModalCountdown(time) {
    const target = new Date(time).getTime();

    modalInterval = setInterval(() => {
        const now = new Date().getTime();
        const diff = target - now;

        const el = document.getElementById("countdownModal");

        if (diff <= 0) {
            el.innerHTML = "00:00:00";

            const btn = document.getElementById("startNowBtn");
            btn.innerHTML = "Start Quiz";
            btn.disabled = false;
            btn.classList.remove("bg-gray-400", "cursor-not-allowed");
            btn.classList.add("bg-green-600", "hover:bg-green-700");

            btn.onclick = () => window.location.href = quizUrl;

            clearInterval(modalInterval);
            return;
        }

        let h = Math.floor(diff / (1000 * 60 * 60));
        let m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        let s = Math.floor((diff % (1000 * 60)) / 1000);

        el.innerHTML = `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
    }, 1000);
}
</script>


</x-app-layout>
