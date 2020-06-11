@extends('layouts.app')

@section('title', 'Create Expense Type')

@section('content')
    <div class="container">
        <h4 class="mb-4">Create Expense Type</h4>

        <form action="/expense-type" method="post">
            @csrf
            <div class="form-group">
                <label for="">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                @include('errors.inline', ['message' => $errors->first('name')])
            </div>
            <a href="/expense-type">Cancel</a>
            <input type="submit" class="btn btn-primary float-right" value="Save">
        </form>
    </div>
@endsection