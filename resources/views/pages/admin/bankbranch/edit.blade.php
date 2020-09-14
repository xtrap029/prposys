@extends('layouts.app')

@section('title', 'Edit Branch')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Bank Branch</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/bank-branch/{{ $bank_branch->id }}" method="post">
                @csrf
                @method('put')
                <div class="form-group">
                    <label for="">Branch Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $bank_branch->name }}" required>
                    @include('errors.inline', ['message' => $errors->first('name')])
                </div>
                <div class="form-group">
                    <label for="">Bank</label>
                    <select name="bank_id" class="form-control">
                        @foreach ($banks as $data)
                            <option value="{{ $data->id }}" {{ $bank_branch->bank_id == $data->id ? 'selected' : '' }}>{{ $data->name }}</option>
                        @endforeach
                    </select>
                </div>
                <a href="/bank">Cancel</a>
                <input type="submit" class="btn btn-primary float-right ml-2" value="Save">
                <input type="submit" class="btn btn-danger float-right" value="Delete" onclick="return confirm('Are you sure?')" form="delete-bank">
            </form>
            <form action="/bank-branch/{{ $bank_branch->id }}" method="post" class="d-inline-block" id="delete-bank">
                @csrf
                @method('delete')
            </form>
        </div>
    </section>
@endsection