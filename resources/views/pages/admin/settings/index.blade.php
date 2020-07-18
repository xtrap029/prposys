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
            <form action="/settings" method="post">
                @csrf
                @foreach ($settings as $item)
                    @switch($item->type)
                        @case('LIMIT_UNLIQUIDATEDPR_AMOUNT')
                            <div class="mb-3"><b>Unliquidated Limit</b></div>
                            <div class="form-row mb-3">
                                <div class="col-md-9">
                                    <label for="">Default unliquidated PR amount limit</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control @error('LIMIT_UNLIQUIDATEDPR_AMOUNT') is-invalid @enderror" name="LIMIT_UNLIQUIDATEDPR_AMOUNT" value="{{ $item->value }}">
                                    @include('errors.inline', ['message' => $errors->first('LIMIT_UNLIQUIDATEDPR_AMOUNT')])
                                </div>
                            </div>
                            @break
                            @case('LIMIT_UNLIQUIDATEDPR_COUNT')
                            <div class="form-row mb-5">
                                <div class="col-md-9">
                                    <label for="">Default unliquidated PR transactions limit</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control @error('LIMIT_UNLIQUIDATEDPR_COUNT') is-invalid @enderror" name="LIMIT_UNLIQUIDATEDPR_COUNT" value="{{ $item->value }}">
                                    @include('errors.inline', ['message' => $errors->first('LIMIT_UNLIQUIDATEDPR_COUNT')])
                                </div>
                            </div>
                            @break
                            @case('LIMIT_EDIT_GENPR_USER_2')
                            <div class="mb-3"><b>User Changes Limit</b></div>
                            <div class="form-row mb-3">
                                <div class="col-md-6">
                                    <label for="">Generate PR edit limit</label>
                                </div>
                                <div class="col-md-3 text-right pr-md-5 align-self-end">
                                    <label for="">{{ $role_2->name}}</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control @error('LIMIT_EDIT_GENPR_USER_2') is-invalid @enderror" name="LIMIT_EDIT_GENPR_USER_2" value="{{ $item->value }}">
                                    @include('errors.inline', ['message' => $errors->first('LIMIT_EDIT_GENPR_USER_2')])
                                </div>
                            </div>
                            @break
                            @case('LIMIT_EDIT_GENPR_USER_3')
                            <div class="form-row mb-3">
                                <div class="offset-md-6 col-md-3 text-right pr-md-5 align-self-end">
                                    <label for="">{{ $role_3->name}}</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control @error('LIMIT_EDIT_GENPR_USER_3') is-invalid @enderror" name="LIMIT_EDIT_GENPR_USER_3" value="{{ $item->value }}">
                                    @include('errors.inline', ['message' => $errors->first('LIMIT_EDIT_GENPR_USER_3')])
                                </div>
                            </div>
                            @break
                            @case('LIMIT_EDIT_PRPOFORM_USER_2')
                            <div class="form-row mb-3">
                                <div class="col-md-6">
                                    <label for="">PR/PO Form edit limit</label>
                                </div>
                                <div class="col-md-3 text-right pr-md-5 align-self-end">
                                    <label for="">{{ $role_2->name}}</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control @error('LIMIT_EDIT_PRPOFORM_USER_2') is-invalid @enderror" name="LIMIT_EDIT_PRPOFORM_USER_2" value="{{ $item->value }}">
                                    @include('errors.inline', ['message' => $errors->first('LIMIT_EDIT_PRPOFORM_USER_2')])
                                </div>
                            </div>
                            @break
                            @case('LIMIT_EDIT_PRPOFORM_USER_3')
                            <div class="form-row mb-3">
                                <div class="offset-md-6 col-md-3 text-right pr-md-5 align-self-end">
                                    <label for="">{{ $role_3->name}}</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control @error('LIMIT_EDIT_PRPOFORM_USER_3') is-invalid @enderror" name="LIMIT_EDIT_PRPOFORM_USER_3" value="{{ $item->value }}">
                                    @include('errors.inline', ['message' => $errors->first('LIMIT_EDIT_PRPOFORM_USER_3')])
                                </div>
                            </div>
                            @break
                            @case('LIMIT_EDIT_LIQFORM_USER_2')
                            <div class="form-row mb-3">
                                <div class="col-md-6">
                                    <label for="">Liquidation Form edit limit</label>
                                </div>
                                <div class="col-md-3 text-right pr-md-5 align-self-end">
                                    <label for="">{{ $role_2->name}}</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control @error('LIMIT_EDIT_LIQFORM_USER_2') is-invalid @enderror" name="LIMIT_EDIT_LIQFORM_USER_2" value="{{ $item->value }}">
                                    @include('errors.inline', ['message' => $errors->first('LIMIT_EDIT_LIQFORM_USER_2')])
                                </div>
                            </div>
                            @break
                            @case('LIMIT_EDIT_LIQFORM_USER_3')
                            <div class="form-row mb-3">
                                <div class="offset-md-6 col-md-3 text-right pr-md-5 align-self-end">
                                    <label for="">{{ $role_3->name}}</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control @error('LIMIT_EDIT_LIQFORM_USER_3') is-invalid @enderror" name="LIMIT_EDIT_LIQFORM_USER_3" value="{{ $item->value }}">
                                    @include('errors.inline', ['message' => $errors->first('LIMIT_EDIT_LIQFORM_USER_3')])
                                </div>
                            </div>
                            @break
                            @case('LIMIT_EDIT_DEPOSIT_USER_2')
                            <div class="form-row mb-3 d-none">
                                <div class="col-md-6">
                                    <label for="">Deposit edit limit</label>
                                </div>
                                <div class="col-md-3 text-right pr-md-5 align-self-end">
                                    <label for="">{{ $role_2->name}}</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control @error('LIMIT_EDIT_DEPOSIT_USER_2') is-invalid @enderror" name="LIMIT_EDIT_DEPOSIT_USER_2" value="{{ $item->value }}">
                                    @include('errors.inline', ['message' => $errors->first('LIMIT_EDIT_DEPOSIT_USER_2')])
                                </div>
                            </div>
                            @break
                            @default
                    @endswitch            
                @endforeach
                <div class="py-5">
                    <input type="submit" class="btn btn-primary float-right" value="Save"> 
                </div>
            </form>
        </div>
    </section>
@endsection