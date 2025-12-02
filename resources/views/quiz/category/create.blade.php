<x-app-layout>
    <div class="max-w-3xl mx-auto p-6">
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Add Category</h2>

                <a href="{{ route('category.index') }}" class="btn btn-light">
                    Back
                </a>
            </div>

            <form action="{{ route('category.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>Category Title</label>
                    <input type="text" name="title" 
                        class="border w-full p-2 rounded"
                        value="{{ old('title') }}">

                    @error('title')
                        <p class="text-red-500 text-sm ">{{ $message }}</p>
                    @enderror
                </div>

                <button class="btn btn-primary">Save Category</button>
            </form>

        </div>
    </div>
</x-app-layout>
