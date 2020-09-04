@extends('layouts.app')

@section('title', 'Make Form')

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
                    <h1>Make Form</h1>
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
                <input type="hidden" name="key" value="{{ strtoupper($transaction->trans_type) }}-{{ $transaction->trans_year }}-{{ sprintf('%05d',$transaction->trans_seq) }}">
                <div class="form-row mb-3">
                    <div class="col-md-5">
                        <label for="">COA Tagging</label>
                        <select name="coa_tagging_id" class="form-control @error('coa_tagging_id') is-invalid @enderror" required>
                            @foreach ($coa_taggings as $item)
                                <option value="{{ $item->id }}" {{ $item->id == old('coa_tagging_id') ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('coa_tagging_id')])
                    </div>
                    {{-- <div class="col-md-5">
                        <label for="">Expense Type / Description</label>
                        <select name="expense_type_id" class="form-control @error('expense_type_id') is-invalid @enderror" required>
                            @foreach ($expense_types as $item)
                                <option value="{{ $item->id }}" {{ $item->id == old('expense_type_id') ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('expense_type_id')])
                    </div> --}}
                    <div class="col-md-5">
                        <label for="">Description</label>
                        <input type="text" name="expense_type_description" value="{{ old('expense_type_description') }}" class="form-control @error('expense_type_description') is-invalid @enderror" required>
                        @include('errors.inline', ['message' => $errors->first('expense_type_description')])
                    </div>
                    <div class="col-md-2">
                        <label for="">Tax Type</label>
                        <select name="vat_type_id" class="form-control">
                            @foreach ($vat_types as $item)
                            <option value="{{ $item->id }}" {{ $item->id == old('vat_type_id') ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('vat_type_id')])
                    </div>
                    <div class="col-md-12 text-right mt-4">
                        <a href="/transaction/{{ $trans_page_url }}/{{ $transaction->project->company_id }}" class="mr-3">Cancel</a>
                        <input type="submit" class="btn btn-primary" value="Save">
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection