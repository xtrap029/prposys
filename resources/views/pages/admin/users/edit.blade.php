@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
    <div class="container">
        <h4 class="mb-4">Edit Role</h4>

        <form action="/role/{{ $role->id }}" method="post">
            @csrf
            @method('put')
            <div class="form-group">
                <label for="">Name</label>
                <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name" value="{{ $role->name }}">
                @include('errors.inline', ['message' => $errors->first('name')])
            </div>
            <a href="/role">Cancel</a>
            <input type="submit" class="btn btn-success float-right" value="Save">
        </form>
    </div>
@endsection