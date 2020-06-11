@extends('layouts.app')

@section('title', 'Create Particulars')

@section('content')
    <div class="container">
        <h4 class="mb-4">Create {{ strtoupper($type) }} Particulars</h4>

        <form action="/particular" method="post">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">
            <div class="form-group">
                <label for="">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                @include('errors.inline', ['message' => $errors->first('name')])
            </div>
            <a href="/particular">Cancel</a>
            <input type="submit" class="btn btn-primary float-right" value="Save">
        </form>
    </div>
@endsection