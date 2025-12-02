<x-app-layout>
<div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white shadow sm:rounded-lg p-6">

        <h2 class="text-xl font-bold mb-4">My Quiz Schedule</h2>

        <table class="table-auto w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">Quiz Name</th>
                    <th class="border p-2">Scheduled At</th>
                    <th class="border p-2">Status</th>
                    <th class="border p-2">Action</th>
                </tr>
            </thead>

            <tbody>
            @forelse($schedules as $schedule)
                <tr>
                    <td class="border p-2">{{ $schedule->quiz->title }}</td>

                    <td class="border p-2">
                        {{ \Carbon\Carbon::parse($schedule->schedule_at)->format('d M Y, h:i A') }}
                    </td>

                    <td class="border p-2">
                        @if($schedule->is_processed)
                            <span class="text-green-600 font-semibold">Processed</span>
                        @else
                            <span class="text-red-600 font-semibold">Pending</span>
                        @endif
                    </td>

                    <td class="border p-2 text-center">
                        @if($schedule->is_processed)
                            <a href="javascript:void(0)"
                               onclick="openScheduleModal('{{ $schedule->schedule_at }}', '{{ route('quiz.show', $schedule->quiz_id) }}')"
                               class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition">
                                Start Quiz
                            </a>
                        @else
                            <span class="text-gray-500">Not available</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-4 text-center">No scheduled quizzes.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

    </div>
</div>

<!-- Start Quiz Modal -->
<div id="scheduleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-2xl shadow-2xl w-96 text-center relative transform scale-95 opacity-0 transition-all duration-300"
         id="scheduleModalContent">

        <!-- Close Button -->
        <button onclick="closeModal()"
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-2xl font-bold">&times;</button>

        <!-- Icon -->
        <div class="flex justify-center mb-4">
            <svg class="w-12 h-12 text-red-500 animate-pulse" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 8v4l3 3m6 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>

        <h3 class="text-2xl font-bold mb-2 text-gray-800">Quiz Will Start Soon!</h3>
        <p class="mb-4 text-gray-600">Please wait until the scheduled start time.</p>

        <div class="mb-4">
            <p class="mb-1 text-gray-500">Remaining Time:</p>
            <p id="countdownTime" class="text-3xl font-extrabold text-red-600 tracking-widest"></p>
        </div>

        <button id="startNowBtn"
                disabled
                class="bg-gray-400 text-white px-6 py-2 rounded-full mt-3 cursor-not-allowed text-lg font-semibold transition-all duration-300 hover:bg-gray-500">
            Please wait...
        </button>

    </div>
</div>

<script>
    let quizUrl = "";
    let timerInterval;

    function openScheduleModal(scheduleTime, url) {
        quizUrl = url;

        const modal = document.getElementById('scheduleModal');
        const content = document.getElementById('scheduleModalContent');

        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);

        startCountdown(scheduleTime);
    }

    function closeModal() {
        const content = document.getElementById('scheduleModalContent');
        content.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            document.getElementById('scheduleModal').classList.add('hidden');
        }, 300);

        clearInterval(timerInterval);
    }

    function startCountdown(scheduleTime) {
        const target = new Date(scheduleTime).getTime();

        timerInterval = setInterval(() => {
            const now = new Date().getTime();
            const diff = target - now;

            if (diff <= 0) {
                document.getElementById('countdownTime').innerHTML = "00:00:00";

                const startBtn = document.getElementById('startNowBtn');
                startBtn.innerHTML = "Start Quiz";
                startBtn.disabled = false;
                startBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                startBtn.classList.add('bg-green-600', 'hover:bg-green-700');

                clearInterval(timerInterval);

                startBtn.onclick = () => {
                    window.location.href = quizUrl;
                };

                return;
            }

            let hours = Math.floor((diff / (1000 * 60 * 60)));
            let mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            let secs = Math.floor((diff % (1000 * 60)) / 1000);

            document.getElementById('countdownTime').innerHTML =
                `${String(hours).padStart(2, '0')}:${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
        }, 1000);
    }
</script>

</x-app-layout>
