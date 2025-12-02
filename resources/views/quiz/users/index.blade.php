<x-app-layout>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">

            <!-- Header + Add Button -->
            <div class="flex justify-between mb-4">
                <h2 class="text-xl font-bold">Users List</h2>

                <a href="{{ route('users.create') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    + Add User
                </a>
            </div>

            <!-- Table -->
            <table id="usersTable" class="table-auto w-full border mt-2">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2">No.</th>
                        <th class="border p-2">Name</th>
                        <th class="border p-2">Email</th>
                        <th class="border p-2">Created At</th>
                        <th class="border p-2 w-40">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $index => $user)
                        <tr>
                            <td class="border p-2">{{ $index + 1 }}</td>
                            <td class="border p-2">{{ $user->name }}</td>
                            <td class="border p-2">{{ $user->email }}</td>
                            <td class="border p-2">{{ $user->created_at->format('d M, Y') }}</td>

                            <td class="border p-2">
                                <div class="flex items-center gap-2">

                                    <!-- Edit -->
                                    <a href="{{ route('users.edit', $user->id) }}" 
                                       class="btn btn-primary">
                                        Edit
                                    </a>

                                    <!-- Delete -->
                                    <form action="{{ route('users.destroy', $user->id) }}" 
                                          method="POST" 
                                          class="inline-block delete-form">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button"
                                                class="bg-red-600 text-white px-3 py-1 rounded deleteBtn"
                                                data-id="{{ $user->id }}">
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
    $('#usersTable').DataTable({
        pageLength: 5,
        ordering: true,
        searching: true
    });

    // SweetAlert Delete
    $('#usersTable').on('click', '.deleteBtn', function () {

        let form = $(this).closest("form");

        Swal.fire({
            title: "Are you sure?",
            text: "This user will be permanently deleted!",
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
