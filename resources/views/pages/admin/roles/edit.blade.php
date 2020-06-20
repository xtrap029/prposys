@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Role</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/role/{{ $role->id }}" method="post">
                @csrf
                @method('put')
                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $role->name }}" required>
                    @include('errors.inline', ['message' => $errors->first('name')])
                </div>
                <a href="/role">Cancel</a>
                <input type="submit" class="btn btn-primary float-right" value="Save">
            </form>
        </div>
    </section>
@endsection