@extends('layouts.app')

@section('title', 'Edit Tax Type')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Tax Type</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/vat-type/{{ $vat_type->id }}" method="post">
                @csrf
                @method('put')
                <div class="form-row mb-3">
                    <div class="col-md-4">
                        <label for="">Code</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ $vat_type->code }}" required>
                        @include('errors.inline', ['message' => $errors->first('code')])
                    </div>
                    <div class="col-md-8">
                        <label for="">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $vat_type->name }}" required>
                        @include('errors.inline', ['message' => $errors->first('name')])
                    </div>
                </div>
                <div class="form-row mb-3">
                    <div class="col-md-2">
                        <label for="">VAT (%)</label>
                        <input type="number" class="form-control @error('vat') is-invalid @enderror" name="vat" value="{{ $vat_type->vat }}" max="100" required>
                        @include('errors.inline', ['message' => $errors->first('vat')])
                    </div>
                    <div class="col-md-2">
                        <label for="">WHT (%)</label>
                        <input type="text" class="form-control @error('wht') is-invalid @enderror" name="wht" value="{{ $vat_type->wht }}" max="100" required>
                        @include('errors.inline', ['message' => $errors->first('wht')])
                    </div>
                    <div class="col-md-2 text-center">
                        <label for="" class="invisible">.</label>
                        <div>Allow in</div>
                    </div>
                    <div class="col-md-2">
                        <label for="">PR</label>
                        <select name="is_pr" class="form-control @error('is_pr') is-invalid @enderror">
                            <option value="0">No</option>
                            <option value="1" {{ $vat_type->is_pr ? 'selected' : '' }}>Yes</option>
                        </select>
                        @include('errors.inline', ['message' => $errors->first('is_pr')])
                    </div>
                    <div class="col-md-2">
                        <label for="">PO</label>
                        <select name="is_po" class="form-control @error('is_po') is-invalid @enderror">
                            <option value="0">No</option>
                            <option value="1" {{ $vat_type->is_po ? 'selected' : '' }}>Yes</option>
                        </select>
                        @include('errors.inline', ['message' => $errors->first('is_po')])
                    </div>
                    <div class="col-md-2">
                        <label for="">PC</label>
                        <select name="is_pc" class="form-control @error('is_pc') is-invalid @enderror">
                            <option value="0">No</option>
                            <option value="1" {{ $vat_type->is_pc ? 'selected' : '' }}>Yes</option>
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