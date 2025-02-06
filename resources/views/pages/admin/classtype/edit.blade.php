@extends('layouts.app')

@section('title', 'Edit Class Type')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Class Type</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/class-type/{{ $class_type->id }}" method="post">
                @csrf
                @method('put')
                <div class="form-group">
                    <label for="">Code</label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ $class_type->code }}" required>
                    @include('errors.inline', ['message' => $errors->first('code')])
                </div>
                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $class_type->name }}" required>
                    @include('errors.inline', ['message' => $errors->first('name')])
                </div>
                <div class="form-group">
                    <label for="">Companies</label>
                    <select class="multipleSelect" name="companies[]" multiple>
                        @foreach ($companies as $item)
                            <option value="{{ $item->id }}" {{ in_array($item->id, explode(',', $class_type->companies)) ? 'selected' : '' }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <a href="/class-type">Cancel</a>
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