@extends('layouts.app')

@section('title', 'Edit Particular')

@section('content')
    <div class="container">
        <h4 class="mb-4">Edit {{ strtoupper($particular->type) }} Particular</h4>

        <form action="/particular/{{ $particular->id }}" method="post">
            @csrf
            @method('put')
            <div class="form-group">
                <label for="">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $particular->name }}" required>
                @include('errors.inline', ['message' => $errors->first('name')])
            </div>
            <a href="/particular">Cancel</a>
            <input type="submit" class="btn btn-primary float-right" value="Save">
        </form>
    </div>
@endsection