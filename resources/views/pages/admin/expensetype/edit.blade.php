@extends('layouts.app')

@section('title', 'Edit Expense Type')

@section('content')
    <div class="container">
        <h4 class="mb-4">Edit Expense Type</h4>

        <form action="/expense-type/{{ $expense_type->id }}" method="post">
            @csrf
            @method('put')
            <div class="form-group">
                <label for="">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $expense_type->name }}" required>
                @include('errors.inline', ['message' => $errors->first('name')])
            </div>
            <a href="/expense-type">Cancel</a>
            <input type="submit" class="btn btn-primary float-right" value="Save">
        </form>
    </div>
@endsection