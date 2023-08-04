@extends('layouts.app')

@section('title', 'Edit Cost Type')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Cost Type</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/cost-type/{{ $cost_type->id }}" method="post">
                @csrf
                @method('put')
                <div class="form-group">
                    <label for="">Control No.</label>
                    <input type="text" class="form-control @error('control_no') is-invalid @enderror" name="control_no" value="{{ $cost_type->control_no }}" required>
                    @include('errors.inline', ['message' => $errors->first('control_no')])
                </div>
                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $cost_type->name }}" required>
                    @include('errors.inline', ['message' => $errors->first('name')])
                </div>
                <div class="form-group">
                    <label for="">Description</label>
                    <input type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ $cost_type->description }}" required>
                    @include('errors.inline', ['message' => $errors->first('description')])
                </div>
                <a href="/cost-type">Cancel</a>
                <input type="submit" class="btn btn-primary float-right" value="Save">
            </form>
        </div>
    </section>
@endsection