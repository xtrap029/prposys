@extends('layouts.app')

@section('title', 'Create Vendor')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Vendor</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/vendor" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                        @include('errors.inline', ['message' => $errors->first('name')])
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Address</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" required>
                        @include('errors.inline', ['message' => $errors->first('address')])
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">TIN</label>
                        <input type="text" class="form-control @error('tin') is-invalid @enderror" name="tin" value="{{ old('tin') }}" required>
                        @include('errors.inline', ['message' => $errors->first('tin')])
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="">Contact Person</label>
                        <input type="text" class="form-control @error('contact_person') is-invalid @enderror" name="contact_person" value="{{ old('contact_person') }}" required>
                        @include('errors.inline', ['message' => $errors->first('contact_person')])
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Contact No.</label>
                        <input type="text" class="form-control @error('contact_no') is-invalid @enderror" name="contact_no" value="{{ old('contact_no') }}" required>
                        @include('errors.inline', ['message' => $errors->first('contact_no')])
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Contact Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                        @include('errors.inline', ['message' => $errors->first('email')])
                    </div>
                </div>                
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="">Account Bank</label>
                        <input type="text" class="form-control @error('account_bank') is-invalid @enderror" name="account_bank" value="{{ old('account_bank') }}" required>
                        @include('errors.inline', ['message' => $errors->first('account_bank')])
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Account Name</label>
                        <input type="text" class="form-control @error('account_name') is-invalid @enderror" name="account_name" value="{{ old('account_name') }}" required>
                        @include('errors.inline', ['message' => $errors->first('account_name')])
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Account No.</label>
                        <input type="text" class="form-control @error('account_number') is-invalid @enderror" name="account_number" value="{{ old('account_number') }}" required>
                        @include('errors.inline', ['message' => $errors->first('account_number')])
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="">Product</label>
                        <input type="text" class="form-control @error('product') is-invalid @enderror" name="product" value="{{ old('product') }}" required>
                        @include('errors.inline', ['message' => $errors->first('product')])
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Description</label>
                        <input type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ old('description') }}" required>
                        @include('errors.inline', ['message' => $errors->first('description')])
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">2303</label>
                        <input type="file" name="file" class="form-control-file @error('file') is-invalid @enderror">
                        @include('errors.inline', ['message' => $errors->first('file')])
                    </div>
                </div>
                <a href="/vendor">Cancel</a>
                <input type="submit" class="btn btn-primary float-right" value="Save">
            </form>
        </div>
    </section>
@endsection