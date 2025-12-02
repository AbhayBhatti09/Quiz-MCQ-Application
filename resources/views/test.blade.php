<x-app-layout>

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush

<div class="p-6">
    <table id="attemptTable" class="table-auto w-full border">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>1</td><td>Abhay</td></tr>
            <tr><td>2</td><td>Rahul</td></tr>
            <tr><td>3</td><td>John</td></tr>
        </tbody>
    </table>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    $('#attemptTable').DataTable();
});
</script>
@endpush

</x-app-layout>
