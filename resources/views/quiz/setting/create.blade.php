<x-app-layout>
<div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white shadow sm:rounded-lg p-6 mt-2">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">
                {{ isset($quiz) ? 'Edit Quiz' : 'Create New Quiz' }}
            </h2>
            <a href="{{ route('quiz.setting.listing') }}" class="btn btn-light">Back</a>
        </div>

        <form action="{{ isset($quiz) ? route('quiz.setting.update', $quiz->id) : route('quiz.setting.store') }}" method="{{ isset($quiz) ? 'POST' : 'POST' }}" id="quizForm">
            @csrf
            @if(isset($quiz))
                @method('PUT')
            @endif

            <!-- Step 1: Select Categories -->
            <div id="step1">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold">Step 1: Select Categories</h3>

                    <button type="button"
                        onclick="selectAllCategories()"
                        class="px-3 py-1 bg-green-600 text-white rounded">
                        Select All Categories
                    </button>
                </div>


                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($categories as $category)
                        @php
                            $checkedCategory = (isset($selectedCategoryIds) && in_array($category->id, $selectedCategoryIds)) ? true : false;
                        @endphp
                        <label class="category-card cursor-pointer border rounded-lg p-4 flex items-center gap-3 transition-all hover:shadow-lg hover:border-blue-500">
                            <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" class="category-checkbox hidden" {{ $checkedCategory ? 'checked' : '' }}>
                            <div class="flex-1">
                                <p class="font-semibold">{{ $category->title }}</p>
                                <p class="text-gray-500 text-sm">{{ $category->mcqs->count() ?? 0 }} MCQs</p>
                            </div>
                            <div class="checkmark w-5 h-5 border rounded-full flex items-center justify-center bg-blue-500 text-white {{ $checkedCategory ? '' : 'hidden' }}">✓</div>
                        </label>
                    @endforeach
                </div>
                <p id="step1Error" class="text-red-500 text-sm mt-2 hidden">Please select at least one category.</p>

                <div class="mt-4 text-right">
                    <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded" onclick="showStep2()">Next</button>
                </div>
            </div>

            <!-- Step 2: Select MCQs -->
            <div id="step2" style="display:none;">
               <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold">Step 2: Select MCQs</h3>
                    <p id="selectedCount" class="font-bold text-blue-600 text-lg mt-3">
                        Selected MCQs: 0
                    </p>
                    
                    <button type="button"
                        onclick="selectAllMCQs()"
                        class="px-3 py-1 bg-green-600 text-white rounded">
                        Select All MCQs
                    </button>
                </div>

                <div id="mcqList" class="mb-4"></div>
                <p id="step2Error" class="text-red-500 text-sm mt-2 hidden">Please select at least one MCQ.</p>

                <div class="text-right">
                    <button type="button" class="px-4 py-2 bg-gray-400 text-white rounded mr-2" onclick="goStep1()">Back</button>
                    <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded" onclick="goStep3()">Next</button>
                </div>
            </div>

            <!-- Step 3: Quiz Summary -->
            <div id="step3" style="display:none;">
                <h3 class="font-bold mb-4">Step 3: Quiz Summary</h3>

                <div class="mb-3">
                    <label class="font-semibold">Quiz Title <span class="text-red-500">*</span></label>
                    <input type="text" name="quiz_title" id="quiz_title" class="border rounded w-full p-2" value="{{ $quiz->title ?? old('quiz_title') }}">
                    <p id="step3Error" class="text-red-500 text-sm mt-1 hidden"></p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="font-semibold">Total Questions<span class="text-red-500">*</span></label>
                        <input type="number" id="question_count" name="question_count" class="border rounded w-full p-2 bg-gray-100" value="0" readonly>
                    </div>
                    <div>
                        <label class="font-semibold">Marks per Question<span class="text-red-500">*</span></label>
                        <input type="number" name="marks_per_question" id="marks_per_question" class="border rounded w-full p-2" value="{{ $quiz->marks_per_question ?? 1 }}">
                        <p id="marksError" class="text-red-500 text-sm mt-1 hidden"></p>
                    </div>
                    <div>
                        <label class="font-semibold">Total Marks<span class="text-red-500">*</span></label>
                        <input type="number" id="total_marks" class="border rounded w-full p-2 bg-gray-100" value="0" readonly>
                    </div>
                    <div>
                        <label class="font-semibold block mb-2">Time Type</label>

                       <div class="flex items-center mb-2">

                        {{-- Time Per Question --}}
                        <input type="radio"
                            name="time_type"
                            id="time_per_question_radio"
                            value="1"
                            class="mr-2"
                            {{ old('time_type', isset($quiz) ? $quiz->time_type : 1) == 1 ? 'checked' : '' }}>
                        <label for="time_per_question_radio">Time Per Question</label>

                        {{-- Time For Whole Quiz --}}
                        <input type="radio"
                            name="time_type"
                            id="time_per_quiz_radio"
                            value="0"
                            class="ml-4"
                            {{ old('time_type', isset($quiz) ? $quiz->time_type : 1) == 0 ? 'checked' : '' }}>
                        <label for="time_per_quiz_radio">Time For Whole Quiz</label>

                    </div>

                    </div>
                    <div id="time_per_question_wrapper">
                        <label class="font-semibold">Time per Question (Seconds)<span class="text-red-500">*</span></label>
                        <input type="number" name="time_per_question" id="time_per_question" class="border rounded w-full p-2" value="{{ $quiz->time_per_question ?? 30 }}">
                        <p id="timeError" class="text-red-500 text-sm mt-1 hidden"></p>
                    </div>
                    <div id="total_time_wrapper">
                        <label class="font-semibold">Total Time (Minutes)<span class="text-red-500">*</span></label>
                        <input type="text" id="total_time_minutes" name="quiz_time" class="border rounded w-full p-2 bg-gray-100" value="{{ $quiz->quiz_time ?? old('quiz_time') }}" readonly>
                    </div>
                </div>
               <div class="mb-3 mt-3">
                    <label class="font-semibold">Description </label>
                    <textarea name="description" id="description" class="border rounded w-full p-2 h-28">{{ $quiz->description ?? old('description') }}</textarea>
                    <p id="descriptionError" class="text-red-500 text-sm mt-1 hidden"></p>
                </div>

                <div class="mb-3 mt-3">
                    <label class="font-semibold">Rules</label>
                    <textarea name="rules" id="rules" class="border rounded w-full p-2 h-28">{{ $quiz->rules ?? old('rules') }}</textarea>
                    <p id="rulesError" class="text-red-500 text-sm mt-1 hidden"></p>
                </div>

                <div class="mt-4 text-right">
                    <button type="button" class="px-4 py-2 bg-gray-400 text-white rounded mr-2" onclick="goStep2Back()">Back</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">{{ isset($quiz) ? 'Update Quiz' : 'Create Quiz' }}</button>
                </div>
            </div>

        </form>
    </div>
</div>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        ClassicEditor
            .create(document.querySelector('#description'))
            .catch(error => console.error(error));

        ClassicEditor
            .create(document.querySelector('#rules'))
            .catch(error => console.error(error));
    });
</script>

<script>
let allMcqs = @json($mcqs);
let selectedCategories = new Set({{ isset($selectedCategoryIds) ? json_encode($selectedCategoryIds) : '[]' }});
let selectedMcqs = new Set({{ isset($selectedMcqIds) ? json_encode($selectedMcqIds) : '[]' }});

// Pre-generate MCQs for edit
if(selectedCategories.size > 0){
    generateMCQs();
    updateTotals();
    document.getElementById('step1').style.display = 'none';
    document.getElementById('step2').style.display = 'block';
}

// Toggle category selection
document.querySelectorAll('.category-card').forEach(card => {
    card.addEventListener('click', function(e){
        e.preventDefault();
        const checkbox = this.querySelector('input[type="checkbox"]');
        checkbox.checked = !checkbox.checked;
        const checkmark = this.querySelector('.checkmark');
        if(checkbox.checked){
            checkmark.classList.remove('hidden');
            selectedCategories.add(Number(checkbox.value));
        } else {
            checkmark.classList.add('hidden');
            selectedCategories.delete(Number(checkbox.value));
        }
    });
});

// Step navigation
function showStep2(){
    if(selectedCategories.size === 0){
        document.getElementById('step1Error').classList.remove('hidden');
        return;
    } else {
        document.getElementById('step1Error').classList.add('hidden');
    }

    generateMCQs();
    document.getElementById('step1').style.display = 'none';
    document.getElementById('step2').style.display = 'block';
}

function goStep1(){
    document.getElementById('step2').style.display = 'none';
    document.getElementById('step1').style.display = 'block';
}

function goStep2Back(){
    document.getElementById('step3').style.display = 'none';
    document.getElementById('step2').style.display = 'block';
}

function goStep3(){
    if(selectedMcqs.size === 0){
        document.getElementById('step2Error').classList.remove('hidden');
        document.getElementById('step2').style.display = 'block';
        document.getElementById('step3').style.display = 'none';
        return;
    } else {
        document.getElementById('step2Error').classList.add('hidden');
    }

    const quizTitle = document.getElementById('quiz_title').value.trim();
    if(quizTitle === ''){
        document.getElementById('step3Error').classList.remove('hidden');
    } else {
        document.getElementById('step3Error').classList.add('hidden');
    }

    document.getElementById('step2').style.display = 'none';
    document.getElementById('step3').style.display = 'block';
    updateTotals();
}

// Generate MCQs list dynamically
function generateMCQs(){
    let mcqHtml = '';
    let count = 0;

    selectedCategories.forEach(catId => {
        allMcqs.forEach(mcq => {
            if(mcq.category_id === catId){
                count++;
                const checked = selectedMcqs.has(mcq.id) ? 'checked' : '';
                mcqHtml += `
                <div class="border p-3 rounded mb-2">
                    <label class="flex items-start gap-3">
                        <input type="checkbox" name="mcq_ids[]" value="${mcq.id}" onchange="toggleMCQ(${mcq.id});updateSelectedCount();" ${checked}>
                        <div>
                            <p class="font-semibold">Q${count}. ${mcq.question}</p>
                            <ul class="ml-3 mt-1">
                                <li>A) ${mcq.option_a}</li>
                                <li>B) ${mcq.option_b}</li>
                                <li>C) ${mcq.option_c}</li>
                                <li>D) ${mcq.option_d}</li>
                            </ul>
                        </div>
                    </label>
                </div>`;
            }
        });
    });

    document.getElementById('mcqList').innerHTML = mcqHtml;
}

