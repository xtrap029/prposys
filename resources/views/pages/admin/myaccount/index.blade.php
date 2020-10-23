@extends('layouts.app')

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
                <div class="col-md-4">
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
                                        <span>{{ Carbon::parse($user->created_at)->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <form action="/my-account" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label for="">Default Company</label>
                            <select name="company_id" class="form-control">
                                @foreach ($companies as $item)
                                    <option value="{{ $item->id }}" {{ $user->company_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6">
                                <label for="">Change Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                                @include('errors.inline', ['message' => $errors->first('password')])
                            </div>
                            <div class="col-md-6">
                                <label for="">Confirm Change Password</label>
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="avatar" class="d-block">Avatar</label>
                            <input type="file" name="avatar" class="form-control-file @error('avatar') is-invalid @enderror d-inline-block w-auto">
                            @include('errors.inline', ['message' => $errors->first('avatar')])

                            <div class="float-right">
                                <input type="submit" class="btn btn-primary float-right" value="Update"> 
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection