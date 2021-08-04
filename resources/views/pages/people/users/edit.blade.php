@extends('layouts.app-people')

@section('title', 'Edit User')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit User</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/user/{{ $user->id }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="home" aria-selected="true">Profile</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="account-tab" data-toggle="tab" href="#account" role="tab" aria-controls="profile" aria-selected="false">Account</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="sequence-tab" data-toggle="tab" href="#sequence" role="tab" aria-controls="contact" aria-selected="false">Sequence</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="leaves-tab" data-toggle="tab" href="#leaves" role="tab" aria-controls="contact" aria-selected="false">Leaves</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active m-4" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="form-group">
                            <label for="">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name }}" required>
                            @include('errors.inline', ['message' => $errors->first('name')])
                        </div>
                    </div>
                    <div class="tab-pane fade m-4" id="account" role="tabpanel" aria-labelledby="account-tab">
                        <div class="form-group">
                            <label for="avatar" class="d-block">Avatar</label>
                            <img src="/storage/public/images/users/{{ $user->avatar }}" alt="" class="thumb thumb--sm img-thumbnail">
                            <input type="file" name="avatar" class="form-control-file @error('avatar') is-invalid @enderror d-inline-block w-auto ml-3">
                            @include('errors.inline', ['message' => $errors->first('avatar')])
                        </div>
                        <div class="form-group">
                            <label for="">Change Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                            @include('errors.inline', ['message' => $errors->first('password')])
                        </div>
                        <div class="form-group">
                            <label for="">Confirm Change Password</label>
                            <input type="password" class="form-control" name="password_confirmation">
                        </div>
                    </div>
                    <div class="tab-pane fade m-4" id="sequence" role="tabpanel" aria-labelledby="sequence-tab">
                        <div class="form-group mb-3">
                            <label for="">Role</label>
                            <select name="role_id" class="form-control @error('role_id') is-invalid @enderror">
                                <option value="">Inactive</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>                                        
                                @endforeach
                            </select>
                            @include('errors.inline', ['message' => $errors->first('role_id')])
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Default Company</label>
                            <select name="company_id" class="form-control">
                                @foreach ($companies as $item)
                                    <option value="{{ $item->id }}" {{ $user->company_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
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
                    </div>
                    <div class="tab-pane fade m-4" id="leaves" role="tabpanel" aria-labelledby="leaves-tab">...</div>
                </div>    
                <div class="m-4">
                    <a href="/user">Cancel</a>
                    <input type="submit" class="btn btn-primary float-right" value="Save">
                </div>
            </form>
        </div>
    </section>
@endsection