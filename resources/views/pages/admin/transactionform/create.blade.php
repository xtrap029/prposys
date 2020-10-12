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
                <input type="hidden" name="company" value="{{ $transaction->project->company->id }}">
                <div class="form-row mb-3">
                    <div class="col-md-8">
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
                    {{-- <div class="col-md-5">
                        <label for="">Description</label>
                        <input type="text" name="expense_type_description" value="{{ old('expense_type_description') ?? $transaction->is_deposit ? $transaction->purpose : ''  }}" class="form-control @error('expense_type_description') is-invalid @enderror" required>
                        @include('errors.inline', ['message' => $errors->first('expense_type_description')])
                    </div> --}}
                    <div class="col-md-4">
                        <label for="">Tax Type</label>
                        <select name="vat_type_id" class="form-control">
                            @foreach ($vat_types as $item)
                            <option value="{{ $item->id }}" {{ $item->id == old('vat_type_id') ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('vat_type_id')])
                    </div>

                    <div class="col-md-12">
                        <div class="jsReplicate jsMath mt-5">
                            <h4 class="text-center">Items</h4>
                            <table class="table bg-white">
                                <thead>
                                    <tr>
                                        <th style="width: 100px">Qty.</th>
                                        <th>Description</th>
                                        <th>Particulars</th>
                                        <th>Amount</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="jsReplicate_container">
                                    <tr>
                                        <td><input type="number" min="1" step="any" class="form-control jsMath_qty jsMath_trigger" name="qty[]" value="{{ old('qty.0') ? old('qty.0') : 1 }}" required></td>
                                        <td><input type="text" class="form-control" name="description[]" value="{{ old('description.0') }}" required></td>
                                        <td>
                                            <select name="particulars_id[]" class="form-control" required>
                                                @foreach ($particulars as $item)
                                                    <option value="{{ $item->id }}" {{ old('particulars_id.0') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td colspan="2"><input type="number" class="form-control jsMath_amount jsMath_trigger" name="amount[]" step="0.01" value="{{ old('amount.0') }}" required></td>
                                    </tr>
                                    @if (old('qty'))
                                        @foreach (old('qty') as $key => $item)
                                            @if ($key > 0)
                                                <tr class="jsReplicate_template_item">
                                                    <td><input type="number" min="1" step="any" class="form-control jsMath_qty jsMath_trigger" name="qty[]" value="{{ old('qty.'.$key) }}" required></td>
                                                    <td><input type="text" class="form-control" name="description[]" value="{{ old('description.'.$key) }}" required></td>
                                                    <td>
                                                        <select name="particulars_id[]" class="form-control" required>
                                                            @foreach ($particulars as $particulars_item)
                                                                <option value="{{ $particulars_item->id }}" {{ old('particulars_id.'.$key) == $particulars_item->id ? 'selected' : '' }}>{{ $particulars_item->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td><input type="number" class="form-control jsMath_amount jsMath_trigger" name="amount[]" step="0.01" value="{{ old('amount.'.$key) }}" required></td>
                                                    <td><button type="button" class="btn btn-danger jsReplicate_remove jsMath_trigger"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5">
                                            <div class="float-left font-weight-bold jsMath_alert">
                                                {{ __('messages.required_amount') }}
                                            </div>
                                            <div class="float-right">
                                                Total Amount: <span class="font-weight-bold jsMath_sum jsMath_alert">0</span> / <span class="font-weight-bold jsMath_validate">{{ number_format($transaction->amount, 2, '.', ',') }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="text-center">
                                <button type="button" class="btn btn-secondary jsReplicate_add"><i class="nav-icon material-icons icon--list">add_box</i> Add More</button>
                            </div>
                        </div>
                    </div>  
                    
                    <div class="col-md-12 text-right mt-4">
                        <a href="/transaction-form/{{ $trans_page_url }}/{{ $transaction->project->company_id }}" class="mr-3">Cancel</a>
                        <input type="submit" class="btn btn-primary jsMath_submit" value="Save">
                    </div>
                </div>
            </form>

            <table class="d-none">
                <tbody class="jsReplicate_template">
                    <tr class="jsReplicate_template_item">
                        <td><input type="number" min="1" step="any" class="form-control jsMath_qty jsMath_trigger" name="qty[]" value="1" required></td>
                        <td><input type="text" class="form-control" name="description[]" required></td>
                        <td>
                            <select name="particulars_id[]" class="form-control" required>
                                @foreach ($particulars as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="number" class="form-control jsMath_amount jsMath_trigger" name="amount[]" step="0.01" required></td>
                        <td><button type="button" class="btn btn-danger jsReplicate_remove jsMath_trigger"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
@endsection

@section('script')
    <script type="text/javascript">
        $(function() {
            if ($('.jsReplicate')[0]){
                cls = '.jsReplicate'
                
                $(cls+'_add').click(function() {
                    container = $(this).parents(cls).find(cls+'_container')
                    clsIndex = $(this).parents(cls).index(cls)
                    $(cls+'_template:eq('+clsIndex+')').children().clone().appendTo(container)
                })

                $(document).on('click', cls+'_remove', function() {
                    $(this).parents(cls+'_template_item').remove()
                })
            }

            if ($('.jsMath')[0]){
                cls_2 = '.jsMath'

                $(document).on('keyup click', cls_2+'_trigger', function() {
                    jsMatch_calc()
                })

                jsMatch_calc()

                function jsMatch_calc() {
                    sum = 0
                    $(cls_2+'_amount').each(function() {
                        if ($(this).val().length !== 0) {
                            qty = $(cls_2+'_qty').eq($(this).index(cls_2+'_amount')).val() != "" ? $(cls_2+'_qty').eq($(this).index(cls_2+'_amount')).val() : 0
                            sum += parseFloat($(this).val()) * parseFloat(qty)
                        }
                    })
                    $(cls_2+'_sum').text(sum.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","))

                    if ($(cls_2+'_sum').text() != $(cls_2+'_validate').text()) {
                        $(cls_2+'_alert').addClass('text-danger').removeClass('text-success')
                        $(cls_2+'_submit').prop('disabled', true)
                    } else {
                        $(cls_2+'_alert').removeClass('text-danger').addClass('text-success')
                        $(cls_2+'_submit').prop('disabled', false)
                    }
                }
            }
        })
    </script>
@endsection