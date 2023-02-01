@extends('layouts.app-travels')

@section('title', 'Edit Request Type Option')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Request Type Option</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/travels-request-type-option/{{ $request_type_option->id }}" method="post">
                @csrf
                @method('put')
                <div class="form-group">
                    <label for="">Option Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $request_type_option->name }}" required>
                    @include('errors.inline', ['message' => $errors->first('name')])
                </div>
                <div class="form-group">
                    <label for="">Request Type</label>
                    <select name="travels_request_type_id" class="form-control">
                        @foreach ($request_types as $data)
                            <option value="{{ $data->id }}" {{ $request_type_option->travels_request_type_id == $data->id ? 'selected' : '' }}>{{ $data->name }}</option>
                        @endforeach
                    </select>
                </div>
                <a href="/travels-request-type">Cancel</a>
                <input type="submit" class="btn btn-primary float-right ml-2" value="Save">
                <input type="submit" class="btn btn-danger float-right" value="Delete" onclick="return confirm('Are you sure?')" form="delete-request-type-option">
            </form>
            <form action="/travels-request-type-option/{{ $request_type_option->id }}" method="post" class="d-inline-block" id="delete-request-type-option">
                @csrf
                @method('delete')
            </form>
        </div>
    </section>
@endsection