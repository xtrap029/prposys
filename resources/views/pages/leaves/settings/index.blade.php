@extends('layouts.app-leaves')

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
            <form action="/leaves-settings" method="post" enctype="multipart/form-data">
                @csrf
                @foreach ($settings as $item)
                    @switch($item->type)
                            @case('LEAVES_ANNUAL')
                                <div class="form-row mb-3">
                                    <div class="col-sm-6 col-md-4">
                                        <label for="">Annual Leaves</label>
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4 text-right pr-md-5 align-self-end">
                                        <label for="">Days</label>
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4">
                                        <input type="number" class="form-control @error('LEAVES_ANNUAL') is-invalid @enderror" name="LEAVES_ANNUAL" value="{{ $item->value }}">
                                        @include('errors.inline', ['message' => $errors->first('LEAVES_ANNUAL')])
                                    </div>
                                </div>
                            @break
                            @case('LEAVES_CARRY')
                                <div class="form-row mb-3">
                                    <div class="col-sm-6 col-md-4">
                                        <label for="">Max Carry Over Leaves</label>
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4 text-right pr-md-5 align-self-end">
                                        <label for="">Days</label>
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4">
                                        <input type="number" class="form-control @error('LEAVES_CARRY') is-invalid @enderror" name="LEAVES_CARRY" value="{{ $item->value }}">
                                        @include('errors.inline', ['message' => $errors->first('LEAVES_CARRY')])
                                    </div>
                                </div>
                            @break
                            @case('LEAVES_EXPIRY')
                                <div class="form-row mb-3">
                                    <div class="col-sm-6 col-md-4">
                                        <label for="">Carry Over Expiry</label>
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4 text-right pr-md-5 align-self-end">
                                        <label for="">Month</label>
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4">
                                        <select name="LEAVES_EXPIRY" class="form-control">
                                            <option value="1" {{ $item->value == "1" ? 'selected' : '' }}>January</option>
                                            <option value="2" {{ $item->value == "2" ? 'selected' : '' }}>February</option>
                                            <option value="3" {{ $item->value == "3" ? 'selected' : '' }}>March</option>
                                            <option value="4" {{ $item->value == "4" ? 'selected' : '' }}>April</option>
                                            <option value="5" {{ $item->value == "5" ? 'selected' : '' }}>May</option>
                                            <option value="6" {{ $item->value == "6" ? 'selected' : '' }}>June</option>
                                            <option value="7" {{ $item->value == "7" ? 'selected' : '' }}>July</option>
                                            <option value="8" {{ $item->value == "8" ? 'selected' : '' }}>August</option>
                                            <option value="9" {{ $item->value == "9" ? 'selected' : '' }}>September</option>
                                            <option value="10" {{ $item->value == "10" ? 'selected' : '' }}>October</option>
                                            <option value="11" {{ $item->value == "11" ? 'selected' : '' }}>November</option>
                                            <option value="12" {{ $item->value == "12" ? 'selected' : '' }}>December</option>
                                        </select>
                                        @include('errors.inline', ['message' => $errors->first('LEAVES_EXPIRY')])
                                    </div>
                                </div>
                            @break
                            @case('LEAVES_FILING_DAYS')
                                <div class="form-row mb-3">
                                    <div class="col-sm-6 col-md-4">
                                        <label for="">Advanced Leave filing minimum</label>
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4 text-right pr-md-5 align-self-end">
                                        <label for="">Days</label>
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4">
                                        <input type="number" class="form-control @error('LEAVES_FILING_DAYS') is-invalid @enderror" name="LEAVES_FILING_DAYS" value="{{ $item->value }}">
                                        @include('errors.inline', ['message' => $errors->first('LEAVES_FILING_DAYS')])
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