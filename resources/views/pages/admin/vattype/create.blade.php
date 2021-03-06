@extends('layouts.app')

@section('title', 'Create Tax Type')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Tax Type</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/vat-type" method="post">
                @csrf
                <div class="form-row">
                    <div class="col-sm-4 mb-3">
                        <label for="">Code</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code') }}" required>
                        @include('errors.inline', ['message' => $errors->first('code')])
                    </div>
                    <div class="col-sm-8 mb-3">
                        <label for="">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                        @include('errors.inline', ['message' => $errors->first('name')])
                    </div>
                </div>
                <div class="form-row mb-3">
                    <div class="col-sm-6 col-md-2 mb-3">
                        <label for="">VAT (%)</label>
                        <input type="number" class="form-control @error('vat') is-invalid @enderror" name="vat" value="{{ old('vat') }}" max="100" required>
                        @include('errors.inline', ['message' => $errors->first('vat')])
                    </div>
                    <div class="col-sm-6 col-md-2 mb-3">
                        <label for="">WHT (%)</label>
                        <input type="text" class="form-control @error('wht') is-invalid @enderror" name="wht" value="{{ old('wht') }}" max="100" required>
                        @include('errors.inline', ['message' => $errors->first('wht')])
                    </div>
                    <div class="col-md-2 mb-3 text-center">
                        <label for="" class="invisible">.</label>
                        <div>Allow in</div>
                    </div>
                    <div class="col-4 col-md-2 mb-3">
                        <label for="">PR</label>
                        <select name="is_pr" class="form-control @error('is_pr') is-invalid @enderror">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                        @include('errors.inline', ['message' => $errors->first('is_pr')])
                    </div>
                    <div class="col-4 col-md-2 mb-3">
                        <label for="">PO</label>
                        <select name="is_po" class="form-control @error('is_po') is-invalid @enderror">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                        @include('errors.inline', ['message' => $errors->first('is_po')])
                    </div>
                    <div class="col-4 col-md-2 mb-3">
                        <label for="">PC</label>
                        <select name="is_pc" class="form-control @error('is_pc') is-invalid @enderror">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                        @include('errors.inline', ['message' => $errors->first('is_pc')])
                    </div>
                </div>
                <a href="/vat-type">Cancel</a>
                <input type="submit" class="btn btn-primary float-right" value="Save">
            </form>
        </div>
    </section>
@endsection