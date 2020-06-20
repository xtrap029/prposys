@extends('layouts.app')

@section('title', 'Create Company Project')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Company Project</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
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
    </section>
@endsection