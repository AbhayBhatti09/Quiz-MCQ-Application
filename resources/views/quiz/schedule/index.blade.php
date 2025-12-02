<x-app-layout>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">

            <!-- Header + Add Button -->
            <div class="flex justify-between mb-4">
                <h2 class="text-xl font-bold">Schedule List</h2>

                <a href="{{route('schedule.create')}}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    + Assign Quiz
                </a>
            </div>

            <!-- Table -->
            <table id="usersTable" class="table-auto w-full border mt-2">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2">No.</th>
                        <th class="border p-2">User Name</th>
                        <th class="border p-2">Quiz Name</th>
                        <th class="border p-2">Quiz Time</th>
                        <th class="border p-2">Created At</th>
                        <th class="border p-2">Processed</th>
                        <th class="border p-2 w-40">Actions</th>
                    </tr>
                </thead>

               <tbody>
                    @forelse ($schedules as $key => $schedule)
                        <tr>
                            <td class="border p-2">{{ $key + 1 }}</td>

                            <td class="border p-2">{{ $schedule->user->name }}</td>

                            <td class="border p-2">{{ $schedule->quiz->title }}</td>

                            <td class="border p-2">
                                {{ \Carbon\Carbon::parse($schedule->schedule_at)->format('d M Y, h:i A') }}
                            </td>

                            <td class="border p-2">
                                {{ $schedule->created_at->format('d M Y') }}
                            </td>
                            <td class="border p-2 text-center">
                                <input type="checkbox"
                                    class="processToggle"
                                    data-id="{{ $schedule->id }}"
                                    {{ $schedule->is_processed ? 'checked' : '' }}>
                            </td>
                            <td class="border p-2 text-center">
                                <form action="{{ route('schedule.delete', $schedule->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="deleteBtn bg-red-600 text-white px-3 py-1 rounded">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center p-4">No Schedule Found</td>
                        </tr>
                    @endforelse
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

    $('.processToggle').on('change', function () {

    let id = $(this).data('id');
    let status = $(this).is(':checked') ? 1 : 0;

    $.ajax({
        url: "{{ route('schedule.toggle') }}",
        type: "POST",
        data: {
            id: id,
            status: status,
            _token: "{{ csrf_token() }}"
        },
        success: function (res) {
            console.log("Updated");
        }
    });

});


});
</script>
</x-app-layout>
