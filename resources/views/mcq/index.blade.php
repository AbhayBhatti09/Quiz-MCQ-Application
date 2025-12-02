<x-app-layout>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">

            <!-- Header + Add Button -->
            <div class="flex justify-between mb-4">
                <h2 class="text-xl font-bold">MCQ List</h2>
                <a href="{{ route('mcq.create') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    + Add MCQ
                </a>
            </div>

            <!-- Table -->
             
            <table id="mcqTable" class="table-auto w-full border mt-2">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2">No.</th>
                        <th class="border p-2">Category</th>
                        <th class="border p-2">Question</th>
                        <th class="border p-2">Correct Answer</th>
                        <th class="border p-2 w-40">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($mcqs as $index => $mcq)
                        <tr>
                            <td class="border p-2">{{ $index + 1 }}</td>
                            <td class="border p-2">{{ $mcq->category->title ?? 'NA' }}</td>
                            <td class="border p-2">{{ $mcq->question }}</td>
                            <td class="border p-2">{{ $mcq->correct_answer_full ?? $mcq->correct_answer }}</td>
                            <td class="border p-2">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('mcq.edit', $mcq->id) }}" class="btn btn-primary">
                                        Edit
                                    </a>

                                    <form action="{{ route('mcq.destroy', $mcq->id) }}" 
                                          method="POST" 
                                          class="inline-block delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="bg-red-600 text-white px-3 py-1 rounded deleteBtn"
                                                data-id="{{ $mcq->id }}">
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
$(document).ready(function () {

    // Initialize DataTable
    var table = $('#mcqTable').DataTable({
        pageLength: 5,
        ordering: true,
        searching: true
    });

    // Delete with SweetAlert using event delegation
    $('#mcqTable').on('click', '.deleteBtn', function () {

        let form = $(this).closest("form");

        Swal.fire({
            title: "Are you sure?",
            text: "This MCQ will be permanently deleted!",
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
</script>

</x-app-layout>
