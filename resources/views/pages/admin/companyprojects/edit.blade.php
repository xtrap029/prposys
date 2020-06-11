@extends('layouts.app')

@section('title', 'Edit Company Project')

@section('content')
    <div class="container">
        <h4 class="mb-4">Edit Company Project</h4>

        <form action="/company-project/{{ $company_project->id }}" method="post">
            @csrf
            @method('put')
            <div class="form-group">
                <label for="">Project</label>
                <input type="text" class="form-control @error('project') is-invalid @enderror" name="project" value="{{ $company_project->project }}" required>
                @include('errors.inline', ['message' => $errors->first('project')])
            </div>
            <a href="/company-project/{{ $company_project->company_id }}">Cancel</a>
            <input type="submit" class="btn btn-primary float-right" value="Save">
        </form>
    </div>
@endsection