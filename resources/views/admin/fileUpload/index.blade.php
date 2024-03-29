@extends('layouts.hr.app')

@section('title','File Upload')

@section('content')
@include('layouts.basic.tableHead',["table_title" => "File Upload List", "url" => "/file-upload/create"])
<table class="unit_table mx-auto drmDataTable">
    <thead>
        <tr class="table_title" style="background-color: #0f5288;">
        <th scope="col" class="ps-4">S.N</th>
            <th scope="col">File Category</th>
            <th scope="col">Employee</th>                   
            <th scope="col">Uploaded By</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($fileUploads as $fileUpload)
            <tr>
                <th scope="row" class="ps-4 text-dark">{{ $loop->iteration }}</th>
                <td>{{ $fileUpload->fileCategory->category_name }}</td>
                <td>{{ $fileUpload->employee->first_name.' '.substr($fileUpload->employee->middle_name,0,1).' '.$fileUpload->employee->last_name }}</td>
                <td>{{ $fileUpload->uploader->first_name.' '.substr($fileUpload->uploader->middle_name,0,1).' '.$fileUpload->uploader->last_name }}</td>

                <td class="text-center">
                <a href="/file-upload/download/{{ $fileUpload->id }}"><i class="fa fa-download" aria-hidden="true"></i></a> 
                | 
                <form id="deleteFile" action="/file-upload/{{ $fileUpload->id }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDelete()" class="delete border-0 confirmDelete"><i class="fas fa-trash-alt action"></i></button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <th colspan=5 class="text-center text-dark">No Uploaded File Found</th>
        </tr>
        @endforelse
    </tbody>
</table>
{{-- $fileUploads->links() --}}

@include('layouts.basic.tableFoot')
@endsection



@section('scripts')
<script>
    $(document).ready(function() {
        $('.drmDataTable').DataTable();
    })

    function confirmDelete(e)
    {
        Swal.fire({
            title: `Are you sure you want to delete this file?`,
            text: "If you delete it, file will also get deleted and cannot be undone.",
            icon: "warning",
            showCancelButton: true,
        })
        .then((res) => {
            console.log(res.isConfirmed);
            if(res.isConfirmed === true)
            {
                $('#deleteFile').submit();
            }
        });
    }
</script>
@endsection