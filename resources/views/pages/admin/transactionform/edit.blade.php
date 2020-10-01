@extends('layouts.app')

@section('title', 'Edit Form')

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
                    <h1>Edit Form</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <table class="table">
                <thead>
                    <tr>
                        <th>Request No.</th>
                        <th>Project</th>
                        <th>Due Date</th>
                        <th>Vendor</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ strtoupper($transaction->trans_type) }}-{{ $transaction->trans_year }}-{{ sprintf('%05d',$transaction->trans_seq) }}</td>
                        <td>{{ $transaction->project->project }}</td>
                        <td>{{ $transaction->due_at }}</td>
                        <td>{{ $transaction->payee }}</td>
                        <td class="text-right">{{ $transaction->currency." ".number_format($transaction->amount, 2, '.', ',') }}</td>
                    </tr>
                </tbody>
            </table>
            <form action="" method="post">
                @csrf
                @method('put')
                <div class="form-row mb-3">
                    <div class="col-md-6">
                        <label for="">Particulars</label>
                        @if ($trans_page_url == 'prpo')
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
                        <select name="currency" class="form-control @error('currency') is-invalid @enderror">
                            <option value="PHP">PHP</option> 
                            <option value="USD">USD</option>   
                            <option value="EUR">EUR</option>   
                        </select>
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
                    <div class="col-md-3">
                        <label for="">Due Date</label>
                        <input type="date" class="form-control @error('due_at') is-invalid @enderror" name="due_at" value="{{ $transaction->due_at }}" required>
                        @include('errors.inline', ['message' => $errors->first('due_at')])
                    </div>
                    <div class="col-md-3">
                        <label for="">Requested by</label>
                        <select name="requested_id" class="form-control @error('requested_id') is-invalid @enderror">
                            @foreach ($users as $item)
                                <option value="{{ $item->id }}" {{ $item->id == $transaction->requested->id ? 'selected' : '' }}>{{ $item->name }}</option>                                        
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('requested_id')])
                    </div>
                </div>
                <div class="form-row mb-3 mt-5">
                    <div class="col-md-5">
                        <label for="">COA Tagging</label>
                        <select name="coa_tagging_id" class="form-control @error('coa_tagging_id') is-invalid @enderror" required>
                            @foreach ($coa_taggings as $item)
                                <option value="{{ $item->id }}" {{ $item->id == $transaction->coa_tagging_id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('coa_tagging_id')])
                    </div>
                    {{-- <div class="col-md-5">
                        <label for="">Expense Type / Description</label>
                        <select name="expense_type_id" class="form-control @error('expense_type_id') is-invalid @enderror" required>
                            @foreach ($expense_types as $item)
                                <option value="{{ $item->id }}" {{ $item->id == $transaction->expense_type_id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('expense_type_id')])
                    </div> --}}
                    <div class="col-md-5">
                        <label for="">Description</label>
                        <input type="text" name="expense_type_description" value="{{ $transaction->expense_type_description }}" class="form-control @error('expense_type_description') is-invalid @enderror" required>
                        @include('errors.inline', ['message' => $errors->first('expense_type_description')])
                    </div>
                    <div class="col-md-2">
                        <label for="">Tax Type</label>
                        <select name="vat_type_id" class="form-control">
                            @foreach ($vat_types as $item)
                            <option value="{{ $item->id }}" {{ $item->id == $transaction->vat_type_id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('vat_type_id')])
                    </div>
                    <div class="col-md-12 text-right mt-4">
                        <a href="/transaction-form/{{ $trans_page_url }}/{{ $transaction->project->company_id }}" class="mr-3">Cancel</a>
                        <input type="submit" class="btn btn-primary" value="Save">
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection