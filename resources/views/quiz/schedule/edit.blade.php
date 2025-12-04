<x-app-layout>
<div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white shadow sm:rounded-lg p-6 mt-2">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Edit Quiz Schedule</h2>
            <a href="{{ route('schedule.index') }}" class="btn btn-light">Back</a>
        </div>

        <form action="{{ route('schedule.update', $schedule->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Quiz --}}
            <div class="mb-4">
                <label class="font-semibold">Select Quiz</label>
                <select name="quiz_id" class="border rounded w-full p-2">
                    @foreach($quizs as $quiz)
                        <option value="{{ $quiz->id }}" 
                            {{ $schedule->quiz_id == $quiz->id ? 'selected' : '' }}>
                            {{ $quiz->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- User --}}
            <div class="mb-4">
                <label class="font-semibold">Select User</label>
                <select name="user_id" class="border rounded w-full p-2">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" 
                            {{ $schedule->user_id == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Schedule At --}}
            <div class="mb-4">
                <label class="font-semibold">Schedule Date & Time</label>
                <input type="datetime-local" 
                       name="schedule_at" 
                       value="{{ date('Y-m-d\TH:i', strtotime($schedule->schedule_at)) }}"
                       class="border rounded w-full p-2">
            </div>

            {{-- Processed --}}
            <div class="mb-4">
                <label class="flex items-center gap-2">
                    <input type="checkbox" 
                           name="is_processed" 
                           value="1"
                           {{ $schedule->is_processed ? 'checked' : '' }}>
                    <span class="font-semibold">Mark as processed?</span>
                </label>
            </div>

            <div class="mt-4 text-right">
                <button type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded">
                    Update Schedule
                </button>
            </div>

        </form>

    </div>
</div>
</x-app-layout>