// Toggle MCQ selection
function toggleMCQ(id){
    if(selectedMcqs.has(id)) selectedMcqs.delete(id);
    else selectedMcqs.add(id);
    updateTotals();
}

// Update totals
function updateTotals(){
    document.getElementById('question_count').value = selectedMcqs.size;
    const marks = Number(document.getElementById('marks_per_question').value);
    const time = Number(document.getElementById('time_per_question').value);
    document.getElementById('total_marks').value = selectedMcqs.size * marks;
    const isPerQuestion = document.getElementById('time_per_question_radio').checked;
    if(isPerQuestion){
        
            document.getElementById('total_time_minutes').value = ((selectedMcqs.size * time)/60).toFixed(2);
    }
}

// Listen marks/time changes
document.getElementById('marks_per_question').addEventListener('input', updateTotals);
document.getElementById('time_per_question').addEventListener('input', updateTotals);

// Prevent form submission if invalid
document.getElementById('quizForm').addEventListener('submit', function(e){
    let valid = true;
    const quizTitle = document.getElementById('quiz_title').value.trim();
    if(quizTitle === ''){
        document.getElementById('step3Error').textContent = "Quiz title is required.";
        document.getElementById('step3Error').classList.remove('hidden');
        valid = false;
        goStep3();
    } else {
        document.getElementById('step3Error').classList.add('hidden');
    }

    const marks = Number(document.getElementById('marks_per_question').value);
    if(isNaN(marks) || marks <= 0){
        document.getElementById('marksError').textContent = "Marks per question must be greater than 0.";
        document.getElementById('marksError').classList.remove('hidden');
        valid = false;
        goStep3();
    } else {
        document.getElementById('marksError').classList.add('hidden');
    }

    const time = Number(document.getElementById('time_per_question').value);
    if(isNaN(time) || time <= 0){
        document.getElementById('timeError').textContent = "Time per question must be greater than 0.";
        document.getElementById('timeError').classList.remove('hidden');
        valid = false;
        goStep3();
    } else {
        document.getElementById('timeError').classList.add('hidden');
    }





    if(selectedMcqs.size === 0){
        document.getElementById('step2Error').classList.remove('hidden');
        valid = false;
        goStep2Back();
    } else {
        document.getElementById('step2Error').classList.add('hidden');
    }

    if(!valid) e.preventDefault();
});
function selectAllCategories() {
    document.querySelectorAll('.category-checkbox').forEach(checkbox => {
        if (!checkbox.checked) {
            checkbox.checked = true;
            selectedCategories.add(Number(checkbox.value));
            checkbox.closest('.category-card').querySelector('.checkmark').classList.remove('hidden');
        }
    });
}
function selectAllMCQs() {
    document.querySelectorAll('#mcqList input[type="checkbox"]').forEach(chk => {
        if (!chk.checked) {
            chk.checked = true;
            selectedMcqs.add(Number(chk.value));
        }
    });
    updateTotals();
    updateSelectedCount();
}

function updateSelectedCount() {
    const count = document.querySelectorAll('input[name="mcq_ids[]"]:checked').length;
    document.getElementById('selectedCount').innerText = "Selected MCQs: " + count;
}

// toggle to time per qution or time per quiz 
document.getElementById('time_per_question_radio').addEventListener('change',toggleTimeInputs);
document.getElementById('time_per_quiz_radio').addEventListener('change',toggleTimeInputs);
    let savedQuizTime = {{ $quiz->quiz_time ?? 0 }}; // in seconds
toggleTimeInputs();
function  toggleTimeInputs(){
    const isPerQuestion = document.getElementById('time_per_question_radio').checked;
    const isPerQuiz =document.getElementById('time_per_quiz_radio').checked;
    
    const timePerQuestionWrapper = document.getElementById('time_per_question_wrapper');
    const totalTimeWrapper = document.getElementById('total_time_wrapper');
     const totalTimeInput = document.getElementById('total_time_minutes');
    if(isPerQuiz){
        timePerQuestionWrapper.classList.add('hidden');
        // totalTimeWrapper.classList.remove('hidden');
         totalTimeInput.removeAttribute('readonly');
        totalTimeInput.classList.remove('bg-gray-100');
         if (savedQuizTime > 0) {
        document.getElementById('total_time_minutes').value = savedQuizTime / 60;
    }

    }
     if(isPerQuestion){
        timePerQuestionWrapper.classList.remove('hidden');
         totalTimeWrapper.classList.remove('hidden');
         totalTimeInput.setAttribute('readonly', true);
        totalTimeInput.classList.add('bg-gray-100');
        updateTotals();
    }

}
</script>
</x-app-layout>
