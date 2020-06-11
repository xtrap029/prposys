@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <div class="container">
        <h4 class="mb-4">Edit User</h4>

        <form action="/user/{{ $user->id }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="form-group">
                <label for="">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name }}" required>
                @include('errors.inline', ['message' => $errors->first('name')])
            </div>
            <div class="form-group">
                <label for="">Role</label>
                <select name="role_id" class="form-control @error('role_id') is-invalid @enderror">
                    <option value="">Inactive</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>                                        
                    @endforeach
                </select>
                @include('errors.inline', ['message' => $errors->first('role_id')])
            </div>
            <div class="form-group">
                <label for="avatar" class="d-block">Avatar</label>
                <img src="/storage/public/images/users/{{ $user->avatar }}" alt="" class="thumb thumb--sm img-thumbnail">
                <input type="file" name="avatar" class="form-control-file @error('avatar') is-invalid @enderror d-inline-block w-auto ml-3">
                @include('errors.inline', ['message' => $errors->first('avatar')])
            </div>
            
            <div class="mt-5 mb-3"><b>Advanced Settings</b></div>
            <div class="form-row mb-3">
                <div class="col-md-6">
                    <label for="">Unliquidated PR amount limit</label>
                </div>
                <div class="col-md-6">
                    <input type="number" class="form-control @error('LIMIT_UNLIQUIDATEDPR_AMOUNT') is-invalid @enderror" name="LIMIT_UNLIQUIDATEDPR_AMOUNT" value="{{ $user->LIMIT_UNLIQUIDATEDPR_AMOUNT }}" placeholder="{{ __('messages.leave_blank') }}">
                    @include('errors.inline', ['message' => $errors->first('LIMIT_UNLIQUIDATEDPR_AMOUNT')])
                </div>
            </div>
            <div class="form-row mb-3">
                <div class="col-md-6">
                    <label for="">Unliquidated PR transactions limit</label>
                </div>
                <div class="col-md-6">
                    <input type="number" class="form-control @error('LIMIT_UNLIQUIDATEDPR_COUNT') is-invalid @enderror" name="LIMIT_UNLIQUIDATEDPR_COUNT" value="{{ $user->LIMIT_UNLIQUIDATEDPR_COUNT }}" placeholder="{{ __('messages.leave_blank') }}">
                    @include('errors.inline', ['message' => $errors->first('LIMIT_UNLIQUIDATEDPR_COUNT')])
                </div>
            </div>

            <div class="mt-5">
                <a href="/user">Cancel</a>
                <input type="submit" class="btn btn-primary float-right" value="Save">
            </div>
        </form>
    </div>
@endsection