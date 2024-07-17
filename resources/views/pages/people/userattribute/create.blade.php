@extends('layouts.app-people')

@section('title', 'Create User Attribute')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create User Attribute</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/user-attribute" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="">Order</label>
                    <input type="number" class="form-control @error('order') is-invalid @enderror" name="order" step="1" value="{{ old('order') }}" required>
                    @include('errors.inline', ['message' => $errors->first('order')])
                </div>
                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                    @include('errors.inline', ['message' => $errors->first('name')])
                </div>
                <a href="/user-attribute">Cancel</a>
                <input type="submit" class="btn btn-primary float-right" value="Save">
            </form>
        </div>
    </section>
@endsection