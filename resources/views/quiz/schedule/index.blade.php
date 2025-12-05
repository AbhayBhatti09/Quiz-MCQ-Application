<x-app-layout>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">

            <h2 class="text-xl font-bold">Schedule List</h2>

            <div class="flex justify-between mb-4">
                <a href="{{route('schedule.create')}}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    + Assign Quiz
                </a>

                <a href="{{ route('quiz.setting.index') }}" class="btn btn-light">Back</a>
            </div>

            <table id="usersTable" class="table-auto w-full border mt-2">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2">No.</th>
                        <th class="border p-2">User Name</th>
                        <th class="border p-2">Quiz Name</th>
                        <th class="border p-2">Quiz Time</th>
                        <th class="border p-2">Created At</th>
                        <th class="border p-2">Processed</th>
                        <th class="border p-2">Send Link</th>
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
                                @if($schedule->is_link_sent)
                                    <button 
                                        class="resendLinkBtn btn btn-warning text-white px-3 py-1 "
                                        data-id="{{ $schedule->id }}">
                                        Re-send
                                    </button>
                                @else
                                    <button 
                                        class="sendLinkBtn btn btn-success text-white px-3 py-1 "
                                        data-id="{{ $schedule->id }}">
                                        Send 
                                    </button>
                                @endif
                            </td>

                            <td class="border p-2 text-center">
                                <div class="flex gap-2 justify-center">

                                    <a href="{{ route('schedule.edit', $schedule->id) }}"
                                        class="bg-blue-600 text-white px-3 py-1 rounded">
                                        Edit
                                    </a>

                                    <form action="{{ route('schedule.delete', $schedule->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="deleteBtn bg-red-600 text-white px-3 py-1 rounded">
                                            Delete
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center p-4">No Schedule Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>


<!-- Page Loader -->
<div id="pageLoader" class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-transparent" style="z-index: 999; display: none;">
    <div class="spinner-border text-primary" role="status" style="width: 4rem; height: 4rem; border-width: 0.5rem;">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>




<!-- Scripts -->
<script>
$(document).ready(function () {

    // DataTable Init
    $('#usersTable').DataTable({
        pageLength: 5,
        ordering: true,
        searching: true
    });

    // DELETE with SweetAlert
    $('#usersTable').on('click', '.deleteBtn', function () {
        let form = $(this).closest("form");

        Swal.fire({
            title: "Are you sure?",
            text: "This schedule will be permanently deleted!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#e3342f",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, Delete"
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });

    // PROCESS Toggle
    $('.processToggle').on('change', function () {
        let id = $(this).data('id');
        let status = $(this).is(':checked') ? 1 : 0;

        $.post("{{ route('schedule.toggle') }}", {
            id: id,
            status: status,
            _token: "{{ csrf_token() }}"
        });
    });

    // SEND LINK
    $(document).on("click", ".sendLinkBtn", function () {
        sendLink($(this));
    });

    // RESEND LINK
    $(document).on("click", ".resendLinkBtn", function () {
        sendLink($(this));
    });

    // ---- FUNCTION ----
    function sendLink(btn) {
        let id = btn.data('id');

        Swal.fire({
            title: "Send Quiz Link?",
            text: "User will receive email with login details.",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Send"
        }).then((result) => {

            if (result.isConfirmed) {

              document.getElementById('pageLoader').style.display = 'flex'; // SHOW LOADER

                $.ajax({
                    url: "{{ route('schedule.sendLink') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (res) {

                       document.getElementById('pageLoader').style.display = 'none'; // HIDE LOADER

                        if (res.status === "sent") {

                            Swal.fire("Email Sent!", "User received quiz link.", "success");

                            btn.replaceWith(`
                                <button class="resendLinkBtn btn btn-warning text-white px-3 py-1 "
                                    data-id="${id}">
                                    Re-send
                                </button>
                            `);
                        }
                    },
                    error: function () {
                       document.getElementById('pageLoader').style.display = 'none';
                        Swal.fire("Error", "Email could not be sent. Try again.", "error");
                    }
                });
            }
        });
    }

});
</script>

</x-app-layout>
