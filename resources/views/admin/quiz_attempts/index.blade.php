<x-app-layout>


<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">
            <div class="flex justify-between mb-4">
                <h2 class="text-xl font-bold">Quiz Attempt to Users </h2>
            </div>

            <table id="attemptTable" class="table-auto w-full border mt-2">
                <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">No.</th>
                   
                    <th class="border p-2">Quiz</th>
                    @if( auth()->user()->role_id==1)
                     <th class="border p-2">User Name</th>
                     <th class="border p-2">Total Questions</th>
                    <th class="border p-2">Mark Per Question</th>
                    <th class="border p-2">Total Marks</th>
                    <th class="border p-2">Correct Questions</th>
                    <th class="border p-2">candidate Marks</th>
                    @endif
                    <th class="border p-2">Attempted On</th>
                    <th class="border p-2">View Attempt</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($attempts as $index => $attempt)
                    <tr class="">
                        <td class="border p-2">{{ $index + 1 }}</td>
                        
                        <td class="border p-2">{{ $attempt->quiz->title ?? 'NA' }}</td>
                        
                         @if( auth()->user()->role_id==1)
                         <td class="border p-2">{{ $attempt->user->name ?? '' }}</td>
                        <td class="border p-2">{{ $attempt->quiz->question_count ?? 'NA'}}</td>
                        <td class="border p-2">{{ $attempt->quiz->marks_per_question ?? 'NA'}}</td>
                        <td class="border p-2">{{ $attempt->quiz->total_marks ?? 'NA'}}</td>
                        <td class="border p-2">{{ $attempt->score ?? 'NA'}}</td>
                        <td class="border text-center p-2">
                            {{ $attempt->scoremarks ?? 0 }}
                        </td>
                        @endif
                        <td class="border p-2">{{ $attempt->created_at->timezone('Asia/Kolkata')->format('d M Y h:i A') }}</td>
                    <td class="border p-2 flex space-x-2">
                    <a href="{{ route('admin.attempts.show', $attempt->id) }}" class="btn btn-primary">View</a>
                    @if(auth()->user()->role_id == 1)
                        <button class="deleteBtn bg-red-600 text-white px-2 py-1 rounded" data-id="{{ $attempt->id }}">Delete</button>
                    @endif
                </td>


                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {
    const table = $('#attemptTable').DataTable({
        "pageLength": 5,
        "ordering": true,
        "searching": true
    });

    // Event delegation on correct table
    $('#attemptTable').on('click', '.deleteBtn', function () {
        let attemptId = $(this).data('id');
        let row = $(this).closest('tr'); // get the row to remove later

        Swal.fire({
            title: "Are you sure?",
            text: "This attempt will be permanently deleted!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#e3342f",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, Delete",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/attempts/' + attemptId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire('Deleted!', 'The attempt has been deleted.', 'success');
                        // Remove the row instantly without reloading
                        table.row(row).remove().draw();
                    },
                    error: function(err) {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                });
            }
        });
    });
});

</script>

</x-app-layout>
