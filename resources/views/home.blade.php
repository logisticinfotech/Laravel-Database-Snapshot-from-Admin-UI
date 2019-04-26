@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">New Snapshot</div>

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
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    @if(session()->has('db-snapshot-done'))
                        <div class="alert alert-success" role="alert">
                            {{ session()->get('db-snapshot-done') }}
                        </div>
                    @endif

                    <form method="post" action="{{ route('CreateDBSnapshot') }}">
                        @csrf
                        <div class="form-group">
                           <label for="snapshot_name">Snapshot Name (Whithspace Not allow)</label>
                           <input type="text" required name="snapshot_name" value="{{ old('snapshot_name') }}" class="form-control" id="snapshot_name" placeholder="Enter Snapshot Name">                         
                         </div>
                        <button type="submit" class="btn btn-success btn-block"><b>Take Database Snapshot</b> <i class="fa fa-camera"></i></button>                   
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
