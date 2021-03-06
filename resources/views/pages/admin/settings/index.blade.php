@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Settings</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/settings" method="post" enctype="multipart/form-data">
                @csrf
                @foreach ($settings as $item)
                    @switch($item->type)
                            @case('LIMIT_UNLIQUIDATEDPR_AMOUNT')
                                <div class="mb-3"><b>Unliquidated Limit</b></div>
                                <div class="form-row mb-3">
                                    <div class="col-md-8">
                                        <label for="">Default unliquidated PR amount limit</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" class="form-control @error('LIMIT_UNLIQUIDATEDPR_AMOUNT') is-invalid @enderror" name="LIMIT_UNLIQUIDATEDPR_AMOUNT" value="{{ $item->value }}">
                                        @include('errors.inline', ['message' => $errors->first('LIMIT_UNLIQUIDATEDPR_AMOUNT')])
                                    </div>
                                </div>
                            @break
                            @case('LIMIT_UNLIQUIDATEDPR_COUNT')
                                <div class="form-row mb-5">
                                    <div class="col-md-8">
                                        <label for="">Default unliquidated PR transactions limit</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" class="form-control @error('LIMIT_UNLIQUIDATEDPR_COUNT') is-invalid @enderror" name="LIMIT_UNLIQUIDATEDPR_COUNT" value="{{ $item->value }}">
                                        @include('errors.inline', ['message' => $errors->first('LIMIT_UNLIQUIDATEDPR_COUNT')])
                                    </div>
                                </div>
                            @break
                            @case('LIMIT_EDIT_GENPR_USER_2')
                                <div class="mb-3"><b>User Changes Limit</b></div>
                                <div class="form-row mb-3">
                                    <div class="col-sm-6 col-md-4">
                                        <label for="">Generate PR edit limit</label>
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4 text-right pr-md-5 align-self-end">
                                        <label for="">{{ $role_2->name}}</label>
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4">
                                        <input type="number" class="form-control @error('LIMIT_EDIT_GENPR_USER_2') is-invalid @enderror" name="LIMIT_EDIT_GENPR_USER_2" value="{{ $item->value }}">
                                        @include('errors.inline', ['message' => $errors->first('LIMIT_EDIT_GENPR_USER_2')])
                                    </div>
                                </div>
                            @break
                            @case('LIMIT_EDIT_GENPR_USER_3')
                                <div class="form-row mb-3">
                                    <div class="offset-sm-6 offset-md-4 col-6 col-sm-3 col-md-4 text-right pr-md-5 align-self-end">
                                        <label for="">{{ $role_3->name}}</label>
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4">
                                        <input type="number" class="form-control @error('LIMIT_EDIT_GENPR_USER_3') is-invalid @enderror" name="LIMIT_EDIT_GENPR_USER_3" value="{{ $item->value }}">
                                        @include('errors.inline', ['message' => $errors->first('LIMIT_EDIT_GENPR_USER_3')])
                                    </div>
                                </div>
                            @break
                            @case('LIMIT_EDIT_PRPOFORM_USER_2')
                                <div class="form-row mb-3">
                                    <div class="col-sm-6 col-md-4">
                                        <label for="">PR/PO Form edit limit</label>
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4 text-right pr-md-5 align-self-end">
                                        <label for="">{{ $role_2->name}}</label>
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4">
                                        <input type="number" class="form-control @error('LIMIT_EDIT_PRPOFORM_USER_2') is-invalid @enderror" name="LIMIT_EDIT_PRPOFORM_USER_2" value="{{ $item->value }}">
                                        @include('errors.inline', ['message' => $errors->first('LIMIT_EDIT_PRPOFORM_USER_2')])
                                    </div>
                                </div>
                            @break
                            @case('LIMIT_EDIT_PRPOFORM_USER_3')
                                <div class="form-row mb-3">
                                    <div class="offset-sm-6 offset-md-4 col-6 col-sm-3 col-md-4 text-right pr-md-5 align-self-end">
                                        <label for="">{{ $role_3->name}}</label>
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4">
                                        <input type="number" class="form-control @error('LIMIT_EDIT_PRPOFORM_USER_3') is-invalid @enderror" name="LIMIT_EDIT_PRPOFORM_USER_3" value="{{ $item->value }}">
                                        @include('errors.inline', ['message' => $errors->first('LIMIT_EDIT_PRPOFORM_USER_3')])
                                    </div>
                                </div>
                            @break
                            @case('LIMIT_EDIT_LIQFORM_USER_2')
                                <div class="form-row mb-3">
                                    <div class="col-sm-6 col-md-4">
                                        <label for="">Liquidation Form edit limit</label>
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4 text-right pr-md-5 align-self-end">
                                        <label for="">{{ $role_2->name}}</label>
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4">
                                        <input type="number" class="form-control @error('LIMIT_EDIT_LIQFORM_USER_2') is-invalid @enderror" name="LIMIT_EDIT_LIQFORM_USER_2" value="{{ $item->value }}">
                                        @include('errors.inline', ['message' => $errors->first('LIMIT_EDIT_LIQFORM_USER_2')])
                                    </div>
                                </div>
                            @break
                            @case('LIMIT_EDIT_LIQFORM_USER_3')
                                <div class="form-row mb-3">
                                    <div class="offset-sm-6 offset-md-4 col-6 col-sm-3 col-md-4 text-right pr-md-5 align-self-end">
                                        <label for="">{{ $role_3->name}}</label>
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4">
                                        <input type="number" class="form-control @error('LIMIT_EDIT_LIQFORM_USER_3') is-invalid @enderror" name="LIMIT_EDIT_LIQFORM_USER_3" value="{{ $item->value }}">
                                        @include('errors.inline', ['message' => $errors->first('LIMIT_EDIT_LIQFORM_USER_3')])
                                    </div>
                                </div>
                            @break
                            @case('LIMIT_EDIT_DEPOSIT_USER_2')
                                <div class="form-row mb-3 d-none">
                                    <div class="col-md-4">
                                        <label for="">Deposit edit limit</label>
                                    </div>
                                    <div class="col-md-4 text-right pr-md-5 align-self-end">
                                        <label for="">{{ $role_2->name}}</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" class="form-control @error('LIMIT_EDIT_DEPOSIT_USER_2') is-invalid @enderror" name="LIMIT_EDIT_DEPOSIT_USER_2" value="{{ $item->value }}">
                                        @include('errors.inline', ['message' => $errors->first('LIMIT_EDIT_DEPOSIT_USER_2')])
                                    </div>
                                </div>
                            @break
                            @case('SESSION_LIFETIME')
                                <div class="mb-3"><b>Main</b></div>

                                <div class="form-row mb-3">
                                    <div class="col-sm-6 col-md-4">
                                        <label for="">Auto Logout if Inactive</label>
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4 text-right pr-md-5 align-self-end">
                                        <label for="">Minutes</label>
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4">
                                        <input type="number" class="form-control @error('SESSION_LIFETIME') is-invalid @enderror" name="SESSION_LIFETIME" value="{{ $item->value }}">
                                        @include('errors.inline', ['message' => $errors->first('SESSION_LIFETIME')])
                                    </div>
                                </div>
                            @break
                            @case('AUTHORIZED_BY')
                                <div class="form-row mb-3">
                                    <div class="col-sm-6 col-md-4">
                                        <label for="">Final Authorized</label>
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4 text-right pr-md-5 align-self-end">
                                        <label for="">Label</label>
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4">
                                        <select name="AUTHORIZED_BY" class="form-control @error('AUTHORIZED_BY') is-invalid @enderror">
                                            @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ $user->id == $item->value ? 'selected' : '' }}>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @include('errors.inline', ['message' => $errors->first('AUTHORIZED_BY')])
                                    </div>
                                </div>
                            @break
                            @case('SITE_LOGO')
                                <div class="form-row mb-3">
                                    <div class="col-6 col-sm-8">
                                        <label for="">
                                            Site Logo
                                            <div class="small">Image with 500kb max size</div>
                                        </label>                                        
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <input type="file" name="SITE_LOGO" class="form-control-file @error('SITE_LOGO') is-invalid @enderror d-inline-block mt-2">
                                        @include('errors.inline', ['message' => $errors->first('SITE_LOGO')])
                                    </div>
                                </div>
                            @break
                            @case('FOOTER_LABEL')
                                <div class="form-row mb-3">
                                    <div class="col-md-8">
                                        <label for="">Footer Text</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control @error('FOOTER_LABEL') is-invalid @enderror" name="FOOTER_LABEL" value="{{ $item->value }}">
                                        @include('errors.inline', ['message' => $errors->first('FOOTER_LABEL')])
                                    </div>
                                </div>
                            @break
                            @default
                    @endswitch            
                @endforeach
                <div class="py-5 text-right">
                    <input type="submit" class="btn btn-primary" value="Save"> 
                </div>
            </form>
        </div>
    </section>
@endsection