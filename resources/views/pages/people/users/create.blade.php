@extends('layouts.app-people')

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
                            <label for="">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                            @include('errors.inline', ['message' => $errors->first('name')])
                        </div>
                        {{-- <div class="form-row">
                            <div class="form-group col-lg-4">
                                <label for="">Date of Birth</label>
                                <input type="date" class="form-control @error('e_dob') is-invalid @enderror" name="name" value="{{ old('e_dob') }}" required>
                                @include('errors.inline', ['message' => $errors->first('e_dob')])
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="">Gender</label>
                                <input type="date" class="form-control @error('e_dob') is-invalid @enderror" name="name" value="{{ old('e_dob') }}" required>
                                @include('errors.inline', ['message' => $errors->first('e_dob')])
                            </div>
                        </div> --}}
                    </div>
                    <div class="tab-pane fade m-4" id="account" role="tabpanel" aria-labelledby="account-tab">
                        <div class="form-group">
                            <label for="">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                            @include('errors.inline', ['message' => $errors->first('email')])
                        </div>
                        <div class="form-group">
                            <label for="avatar">Avatar</label>
                            <input type="file" name="avatar" class="form-control-file @error('avatar') is-invalid @enderror" required>
                            @include('errors.inline', ['message' => $errors->first('avatar')])
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
                    </div>
                    <div class="tab-pane fade m-4" id="sequence" role="tabpanel" aria-labelledby="sequence-tab">
                        <div class="form-group mb-3">
                            <label for="">Role</label>
                            <select name="role_id" class="form-control @error('role_id') is-invalid @enderror">
                                <option value="">Inactive</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>                                        
                                @endforeach
                            </select>
                            @include('errors.inline', ['message' => $errors->first('role_id')])
                        </div>                
                        <div class="form-group mb-3">
                            <label for="">Default Company</label>
                            <select name="company_id" class="form-control @error('company_id') is-invalid @enderror" required>
                                @foreach ($companies as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @include('errors.inline', ['message' => $errors->first('company_id')])
                        </div>

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