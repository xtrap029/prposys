@extends('layouts.app-travels')

@section('title', 'Create Request Type')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Request Type</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/travels-request-type" method="post">
                @csrf
                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                    @include('errors.inline', ['message' => $errors->first('name')])
                </div>
                <a href="/travels-request-type">Cancel</a>
                <input type="submit" class="btn btn-primary float-right" value="Save">
            </form>
        </div>
    </section>
@endsection