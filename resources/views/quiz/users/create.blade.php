<x-app-layout>
    <div class="max-w-3xl mx-auto p-6">
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Create User</h2>

                <a href="{{ route('users.index') }}" class="btn btn-light">
                    Back
                </a>
            </div>

            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>Name<span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="border rounded w-full p-2"
                           value="{{ old('name') }}">

                    @error('name')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Email<span class="text-red-500">*</span></label>
                    <input type="email" name="email" class="border rounded w-full p-2"
                           value="{{ old('email') }}">

                    @error('email')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Password<span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" name="password" id="password"
                            class="border rounded w-full p-2 pr-10" value="{{ old('password') }}">

                        <i class="bi bi-eye-slash absolute right-3 top-3 cursor-pointer text-gray-600"
                        id="togglePassword"></i>
                    </div>

                    @error('password')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Confirm Password<span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="confirmPassword"
                            class="border rounded w-full p-2 pr-10" value="{{ old('password_confirmation') }}">

                        <i class="bi bi-eye-slash absolute right-3 top-3 cursor-pointer text-gray-600"
                        id="toggleConfirmPassword"></i>
                    </div>
                </div>


                <button class="btn btn-primary">Save User</button>
            </form>
        </div>
    </div>

    <script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);

    icon.addEventListener("click", function () {
        const isHidden = input.type === "password";
        input.type = isHidden ? "text" : "password";

        icon.classList.toggle("bi-eye-slash");
        icon.classList.toggle("bi-eye");
    });
}

// Create User Page
togglePassword("password", "togglePassword");
togglePassword("confirmPassword", "toggleConfirmPassword");

</script>
</x-app-layout>
