@extends('layouts.app')

@section('title', 'Create Company Project')

@section('content')
    <div class="container">
        <h4 class="mb-4">Create Company Project</h4>

        <form action="/company-project/{{ $company->id }}" method="post">
            @csrf
            <div class="form-group">
                <label for="">Project</label>
                <input type="text" class="form-control @error('project') is-invalid @enderror" name="project" value="{{ old('project') }}" required>
                @include('errors.inline', ['message' => $errors->first('project')])
            </div>
            <a href="/company-project/{{ $company->id }}">Cancel</a>
            <input type="submit" class="btn btn-primary float-right" value="Save">
        </form>
    </div>
@endsection