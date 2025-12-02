<x-app-layout>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">

            <div class="flex justify-between mb-4">
                <h2 class="text-xl font-bold">Category List</h2>

                <a href="{{ route('category.create') }}" 
                   class="bg-blue-600 text-white px-3 py-2 rounded">
                    + Add Category
                </a>
            </div>

            <table id="categoryTable" class="table-auto w-full border mt-2">
                <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">No.</th>
                    <th class="border p-2">Category Title</th>
                    <th class="border p-2">Created At</th>
                    <th class="border p-2">Action</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($categories as $index => $cat)
                    <tr>
                        <td class="border p-2">{{ $index + 1 }}</td>
                        <td class="border p-2">{{ $cat->title }}</td>
                        <td class="border p-2">
                            {{ $cat->created_at->timezone('Asia/Kolkata')->format('d M Y h:i A') }}
                        </td>
                       <td class="border p-2">
                            <div class="flex items-center gap-2">

                                <a href="{{ route('category.edit', $cat->id) }}" 
                                class="btn btn-primary">
                                    Edit
                                </a>

                                <form action="{{ route('category.destroy', $cat->id) }}" 
                                    method="POST"
                                    class="inline-block delete-form">
                                    @csrf
                                    @method('DELETE')

                                    <button 
                                        type="button" 
                                        class="bg-red-600 text-white px-3 py-1 rounded deleteBtn"
                                        data-id="{{ $cat->id }}">
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

<script>
$(document).ready(function () {
    $('#categoryTable').DataTable({
        "pageLength": 5,
        "ordering": true,
        "searching": true
    });
});
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".deleteBtn").forEach(button => {
        button.addEventListener("click", function () {

            let form = this.closest("form");

            Swal.fire({
                title: "Are you sure?",
                text: "This category will be permanently deleted!",
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
