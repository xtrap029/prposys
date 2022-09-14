@extends('layouts.app-resources')

@section('title', 'Create FAQ')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create FAQ</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/faqs-manage" method="post">
                @csrf
                <div class="form-group">
                    <label for="">Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required>
                    @include('errors.inline', ['message' => $errors->first('title')])
                </div>
                <div class="form-group">
                    <label for="">Category</label>
                    <input type="text" list="categories" class="form-control @error('category') is-invalid @enderror" name="category" value="{{ old('category') }}" required>
                    @include('errors.inline', ['message' => $errors->first('category')])

                    <datalist id="categories">
                        @foreach ($categories as $item)
                            <option value="{{ $item }}">                            
                        @endforeach
                    </datalist>
                </div>
                <div class="form-group">
                    <label for="">Description</label>
                    <textarea class="wysiwyg" name="description" required>{{ old('description') }}</textarea>
                    @include('errors.inline', ['message' => $errors->first('description')])
                </div>
                <a href="/faqs-manage">Cancel</a>
                <input type="submit" class="btn btn-primary float-right" value="Save">
            </form>
        </div>
    </section>
@endsection

@section('style')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script>
        $('.wysiwyg').summernote({
            placeholder: 'Drop your announcement here!',
            height: 300
        });
    </script>
@endsection