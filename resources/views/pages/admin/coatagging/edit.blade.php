@extends('layouts.app')

@section('title', 'Edit COA Tagging')

@section('content')
    <div class="container">
        <h4 class="mb-4">Edit COA Tagging</h4>

        <form action="/coa-tagging/{{ $coa_tagging->id }}" method="post">
            @csrf
            @method('put')
            <div class="form-group">
                <label for="">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $coa_tagging->name }}" required>
                @include('errors.inline', ['message' => $errors->first('name')])
            </div>
            <a href="/coa-tagging">Cancel</a>
            <input type="submit" class="btn btn-primary float-right" value="Save">
        </form>
    </div>
@endsection