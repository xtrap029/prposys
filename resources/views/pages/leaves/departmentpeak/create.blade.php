@extends('layouts.app-leaves')

@section('title', 'Create Department Peak')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create {{ $department->name }} Peak</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/leaves-department-peak/{{ $department->id }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="">Date From</label>
                    <input type="date" class="form-control @error('date_from') is-invalid @enderror" name="date_from" value="{{ old('date_from') }}" required>
                    @include('errors.inline', ['message' => $errors->first('date_from')])
                </div>
                <div class="form-group">
                    <label for="">Date To</label>
                    <input type="date" class="form-control @error('date_to') is-invalid @enderror" name="date_to" value="{{ old('date_to') }}" required>
                    @include('errors.inline', ['message' => $errors->first('date_to')])
                </div>
                <div class="form-group">
                    <label for="">Remarks</label>
                    <textarea name="remarks" rows="3" class="form-control @error('remarks') is-invalid @enderror" required>{{ old('remarks') }}</textarea>
                    @include('errors.inline', ['message' => $errors->first('remarks')])
                </div>
                <a href="/leaves-department-peak/{{ $department->id }}">Cancel</a>
                <input type="submit" class="btn btn-primary float-right" value="Save">
            </form>
        </div>
    </section>
@endsection