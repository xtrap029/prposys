@extends('layouts.app')

@section('title', 'Edit Company Project')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Company Project</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
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
    </section>
@endsection