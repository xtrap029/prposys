@extends('layouts.app')

@section('title', 'Edit '.strtoupper($transaction->trans_type))

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <img src="/storage/public/images/companies/{{ $transaction->project->company->logo }}" alt="" class="thumb--xs mr-2">
                        {{ $transaction->project->company->name }}
                    </h1>
                </div>
                <div class="col-sm-6 text-right">
                    <h1>Edit {{ strtoupper($transaction->trans_type) }}</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/transaction/edit/{{ $transaction->id }}" method="post">
                @csrf
                @method('put')

                <div class="form-row mb-3">
                    <div class="col-md-6">
                        <label for="">Particulars</label>
                        @if ($trans_page == 'prpo')
                            <select name="particulars_id" class="form-control @error('particulars_id') is-invalid @enderror">
                                @foreach ($particulars as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == $transaction->particulars_id ? 'selected' : '' }}>{{ $item->name }}</option>                                        
                                @endforeach
                            </select>
                            @include('errors.inline', ['message' => $errors->first('particulars_id')])
                        @else
                            <input type="text" class="form-control @error('particulars_custom') is-invalid @enderror" name="particulars_custom" value="{{ $transaction->particulars_custom }}" required>
                            @include('errors.inline', ['message' => $errors->first('particulars_custom')])
                        @endif
                    </div>
                    <div class="col-md-2">
                        <label for="">Currency</label>
                        <input type="text" class="form-control" value="{{ $transaction->currency }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="">Amount</label>
                        <input type="number" class="form-control @error('amount') is-invalid @enderror" step="0.01" name="amount" value="{{ $transaction->amount }}" required>
                        @include('errors.inline', ['message' => $errors->first('amount')])
                    </div>
                </div>
                <div class="form-row mb-3">
                    <div class="col-md-6">
                        <label for="">Purpose</label>
                        <textarea name="purpose" rows="1" class="form-control @error('purpose') is-invalid @enderror" required>{{ $transaction->purpose }}</textarea>
                        @include('errors.inline', ['message' => $errors->first('purpose')])
                    </div>
                    <div class="col-md-2">
                        <label for="">Project</label>
                        <select name="project_id" class="form-control @error('project_id') is-invalid @enderror">
                            @foreach ($projects as $item)
                                <option value="{{ $item->id }}" {{ $item->id == $transaction->project_id ? 'selected' : '' }}>{{ $item->project }}</option>                                        
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('project_id')])
                    </div>
                    <div class="col-md-4">
                        <label for="">Payee Name</label>
                        <input type="text" class="form-control @error('payee') is-invalid @enderror" name="payee" value="{{ $transaction->payee }}" required>
                        @include('errors.inline', ['message' => $errors->first('payee')])
                    </div>
                </div>
                <div class="form-row mb-3">
                    <div class="col-md-4">
                        <label for="">Due Date</label>
                        <input type="text" class="form-control" value="{{ $transaction->due_at }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="">Requested by</label>
                        <input type="text" class="form-control" value="{{ $transaction->requested->name }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="">Prepared by</label>
                        <h5>{{ $transaction->owner->name }}</h5>
                    </div>
                    {{-- <div class="col-md-2">
                        <label for="">For Deposit?</label>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input @error('is_deposit') is-invalid @enderror" name="is_deposit" id="is_deposit" value="1" {{ $transaction->is_deposit == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_deposit">Yes</label>
                        </div>
                        @include('errors.inline', ['message' => $errors->first('is_deposit')])
                    </div> --}}
                    <div class="card col-md-12 mt-4">
                        <div class="card-header font-weight-bold">
                            Select Transaction Category
                        </div>
                        <div class="card-body pb-1 row">                            
                            <div class="col-md-4">
                                <div class="callout callout-info py-2 px-3 mx-1 row">
                                    <div class="col-md-2">
                                        <input type="radio" name="trans_category" value="{{ config('global.trans_category')[0] }}" class="form-control m-auto outline-0" {{ $transaction->is_deposit == 0 ? 'checked' : '' }}>
                                    </div>
                                    <div class="col-md-10">
                                        <h6 class="font-weight-bold mb-1">Regular Transaction</h6>
                                        <p>Lorem ipsum dolor sit amet, consectetur, et dolore magna aliqua.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="callout callout-danger py-2 px-3 mx-1 row">
                                    <div class="col-md-2">
                                        <input type="radio" name="trans_category" value="{{ config('global.trans_category')[1] }}" class="form-control m-auto outline-0"  {{ $transaction->is_deposit == 1 ? 'checked' : '' }}>
                                    </div>
                                    <div class="col-md-10">
                                        <h6 class="font-weight-bold mb-1">Human Resource Transaction</h6>
                                        <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="callout callout-success py-2 px-3 mx-1 row">
                                    <div class="col-md-2">
                                        <input type="radio" name="trans_category" value="{{ config('global.trans_category')[2] }}" class="form-control m-auto outline-0"  {{ $transaction->is_bills == 1 ? 'checked' : '' }}>
                                    </div>
                                    <div class="col-md-10">
                                        <h6 class="font-weight-bold mb-1">Bills Payment</h6>          
                                        <p>Excepteur sint non proident, sunt in culpa qui mollit anim id.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <div class="mt-4">
                            <a href="/transaction/view/{{ $transaction->id }}" class="mr-3">Cancel</a>
                            <input type="submit" class="btn btn-primary" value="Save">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection