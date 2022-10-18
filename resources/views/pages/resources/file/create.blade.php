@extends('layouts.app-resources')

@section('title', 'Create File')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create File</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/files-manage" method="post">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-8">
                        <label for="">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                        @include('errors.inline', ['message' => $errors->first('name')])
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Drive ID</label>
                        <input type="text" class="form-control @error('drive') is-invalid @enderror" name="drive" value="{{ old('drive') }}" required>
                        @include('errors.inline', ['message' => $errors->first('drive')])
                    </div>
                </div>
                <div class="form-group">
                    <label for="">User Level Options</label>
                    <select name="ua_level_ids[]" class="form-control form-control-sm chosen-select" multiple>
                        @foreach ($ua_levels as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Description</label>
                    <textarea class="form-control" name="description" required>{{ old('description') }}</textarea>
                    @include('errors.inline', ['message' => $errors->first('description')])
                </div>
                <a href="/files-manage">Cancel</a>
                <input type="submit" class="btn btn-primary float-right" value="Save">
            </form>
        </div>
    </section>
@endsection

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.css" integrity="sha512-0nkKORjFgcyxv3HbE4rzFUlENUMNqic/EzDIeYCgsKa/nwqr2B91Vu/tNAu4Q0cBuG4Xe/D1f/freEci/7GDRA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $('.chosen-select').chosen();
    </script>
@endsection