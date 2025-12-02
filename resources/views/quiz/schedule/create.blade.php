<x-app-layout>
<div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white shadow sm:rounded-lg p-6 mt-2">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Create Quiz Schedule</h2>
            <a href="{{ route('schedule.index') }}" class="btn btn-light">Back</a>
        </div>

        <form action="{{ route('schedule.store') }}" method="POST">
            @csrf

            {{-- Select Quiz --}}
            <div class="mb-4">
                <label class="font-semibold">Select Quiz <span class="text-red-500">*</span></label>
                <select name="quiz_id" class="border rounded w-full p-2">
                    <option value="">-- Select Quiz --</option>
                    @foreach($quizs as $quiz)
                        <option value="{{ $quiz->id }}">{{ $quiz->title }}</option>
                    @endforeach
                </select>
                @error('quiz_id') 
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Select User --}}
            <div class="mb-4">
                <label class="font-semibold">Select User <span class="text-red-500">*</span></label>
                <select name="user_id" class="border rounded w-full p-2">
                    <option value="">-- Select User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                @error('user_id') 
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Schedule Date-Time --}}
            <div class="mb-4">
                <label class="font-semibold">Schedule Date & Time <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="schedule_at" class="border rounded w-full p-2">
                @error('schedule_at')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Toggle --}}
            <div class="mb-4">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_processed" value="1">
                    <span class="font-semibold">Mark as processed immediately?</span>
                </label>
            </div>

            {{-- Submit --}}
            <div class="mt-4 text-right">
                <button type="submit" 
                    class="px-4 py-2 bg-green-600 text-white rounded">
                    Create Schedule
                </button>
            </div>

        </form>

    </div>
</div>
</x-app-layout>
