<x-app-layout>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">
            <h2 class="text-xl font-bold mb-2">Quiz List</h2>

            <!-- Header + Add Button -->
            <div class="flex justify-between mb-4">
                <a href="{{ route('quiz.setting.create') }}" 
                   class="bg-blue-600 text-white px-3 py-2 rounded-md hover:bg-blue-700">
                    + Create Quiz
                </a>
                <a href="{{ route('quiz.setting.index') }}" class="btn btn-light">
                    Back
                </a>
            </div>

            <!-- Quiz Table -->
            <table id="quizTable" class="table-auto w-full border mt-2">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2">No.</th>
                        <th class="border p-2">Title</th>
                        <th class="border p-2">Categories</th>
                        <th class="border p-2">Active Exam Status</th>
                        <th class="border p-2">Created At</th>
                        <th class="border p-2 w-48">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($quizzes as $index => $quiz)
                    <tr>
                        <td class="border p-2">{{ $index + 1 }}</td>
                        <td class="border p-2">{{ $quiz->title }}</td>

                        <td class="border p-2">
                            @forelse($quiz->categories as $category)
                                <span class="inline-block bg-gray-200 text-gray-700 px-2 py-1 rounded-full text-xs mr-1 mb-1">
                                    {{ $category->title }}
                                </span>
                            @empty
                                NA
                            @endforelse
                        </td>

                        <!-- ACTIVE STATUS TOGGLE -->
                        <td class="border p-2 text-center">
                            <label class="inline-flex items-center cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    class="activeToggle"
                                    data-id="{{ $quiz->id }}"
                                    {{ $quiz->is_active ? 'checked' : '' }}
                                >
                                <span class="ml-2 text-sm">
                                    {{ $quiz->is_active ? 'Yes' : 'No' }}
                                </span>
                            </label>
                        </td>

                        <td class="border p-2">{{ $quiz->created_at->format('d M Y h:i A') }}</td>

                        <td class="border p-2">
                            <div class="flex gap-2">
                                <a href="{{ route('quiz.setting.view', $quiz->id) }}" 
                                   class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                    View
                                </a>
                                <a href="{{ route('quiz.setting.edit', $quiz->id) }}" 
                                   class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                    Edit
                                </a>

                                <form action="{{ route('quiz.setting.destroy', $quiz->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="deleteBtn bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>

<!-- Scripts -->
<script>
$(document).ready(function() {

    $('#quizTable').DataTable({
        pageLength: 5,
        ordering: true,
        searching: true
    });
// delegated event will catch dynamically added checkboxes too
$(document).on('change', '.activeToggle', function () {
    const $checkbox = $(this);
    const quizId = $checkbox.data('id');
    const status = $checkbox.is(':checked') ? 1 : 0;
    const textLabel = $checkbox.closest('label').find('span');
    const url = "{{ route('quiz.setting.toggle') }}";
    const token = "{{ csrf_token() }}";

    $.ajax({
        url: url,
        method: 'POST',
        data: {
            _token: token,
            quiz_id: quizId,
            status: status
        },
        beforeSend() {
            $checkbox.prop('disabled', true);
        },
        success() {
            textLabel.text(status ? 'Yes' : 'No');
            Swal.fire({ icon: 'success', title: 'Quiz status updated!', timer: 800, showConfirmButton: false });
        },
        error() {
            // revert checkbox on error
            $checkbox.prop('checked', !status);
            Swal.fire({ icon: 'error', title: 'Update failed', text: 'Please try again' });
        },
        complete() {
            $checkbox.prop('disabled', false);
        }
    });
});


    // DELETE CONFIRMATION
    document.querySelectorAll(".deleteBtn").forEach(button => {
        button.addEventListener("click", function () {
            let form = this.closest("form");

            Swal.fire({
                title: "Are you sure?",
                text: "This quiz will be permanently deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#e3342f",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, Delete",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

});
</script>

</x-app-layout>
