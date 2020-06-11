@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <div class="container">
        <h4 class="mb-4">Settings</h4>

        <form action="/settings" method="post">
            @csrf
            @foreach ($settings as $item)
                @switch($item->type)
                    @case('LIMIT_UNLIQUIDATEDPR_AMOUNT')
                        <div class="mb-3"><b>Unliquidated Limit</b></div>
                        <div class="form-row mb-3">
                            <div class="col-md-6">
                                <label for="">Default unliquidated PR amount limit</label>
                            </div>
                            <div class="col-md-6">
                                <input type="number" class="form-control @error('LIMIT_UNLIQUIDATEDPR_AMOUNT') is-invalid @enderror" name="LIMIT_UNLIQUIDATEDPR_AMOUNT" value="{{ $item->value }}">
                                @include('errors.inline', ['message' => $errors->first('LIMIT_UNLIQUIDATEDPR_AMOUNT')])
                            </div>
                        </div>
                        @break
                        @case('LIMIT_UNLIQUIDATEDPR_COUNT')
                        <div class="form-row mb-5">
                            <div class="col-md-6">
                                <label for="">Default unliquidated PR transactions limit</label>
                            </div>
                            <div class="col-md-6">
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
                            <div class="col-md-3">
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
                            <div class="offset-md-6 col-md-3">
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
                            <div class="col-md-3">
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
                            <div class="offset-md-6 col-md-3">
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
                            <div class="col-md-3">
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
                            <div class="offset-md-6 col-md-3">
                                <label for="">{{ $role_3->name}}</label>
                            </div>
                            <div class="col-md-3">
                                <input type="number" class="form-control @error('LIMIT_EDIT_LIQFORM_USER_3') is-invalid @enderror" name="LIMIT_EDIT_LIQFORM_USER_3" value="{{ $item->value }}">
                                @include('errors.inline', ['message' => $errors->first('LIMIT_EDIT_LIQFORM_USER_3')])
                            </div>
                        </div>
                        @break
                        @case('LIMIT_EDIT_DEPOSIT_USER_2')
                        <div class="form-row mb-3">
                            <div class="col-md-6">
                                <label for="">Deposit edit limit</label>
                            </div>
                            <div class="col-md-3">
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
            <input type="submit" class="btn btn-primary float-right" value="Save"> 
        </form>
    </div>
@endsection