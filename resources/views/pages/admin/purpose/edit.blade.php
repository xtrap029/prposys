@extends('layouts.app')

@section('title', 'Edit Purpose')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Purpose</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/purpose/{{ $purpose->id }}" method="post">
                @csrf
                @method('put')
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="">Code</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ $purpose->code }}" required>
                        @include('errors.inline', ['message' => $errors->first('code')])
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $purpose->name }}" required>
                        @include('errors.inline', ['message' => $errors->first('name')])
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Description</label>
                        <input type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ $purpose->description }}" required>
                        @include('errors.inline', ['message' => $errors->first('description')])
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Companies</label>
                    <select class="multipleSelect" name="companies[]" multiple>
                        @foreach ($companies as $item)
                            <option value="{{ $item->id }}" {{ in_array($item->id, explode(',', $purpose->companies)) ? 'selected' : '' }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <a href="/purpose">Cancel</a>
                <input type="submit" class="btn btn-primary float-right" value="Save">
            </form>
        </div>
    </section>
@endsection

@section('script')
    <script type="text/javascript">
        $(function() {
            $('.multipleSelect').fastselect({
                placeholder: 'Choose your companies here'
            })
        })
    </script>
@endsection