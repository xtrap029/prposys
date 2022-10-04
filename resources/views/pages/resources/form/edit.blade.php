@extends('layouts.app-resources')

@section('title', 'Edit Form')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Form</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/forms-manage/{{ $form->id }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $form->name }}" required>
                    @include('errors.inline', ['message' => $errors->first('name')])
                </div>
                <div class="form-group">
                    <label for="">Category</label>
                    <input type="text" list="categories" class="form-control @error('category') is-invalid @enderror" name="category" value="{{ $form->category }}" required>
                    @include('errors.inline', ['message' => $errors->first('category')])

                    <datalist id="categories">
                        @foreach ($categories as $item)
                            <option value="{{ $item }}">                            
                        @endforeach
                    </datalist>
                </div>
                <div class="form-group">
                    <label for="">User Level Options</label>
                    <select name="ua_level_ids[]" class="form-control form-control-sm chosen-select" multiple>
                        @foreach ($ua_levels as $item)
                            <option value="{{ $item->id }}" {{ in_array($item->id, explode(',',$form->ua_level_ids)) ? 'selected' : '' }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Form Attachment <small>(Accepts .jpg, .png, .docx, and .pdf file types, not more than 10mb.)</small></label>
                    <a href="/storage/public/attachments/form/{{ $form->attachment }}" alt="" target="_blank" class="ml-3">view current</a>
                    <input type="file" class="form-control @error('attachment') is-invalid @enderror" name="attachment">
                    @include('errors.inline', ['message' => $errors->first('attachment')])
                </div>
                <div class="form-group">
                    <label for="">Description</label>
                    <textarea class="wysiwyg" name="description" required>{{ $form->description }}</textarea>
                    @include('errors.inline', ['message' => $errors->first('description')])
                </div>
                <a href="/forms-manage">Cancel</a>
                <input type="submit" class="btn btn-primary float-right" value="Save">
            </form>
        </div>
    </section>
@endsection

@section('style')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.css" integrity="sha512-0nkKORjFgcyxv3HbE4rzFUlENUMNqic/EzDIeYCgsKa/nwqr2B91Vu/tNAu4Q0cBuG4Xe/D1f/freEci/7GDRA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $('.wysiwyg').summernote({
            placeholder: 'Drop your description here!',
            height: 300
        });

        $('.chosen-select').chosen();
    </script>
@endsection