@extends('layouts.app')

@section('title', 'Make '.strtoupper($transaction->trans_type))

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
                    <h1>Make {{ strtoupper($transaction->trans_type) }}</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="" method="post">
                @csrf

                <div class="form-row mb-3">
                    <div class="col-md-6">
                        <label for="">COA Tagging</label>
                        <select name="coa_tagging_id" class="form-control @error('coa_tagging_id') is-invalid @enderror" required>
                            @foreach ($coa_taggings as $item)
                                <option value="{{ $item->id }}" {{ $item->id == old('coa_tagging_id') ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('coa_tagging_id')])
                    </div>
                    <div class="col-md-6">
                        <label for="">Expense Type / Description</label>
                        <select name="expense_type_id" class="form-control @error('expense_type_id') is-invalid @enderror" required>
                            @foreach ($expense_types as $item)
                                <option value="{{ $item->id }}" {{ $item->id == old('expense_type_id') ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('expense_type_id')])
                    </div>
                </div>
                <div class="form-row mb-3">
                    <div class="col-md-4">
                        <label for="">Form Type</label>
                        <select name="" class="form-control"></select>
                    </div>
                    <div class="col-md-8">
                        <label for="">Purpose</label>
                        <textarea name="purpose" rows="3" class="form-control @error('purpose') is-invalid @enderror" required></textarea>
                        @include('errors.inline', ['message' => $errors->first('purpose')])
                    </div>
                </div>
                <div class="form-row mb-3">
                    <div class="col-md-12 text-right">
                        <div class="mt-4">
                            <a href="/transaction-form/{{ $trans_page }}/{{ $transaction->project->company_id }}" class="mr-3">Cancel</a>
                            <input type="submit" class="btn btn-primary" value="Save">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection