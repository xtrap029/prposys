@extends('layouts.app')

@section('title', 'Generate '.strtoupper($trans_type))

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <img src="/storage/public/images/companies/{{ $company->logo }}" alt="" class="thumb--xs mr-2">
                        {{ $company->name }}
                    </h1>
                </div>
                <div class="col-sm-6 text-right">
                    <h1>Generate {{ strtoupper($trans_type) }}</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/transaction/create" method="post" class="jsPreventMultiple">
                @csrf
                <input type="hidden" name="trans_type" value="{{ $trans_type }}">

                <div class="form-row mb-3">
                    {{-- <div class="col-md-6">
                        <label for="">Particulars</label>
                        @if ($trans_page == 'prpo')
                            <select name="particulars_id" class="form-control @error('particulars_id') is-invalid @enderror">
                                @foreach ($particulars as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == old('particulars_id') ? 'selected' : '' }}>{{ $item->name }}</option>                                        
                                @endforeach
                            </select>
                            @include('errors.inline', ['message' => $errors->first('particulars_id')])
                        @else
                            <input type="text" class="form-control @error('particulars_custom') is-invalid @enderror" name="particulars_custom" value="{{ old('particulars_custom') }}" required>
                            @include('errors.inline', ['message' => $errors->first('particulars_custom')])
                        @endif
                    </div> --}}
                    <div class="col-md-7">
                        <label for="">Project</label>
                        <select name="project_id" class="form-control @error('project_id') is-invalid @enderror">
                            @foreach ($projects as $item)
                                <option value="{{ $item->id }}" {{ $item->id == old('project_id') ? 'selected' : '' }}>{{ $item->project }}</option>                                        
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('project_id')])
                    </div>
                    <div class="col-md-1">
                        <label for="">Currency</label>
                        <select name="currency" class="form-control @error('currency') is-invalid @enderror">
                            <option value="PHP">PHP</option>   
                            <option value="USD">USD</option>   
                            <option value="EUR">EUR</option>   
                        </select>
                        @include('errors.inline', ['message' => $errors->first('currency')])
                    </div>
                    <div class="col-md-4">
                        <label for="">Amount</label>
                        <input type="number" class="form-control @error('amount') is-invalid @enderror" name="amount" step="0.01" value="{{ old('amount') }}" required>
                        @include('errors.inline', ['message' => $errors->first('amount')])
                    </div>
                </div>
                <div class="form-row mb-3">
                    <div class="col-md-8">
                        <label for="">Purpose</label>
                        <textarea name="purpose" rows="1" class="form-control @error('purpose') is-invalid @enderror" required>{{ old('purpose') }}</textarea>
                        @include('errors.inline', ['message' => $errors->first('purpose')])
                    </div>
                    <div class="col-md-4">
                        <label for="">Payee Name</label>
                        <input type="text" class="form-control @error('payee') is-invalid @enderror" name="payee" value="{{ old('payee') }}" required>
                        @include('errors.inline', ['message' => $errors->first('payee')])
                    </div>
                </div>
                <div class="form-row mb-3">
                    <div class="col-md-4">
                        <label for="">Due Date</label>
                        <input type="date" class="form-control @error('due_at') is-invalid @enderror" name="due_at" value="{{ old('due_at') }}" required>
                        @include('errors.inline', ['message' => $errors->first('due_at')])
                    </div>
                    <div class="col-md-4">
                        <label for="">Requested by</label>
                        <select name="requested_id" class="form-control @error('requested_id') is-invalid @enderror">
                            @foreach ($users as $item)
                                <option value="{{ $item->id }}" {{ $item->id == Auth::user()->id ? 'selected' : '' }}>{{ $item->name }}</option>                                        
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('requested_id')])
                    </div>
                    <div class="col-md-4">
                        <label for="">Prepared by</label>
                        <h5>{{ Auth::user()->name }}</h5>
                    </div>
                    {{-- <div class="col-md-2">
                        <label for="">For Deposit?</label>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input @error('is_deposit') is-invalid @enderror" name="is_deposit" id="is_deposit" value="1" {{ old('is_deposit') == 'on' ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_deposit">Yes</label>
                        </div>
                        @include('errors.inline', ['message' => $errors->first('is_deposit')])
                    </div> --}}
                    <div class="card col-md-12 mt-4">
                        <div class="card-header font-weight-bold">
                            Select Transaction Category
                        </div>
                        <div class="card-body pb-1 row">                            
                            <div class="col-12 col-md-3">
                                <div class="callout py-2 mx-2 row">
                                    <div class="col-3">
                                        <input type="radio" name="trans_category" value="{{ config('global.trans_category')[0] }}" class="form-control m-auto outline-0" checked>
                                    </div>
                                    <div class="col-9 mt-2">
                                        <h6 class="font-weight-bold">{{ config('global.trans_category_label')[0] }}</h6>
                                        <p class="d-none">Lorem ipsum dolor sit amet, consectetur, et dolore magna aliqua.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="callout py-2 mx-2 row">
                                    <div class="col-3">
                                        <input type="radio" name="trans_category" value="{{ config('global.trans_category')[1] }}" class="form-control m-auto outline-0">
                                    </div>
                                    <div class="col-9 mt-2">
                                        <h6 class="font-weight-bold">{{ config('global.trans_category_label')[1] }}</h6>
                                        <p class="d-none">Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="callout py-2 mx-2 row">
                                    <div class="col-3">
                                        <input type="radio" name="trans_category" value="{{ config('global.trans_category')[2] }}" class="form-control m-auto outline-0">
                                    </div>
                                    <div class="col-9 mt-2">
                                        <h6 class="font-weight-bold">{{ config('global.trans_category_label')[2] }}</h6>          
                                        <p class="d-none">Excepteur sint non proident, sunt in culpa qui mollit anim id.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="callout py-2 mx-2 row">
                                    <div class="col-3">
                                        <input type="radio" name="trans_category" value="{{ config('global.trans_category')[3] }}" class="form-control m-auto outline-0">
                                    </div>
                                    <div class="col-9 mt-2">
                                        <h6 class="font-weight-bold">{{ config('global.trans_category_label')[3] }}</h6>
                                        <p class="d-none">Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-3 d-none">
                                <div class="callout py-2 mx-2 row">
                                    <div class="col-3">
                                        <input type="radio" name="trans_category" value="{{ config('global.trans_category')[4] }}" class="form-control m-auto outline-0">
                                    </div>
                                    <div class="col-9 mt-2">
                                        <h6 class="font-weight-bold">{{ config('global.trans_category_label')[4] }}</h6>
                                        <p class="d-none">Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <div class="mt-4">
                            <a href="/transaction/{{ $trans_page }}/{{ $trans_company }}" class="mr-3">Cancel</a>
                            <input type="submit" class="btn btn-primary" value="Save">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection