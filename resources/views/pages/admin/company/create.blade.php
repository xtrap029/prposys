@extends('layouts.app')

@section('title', 'Create Company')

@section('content')
    <div class="container">
        <h4 class="mb-4">Create Company</h4>

        <form action="/company" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="">Code</label>
                <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code') }}" required>
                @include('errors.inline', ['message' => $errors->first('code')])
            </div>
            <div class="form-group">
                <label for="">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                @include('errors.inline', ['message' => $errors->first('name')])
            </div>
            <div class="form-group">
                <label for="">Logo</label>
                <input type="file" name="logo" class="form-control-file @error('logo') is-invalid @enderror">
                @include('errors.inline', ['message' => $errors->first('logo')])
            </div>
            <a href="/company">Cancel</a>
            <input type="submit" class="btn btn-primary float-right" value="Save">
        </form>
    </div>
@endsection