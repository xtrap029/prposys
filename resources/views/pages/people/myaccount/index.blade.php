@extends('layouts.app-people')

@section('title', 'My Account')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>My Account</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-lg-4 d-none">
                    <div class="card card-widget widget-user">
                        <div class="widget-user-header bg--tecc">
                            <h3 class="widget-user-username">{{ $user->name }}</h3>
                            <h5 class="widget-user-desc">{{ $user->role->name }}</h5>
                        </div>
                        <div class="widget-user-image">
                            <img class="img-circle elevation-2" src="/storage/public/images/users/{{ $user->avatar }}" alt="User Avatar">
                        </div>
                        <div class="card-footer bg-white">
                            <div class="row">
                                <div class="col-sm-6 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">Email</h5>
                                        <span>{{ $user->email }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="description-block">
                                        <h5 class="description-header">Joined</h5>
                                        <span>{{ Carbon::parse($user->created_at)->diffInDays(Carbon::now()) >= 1 ? $user->created_at->format('Y-m-d') : $user->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-2">
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
                                <h5 class="font-weight-bold">{{ $user->name }}</h5>
                            </div>
                        </div>
                        <div class="tab-pane fade m-4" id="account" role="tabpanel" aria-labelledby="account-tab">
                            <form action="/my-account" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="form-group">
                                    <label for="avatar" class="d-block">Avatar</label>
                                    <img src="/storage/public/images/users/{{ $user->avatar }}" alt="" class="thumb thumb--sm img-thumbnail">
                                    <input type="file" name="avatar" class="form-control-file @error('avatar') is-invalid @enderror d-inline-block w-auto">
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
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary float-right" value="Update">
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade m-4" id="sequence" role="tabpanel" aria-labelledby="sequence-tab">
                            <div class="form-group mb-5">
                                <label for="">Role</label>
                                <h5 class="font-weight-bold">{{ $user->role->name }}</h5>
                            </div>
                            <div class="form-group mb-5">
                                <label for="">Default Company</label>
                                <h5 class="font-weight-bold">{{ $user->company->name }}</h5>
                            </div>
                            <div class="form-group mb-5">
                                <label for="">Unliquidated PR amount limit</label>
                                <h5 class="font-weight-bold">{{ $user->LIMIT_UNLIQUIDATEDPR_AMOUNT }}</h5>
                            </div>
                            <div class="form-group mb-5">
                                <label for="">Unliquidated PR transactions limit</label>
                                <h5 class="font-weight-bold">{{ $user->LIMIT_UNLIQUIDATEDPR_COUNT }}</h5>
                            </div>
                        </div>
                        <div class="tab-pane fade m-4" id="leaves" role="tabpanel" aria-labelledby="leaves-tab">...</div>
                    </div>  
                </div>
            </div>
        </div>
    </section>
@endsection