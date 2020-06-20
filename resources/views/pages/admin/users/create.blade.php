@extends('layouts.app')

@section('title', 'Create User')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create User</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/user" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                    @include('errors.inline', ['message' => $errors->first('name')])
                </div>
                <div class="form-group">
                    <label for="">Email Address</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                    @include('errors.inline', ['message' => $errors->first('email')])
                </div>
                <div class="form-group">
                    <label for="">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                    @include('errors.inline', ['message' => $errors->first('password')])
                </div>
                <div class="form-group">
                    <label for="">Confirm Password</label>
                    <input type="password" class="form-control" name="password_confirmation" required>
                </div>
                <div class="form-group">
                    <label for="avatar">Avatar</label>
                    <input type="file" name="avatar" class="form-control-file @error('avatar') is-invalid @enderror" required>
                    @include('errors.inline', ['message' => $errors->first('avatar')])
                </div>
    
                <div class="mt-5 mb-3"><b>Advanced Settings</b></div>
                <div class="form-row mb-3">
                    <div class="col-md-6">
                        <label for="">Unliquidated PR amount limit</label>
                    </div>
                    <div class="col-md-6">
                        <input type="number" class="form-control @error('LIMIT_UNLIQUIDATEDPR_AMOUNT') is-invalid @enderror" name="LIMIT_UNLIQUIDATEDPR_AMOUNT" value="{{ old('LIMIT_UNLIQUIDATEDPR_AMOUNT') }}" placeholder="{{ __('messages.leave_blank') }}">
                        @include('errors.inline', ['message' => $errors->first('LIMIT_UNLIQUIDATEDPR_AMOUNT')])
                    </div>
                </div>
                <div class="form-row mb-3">
                    <div class="col-md-6">
                        <label for="">Unliquidated PR transactions limit</label>
                    </div>
                    <div class="col-md-6">
                        <input type="number" class="form-control @error('LIMIT_UNLIQUIDATEDPR_COUNT') is-invalid @enderror" name="LIMIT_UNLIQUIDATEDPR_COUNT" value="{{ old('LIMIT_UNLIQUIDATEDPR_COUNT') }}" placeholder="{{ __('messages.leave_blank') }}">
                        @include('errors.inline', ['message' => $errors->first('LIMIT_UNLIQUIDATEDPR_COUNT')])
                    </div>
                </div>
                <div class="form-row mb-3">
                    <div class="col-md-6">
                        <label for="">Unliquidated PR transactions limit</label>
                    </div>
                    <div class="col-md-6">
                        <input type="number" class="form-control @error('LIMIT_UNLIQUIDATEDPR_COUNT') is-invalid @enderror" name="LIMIT_UNLIQUIDATEDPR_COUNT" value="{{ old('LIMIT_UNLIQUIDATEDPR_COUNT') }}" placeholder="{{ __('messages.leave_blank') }}">
                        @include('errors.inline', ['message' => $errors->first('LIMIT_UNLIQUIDATEDPR_COUNT')])
                    </div>
                </div>
    
                <div class="py-5">
                    <a href="/user">Cancel</a>
                    <input type="submit" class="btn btn-primary float-right" value="Save">
                </div>
            </form>
        </div>
    </section>
@endsection