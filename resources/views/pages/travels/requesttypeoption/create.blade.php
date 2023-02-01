@extends('layouts.app-travels')

@section('title', 'Create Request Type Option')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Request Type Option</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/travels-request-type-option" method="post">
                @csrf
                <div class="form-group">
                    <label for="">Option Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                    @include('errors.inline', ['message' => $errors->first('name')])
                </div>
                <div class="form-group">
                    <label for="">Request Type</label>
                    <select name="travels_request_type_id" class="form-control">
                        @foreach ($request_types as $data)
                            <option value="{{ $data->id }}" {{ old('travels_request_type_id') ? 'selected' : '' }}>{{ $data->name }}</option>
                        @endforeach
                    </select>
                </div>
                <a href="/travels-request-type-option">Cancel</a>
                <input type="submit" class="btn btn-primary float-right" value="Save">
            </form>
        </div>
    </section>
@endsection