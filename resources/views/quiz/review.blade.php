<x-app-layout>
<div class="max-w-3xl mx-auto p-6 mt-10 bg-white shadow-lg rounded-lg">

    <h1 class="text-3xl font-bold mb-6 text-center">
        Review Answers
    </h1>

 @foreach($review as $index => $item)
    <div class="mb-6 p-4 border rounded-lg">
        <p class="font-semibold text-lg mb-2">Q{{ $index + 1 }}. {{ $item['question'] }}</p>

     @foreach($item['options'] as $key => $option)
    @php
        $isCorrect = ($option == $item['correct_answer']);
        $isUser = ($option == $item['user_answer']);
    @endphp

    <div class="relative p-3 rounded mt-2 border
        @if($isCorrect && $isUser) bg-green-300 border-green-400
        @elseif($isCorrect && !$isUser) bg-gray-300 border-gray-400
        @elseif($isUser) bg-red-100 border-red-300
        @else bg-gray-100
        @endif
    ">
        {{-- Option text --}}
        <span class="font-medium">{{ $option }}</span>

        {{-- Wrong user answer --}}
        @if($isUser && !$isCorrect)
            <span class="absolute right-3 top-3 text-xs bg-red-300 text-white px-2 py-1 rounded">
                ❌ Your answer
            </span>
        @endif

        {{-- Correct answer --}}
        @if($isCorrect && !$isUser)
            <span class="absolute right-3 top-3 text-xs bg-gray-500 text-white px-2 py-1 rounded">
                ✔ Correct answer
            </span>
        @endif
         @if($isCorrect && $isUser)
            <span class="absolute right-3 top-3 text-xs bg-gray-500 text-white px-2 py-1 rounded">
                ✔ Your answer
            </span>
        @endif
    </div>
@endforeach


        @if($item['user_answer'] === null)
            <p class="text-gray-500 mt-2">You skipped this question.</p>
        @endif
    </div>
@endforeach
<div class="text-center mt-6">
    <button onclick="history.back()"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
        Back
    </button>
</div>

</div>
</x-app-layout>
