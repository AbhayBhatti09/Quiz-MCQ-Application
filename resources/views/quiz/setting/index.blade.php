<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Quiz Settings</h2>
                <p class="text-gray-600 mt-2">Manage MCQs and configure your quizzes</p>
            </div>

            <!-- Instructions -->
            <div class="bg-gradient-to-r from-gray-100 to-gray-50 p-6 rounded-lg shadow mb-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Instructions:</h3>
                <ul class="list-disc ml-5 space-y-1 text-gray-600">
                    <li>Select MCQs that should appear in the quiz.</li>
                    <li>After saving, only selected MCQs will be shown to candidates.</li>
                    <li>You can edit or remove MCQs anytime.</li>
                </ul>
            </div>

            <!-- Action Cards -->
             <div class="com-md-12">
                <div class="row">
                <div class="col-md-6">
                

                    <!-- Selected MCQs -->
                    <a href="{{ route('schedule.index') }}">
                        <div class="p-6 bg-green-50 hover:bg-gray-200 rounded-lg shadow  items-center justify-center transition">
                           
                            <h3 class="text-xl text-center font-semibold text-green-700">User Vise Quiz</h3>
                            <p class="text-gray-500 text-sm mt-1 text-center">View Quizs and manage MCQs included in the quiz.</p>
                        </div>
                    </a>
                    
                </div>
                <div class="col-md-6">
                
                    <!-- Selected MCQs -->
                    <a href="{{ route('quiz.setting.listing') }}">
                        <div class="p-6 bg-green-50 hover:bg-gray-200 rounded-lg shadow  items-center justify-center transition">
                            
                            <h3 class="text-xl text-center font-semibold text-green-700">All Quiz</h3>
                            <p class="text-gray-500 text-sm mt-1 text-center">View Quizs and manage MCQs included in the quiz.</p>
                        </div>
                    </a>
                    
                </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
