@extends('layouts.app')

@section('title', 'Edit Company')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Company</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/company/{{ $company->id }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="form-group">
                    <label for="">Code</label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ $company->code }}" required>
                    @include('errors.inline', ['message' => $errors->first('code')])
                </div>
                <div class="form-group">
                    <label for="">QB Code</label>
                    <input type="text" class="form-control @error('qb_code') is-invalid @enderror" name="qb_code" value="{{ $company->qb_code }}" required>
                    @include('errors.inline', ['message' => $errors->first('qb_code')])
                </div>
                <div class="form-group">
                    <label for="">QB No</label>
                    <input type="text" class="form-control @error('qb_no') is-invalid @enderror" name="qb_no" value="{{ $company->qb_no }}" required>
                    @include('errors.inline', ['message' => $errors->first('qb_no')])
                </div>
                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $company->name }}" required>
                    @include('errors.inline', ['message' => $errors->first('name')])
                </div>
                <div class="form-group">
                    <label for="">Categories</label>
                    <select class="multipleSelect" name="categories[]" multiple>
                        @foreach(config('global.trans_category') as $key => $category)
                            <option value="{{ $category }}" {{ in_array($category, explode(',', $company->categories)) ? 'selected' : '' }}>{{ config('global.trans_category_label')[$key] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="" class="d-block">Logo</label>
                    <img src="/storage/public/images/companies/{{ $company->logo }}" alt="" class="thumb thumb--sm img-thumbnail">
                    <input type="file" name="logo" class="form-control-file @error('logo') is-invalid @enderror d-inline-block w-auto ml-3">
                    @include('errors.inline', ['message' => $errors->first('logo')])
                </div>
                <a href="/company">Cancel</a>
                <input type="submit" class="btn btn-primary float-right" value="Save">
            </form>
        </div>
    </section>
@endsection

@section('script')
    <script type="text/javascript">
        $(function() {
            $('.multipleSelect').fastselect({
                placeholder: 'Choose your categories here'
            })
        })
    </script>
@endsection