<x-app-layout>
    <div class="max-w-3xl mx-auto p-6">
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">
           <div class="flex justify-between items-center mb-4">
    <h2 class="text-xl font-bold">Create MCQ</h2>

    <a href="{{ route('mcq.index') }}"
       class="btn btn-light">
        Back
    </a>
</div>
            <form action="{{ route('mcq.store') }}" method="POST">
                @csrf

              <div class="mb-3">
                    <label>Question</label>
                    <textarea name="question" class="border rounded w-full p-2" rows="4">{{ old('question') }}</textarea>

                    @error('question')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Category</label>
                    <select name="category_id" class="border rounded w-full p-2">
                        <option value="">Select Category</option>

                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->title }}
                            </option>
                        @endforeach
                    </select>

                    @error('category_id')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>



                <div class="mb-3">
                    <label>Option A</label>
                    <input type="text" name="option_a" class="border rounded w-full p-2"
                        value="{{ old('option_a') }}">

                    @error('option_a')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Option B</label>
                    <input type="text" name="option_b" class="border rounded w-full p-2"
                        value="{{ old('option_b') }}">

                    @error('option_b')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Option C</label>
                    <input type="text" name="option_c" class="border rounded w-full p-2"
                        value="{{ old('option_c') }}">

                    @error('option_c')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Option D</label>
                    <input type="text" name="option_d" class="border rounded w-full p-2"
                        value="{{ old('option_d') }}">

                    @error('option_d')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Correct Answer</label>
                    <select name="correct_answer" class="border rounded w-full p-2">
                        <option value="">Select</option>
                        <option value="a" {{ old('correct_answer') == 'a' ? 'selected' : '' }}>A</option>
                        <option value="b" {{ old('correct_answer') == 'b' ? 'selected' : '' }}>B</option>
                        <option value="c" {{ old('correct_answer') == 'c' ? 'selected' : '' }}>C</option>
                        <option value="d" {{ old('correct_answer') == 'd' ? 'selected' : '' }}>D</option>
                    </select>

                    @error('correct_answer')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <button class="btn btn-primary">Save MCQ</button>
            </form>
         </div>
    </div>
</x-app-layout>
