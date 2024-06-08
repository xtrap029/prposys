@extends('layouts.app')

@section('title', $page_title)

@section('content')
    <?php $config_confidential = 0; ?>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-lg-6 mb-2">
                    <h1>
                        <img src="/storage/public/images/companies/{{ $transaction->project->company->logo }}" alt="" class="thumb--xs mr-2">
                        {{ $transaction->project->company->name }}
                    </h1>
                </div>
                <div class="col-lg-6 text-lg-right mb-2">
                    <h1>
                        @if ($transaction->is_deposit)
                            {{ config('global.trans_category_label_make_form')[1] }}
                        @elseif ($transaction->is_bills)    
                            {{ config('global.trans_category_label_make_form')[2] }}
                        @elseif ($transaction->is_hr)    
                            {{ config('global.trans_category_label_make_form')[3] }}
                        @elseif ($transaction->is_bank)    
                            {{ config('global.trans_category_label_make_form')[5] }}
                        @else
                            {{ config('global.trans_category_label_make_form')[0] }}
                        @endif
                    </h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-nowrap">Request No.</th>
                            <th>Project</th>
                            <th class="text-nowrap">Due Date</th>
                            <th>Vendor</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-nowrap">{{ strtoupper($transaction->trans_type) }}-{{ $transaction->trans_year }}-{{ sprintf('%05d',$transaction->trans_seq) }}</td>
                            <td class="text-nowrap">{{ $transaction->project->project }}</td>
                            <td class="text-nowrap">{{ $transaction->due_at }}</td>
                            <td class="text-nowrap">{{ $transaction->vendor_id ? $transaction->vendor->name : $transaction->payee }}</td>
                            <td class="text-nowrap text-right">
                                @if ($config_confidential)
                                    -
                                @else
                                    {{ $transaction->currency." ".number_format($transaction->amount, 2, '.', ',') }}
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <form action="" method="post" class="jsPreventMultiple">
                @csrf
                <input type="hidden" name="key" value="{{ strtoupper($transaction->trans_type) }}-{{ $transaction->trans_year }}-{{ sprintf('%05d',$transaction->trans_seq) }}">
                <input type="hidden" name="company" value="{{ $transaction->project->company->id }}">
                @if (1==0)
                    <div class="form-row">
                        <div class="col-md-12 mb-2">
                            <label for="">Particulars</label>
                            @if ($trans_page_url == 'prpo')
                                <select name="particulars_id_single" class="form-control @error('particulars_id') is-invalid @enderror">
                                    <option value="">- Select -</option>
                                    @foreach ($particulars as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>                                        
                                    @endforeach
                                </select>
                                @include('errors.inline', ['message' => $errors->first('particulars_id')])
                            @else
                                <input type="text" class="form-control @error('particulars_custom') is-invalid @enderror" name="particulars_custom" required>
                                @include('errors.inline', ['message' => $errors->first('particulars_custom')])
                            @endif
                        </div>
                    </div>
                @endif
                <div class="form-row mt-5">
                    <div class="mb-2 col-12 {{ $transaction->is_deposit ? '' : 'd-none' }}">
                        <label for="">Payor</label>
                        <input type="text" name="payor" class="form-control @error('payor') is-invalid @enderror" {{ $transaction->is_deposit ? 'required' : '' }}>
                        @include('errors.inline', ['message' => $errors->first('payor')])
                    </div>      
                    <div class="mb-2 col-md-6">
                        <label for="">Category / Class</label>
                        <select name="coa_tagging_id" id="coa_tagging_id" class="form-control @error('coa_tagging_id') is-invalid @enderror" required>
                            @foreach ($coa_taggings as $item)
                                <option value="{{ $item->id }}" {{ $item->id == old('coa_tagging_id') ? 'selected' : '' }} data-notes="{{ $item->notes }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('coa_tagging_id')])
                    </div>
                    <div class="mb-2 col-md-6">
                        <label for="" class="invisible">Notes</label>
                        <p id="coa_tagging_notes" class="bg-white font-italic px-3 py-1 pt-2 rounded text-center">{{ __("messages.not_found") }}</p>
                    </div>
                    <div class="mb-2 col-md-6">
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
                            <div class="table-responsive">
                                <table class="table bg-white" style="min-width:500px">
                                    <thead>
                                        <tr>
                                            <th style="width: 100px">Qty.</th>
                                            <th>Description</th>
                                            <th>
                                                Expense Type
                                                <a href="" class="align-bottom" data-toggle="modal" data-target="#modal-particulars">
                                                    <i class="material-icons text-md align-middle pb-1">info</i>
                                                </a>
                                                <div class="modal fade" id="modal-particulars" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header border-0">
                                                                <h5 class="modal-title">Expense Types</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <table class="table font-weight-normal">
                                                                    <tbody>
                                                                        @foreach ($expense_types as $item)
                                                                            <tr>
                                                                                <td class="w-25 font-weight-bold">{{ $item->name }}</td>
                                                                                <td>{{ $item->notes }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </th>
                                            <th>Amount</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody class="jsReplicate_container">
                                        <tr>
                                            <td><input type="number" min="1" step="any" class="form-control jsMath_qty jsMath_trigger" name="qty[]" value="{{ old('qty.0') ? old('qty.0') : 1 }}" required></td>
                                            <td><input type="text" class="form-control" name="description[]" value="{{ old('description.0') }}" required></td>
                                            <td>
                                                <select name="expense_type_id[]" class="form-control" required>
                                                    <option value="">- Select -</option>
                                                    @foreach ($expense_types as $item)
                                                        <option value="{{ $item->id }}" {{ old('expense_type_id.0') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td colspan="2">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">{{ $transaction->currency }}</span>
                                                    </div>
                                                    <input type="number" class="form-control jsMath_amount jsMath_trigger" name="amount[]" step="0.01" value="{{ old('amount.0') }}" required>
                                                </div>                                            
                                            </td>
                                        </tr>
                                        @if (old('qty'))
                                            @foreach (old('qty') as $key => $item)
                                                @if ($key > 0)
                                                    <tr class="jsReplicate_template_item">
                                                        <td><input type="number" min="1" step="any" class="form-control jsMath_qty jsMath_trigger" name="qty[]" value="{{ old('qty.'.$key) }}" required></td>
                                                        <td><input type="text" class="form-control" name="description[]" value="{{ old('description.'.$key) }}" required></td>
                                                        <td>
                                                            <select name="expense_type_id[]" class="form-control" required>
                                                                <option value="">- Select -</option>
                                                                @foreach ($expense_types as $expense_type_item)
                                                                    <option value="{{ $expense_type_item->id }}" {{ old('expense_type_id.'.$key) == $expense_type_item->id ? 'selected' : '' }}>{{ $expense_type_item->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">{{ $transaction->currency }}</span>
                                                                </div>
                                                                <input type="number" class="form-control jsMath_amount jsMath_trigger" name="amount[]" step="0.01" value="{{ old('amount.'.$key) }}" required>
                                                            </div>  
                                                        </td>
                                                        <td><button type="button" class="btn btn-danger jsReplicate_remove jsMath_trigger"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" class="text-nowrap">
                                                <div class="text-right float-lg-left font-weight-bold jsMath_alert">
                                                    {{ __('messages.required_amount') }}
                                                </div>
                                                <div class="text-nowrap text-right float-lg-right">
                                                    Total Amount: <span class="font-weight-bold jsMath_sum jsMath_alert">0</span> / <span class="font-weight-bold jsMath_validate">{{ number_format($transaction->amount, 2, '.', ',') }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-secondary jsReplicate_add"><i class="nav-icon material-icons icon--list">add_box</i> Add More</button>
                            </div>
                        </div>
                    </div>  
                    
                    <div class="col-md-12 text-center text-lg-right my-5">
                        <a href="/transaction/{{ $trans_page_url }}/{{ $transaction->project->company_id }}" class="mr-3">Cancel</a>
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
                            <select name="expense_type_id[]" class="form-control" required>
                                <option value="">- Select -</option>
                                @foreach ($expense_types as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ $transaction->currency }}</span>
                                </div>
                                <input type="number" class="form-control jsMath_amount jsMath_trigger" name="amount[]" step="0.01" required>
                            </div>  
                        </td>
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

            $('#coa_tagging_id').change(function() {
                notes = $(this).find(':selected').data('notes')
                $('#coa_tagging_notes').text(notes != "" ? notes : '{{ __("messages.not_found") }}')
            })
        })
    </script>
@endsection