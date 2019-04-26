@extends('layouts.app')
@section('head-scripts') 
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables/datatables.min.css') }}">
@endsection

@section('content')
<div class="container">
    <a href="{{ route('home') }}" class="btn btn-success mb-3 text-white"><b>Take Snapshot</b> <i class="fa fa-plus"></i></a>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Database Snapshot List</div>
                <div class="card-body">
                   
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif                    
                    
                    @if(session()->has('db-snapshot-delete'))
                        <div class="alert alert-success" role="alert">
                            {{ session()->get('db-snapshot-delete') }}
                        </div>
                    @endif   

                    <table id="db-snapshot-list" class="table table-striped table-bordered server-side">
                        <thead>
                            <tr>                                
                                <th>Name</th>
                                <th width="130px">Date</th>
                                <th width="50px">Size</th>
                                <th width="30px">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="abc"></div>
@endsection

@push('footer-scripts')

    <script type="text/javascript" charset="utf8" src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>         
    <script type="text/javascript" charset="utf8" src="{{ asset('js/datatables/datatables.min.js') }}"></script>

    <script type="text/javascript">

        $(document).ready(function(){
            $.noConflict();
            // Datatable initialization
            $('#db-snapshot-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("DBSnapshotDatatable") }}',
                columns: [                                   
                    {data: 'name', name: 'name'},
                    {data: 'date', name: 'date'}, 
                    {data: 'size', name: 'size'}, 
                    {data: 'action', name: 'action'}, 
                ],
                "columnDefs": [   
                    {
                        "searchable": false,
                        "orderable": false,
                        "targets": 3,
                        "className": "text-center",
                    },
                ],
                "order": [
                    [1, "desc"]
                ]
            });

        }); 

    </script>

@endpush
