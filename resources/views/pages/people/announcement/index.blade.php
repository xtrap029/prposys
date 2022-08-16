@extends('layouts.app-people')

@section('title', 'Announcement')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Announcement</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/people-announcement" method="post" enctype="multipart/form-data">
                @csrf
                <textarea class="wysiwyg" name="announcement">{{ $announcement }}</textarea>
                <div class="py-5 text-right">
                    <input type="submit" class="btn btn-primary" value="Save"> 
                </div>
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