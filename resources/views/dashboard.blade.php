<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- USER DASHBOARD --}}
            @if(auth()->user()->role_id == 2)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                    <div class="p-6 text-gray-900">
                        Welcome 12, <strong>{{ auth()->user()->name }}</strong> 👋  
                        <p class="text-sm text-gray-600 mt-1">User Dashboard</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('quiz.index') }}">
                        <div class="p-6 bg-gray-100 border rounded shadow hover:bg-gray-200">
                            <h3 class="text-xl font-semibold">Available Quizzes</h3>
                            <p class="text-gray-600">Start a quiz and test your knowledge.</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.attempts.index') }}">
                        <div class="p-6 bg-gray-100 border rounded shadow hover:bg-gray-200">
                            <h3 class="text-xl font-semibold">My Quiz Attempts</h3>
                            <p class="text-gray-600">View your completed quiz results.</p>
                        </div>
                    </a>
                </div>
            @endif

            {{-- ADMIN DASHBOARD --}}
            @if(auth()->user()->role_id == 1)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                    <div class="p-6 text-gray-900">
                        Welcome, <strong>{{ auth()->user()->name }}</strong> 👋  
                     
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('mcq.index') }}">
                        <div class="p-6 bg-gray-100 border rounded shadow hover:bg-gray-200">
                            <h3 class="text-xl font-semibold">Manage MCQs</h3>
                            <p class="text-gray-600">Create & update MCQ questions.</p>
                        </div>
                    </a>

                    <a href="{{ route('quiz.setting.index') }}">
                        <div class="p-6 bg-gray-100 border rounded shadow hover:bg-gray-200">
                            <h3 class="text-xl font-semibold">Quiz Settings</h3>
                            <p class="text-gray-600">Configure quizzes & assign MCQs.</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.attempts.index') }}">
                        <div class="p-6 bg-gray-100 border rounded shadow hover:bg-gray-200">
                            <h3 class="text-xl font-semibold">All Quiz Attempts</h3>
                            <p class="text-gray-600">View all user quiz results.</p>
                        </div>
                    </a>
                </div>
            @endif

        </div>
    </div>

</x-app-layout>
