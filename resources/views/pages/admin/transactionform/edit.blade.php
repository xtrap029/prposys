@extends('layouts.app')

@section('title', $page_title)

@section('content')
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
                            {{ config('global.trans_category_label_edit_form')[1] }}
                        @elseif ($transaction->is_bills)    
                            {{ config('global.trans_category_label_edit_form')[2] }}
                        @elseif ($transaction->is_hr)    
                            {{ config('global.trans_category_label_edit_form')[3] }}
                        @elseif ($transaction->is_bank)    
                            {{ config('global.trans_category_label_edit_form')[5] }}
                        @else
                            {{ config('global.trans_category_label_edit_form')[0] }}
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
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-nowrap">{{ strtoupper($transaction->trans_type) }}-{{ $transaction->trans_year }}-{{ sprintf('%05d',$transaction->trans_seq) }}</td>
                            <td class="text-nowrap">{{ $transaction->project->project }}</td>
                            <td class="text-nowrap">{{ $transaction->due_at }}</td>
                            <td class="text-nowrap">{{ $transaction->payee }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="form-row">
                    <div class="col-md-12 mb-2">
                        <label for="">Particulars</label>
                        @if ($trans_page_url == 'prpo')
                            <select name="particulars_id_single" class="form-control @error('particulars_id') is-invalid @enderror">
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
                </div>
                <div class="form-row">
                    <div class="col-md-7 mb-2">
                        <label for="">Project</label>
                        <select name="project_id" class="form-control @error('project_id') is-invalid @enderror">
                            @foreach ($projects as $item)
                                <option value="{{ $item->id }}" {{ $item->id == $transaction->project_id ? 'selected' : '' }}>{{ $item->project }}</option>                                        
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('project_id')])
                    </div>
                    <div class="col-md-1 mb-2">
                        <label for="">Currency</label>
                        <select name="currency" class="form-control @error('currency') is-invalid @enderror">
                            @foreach (config('global.currency') as $key => $item)
                                <option value="{{ config('global.currency_label')[$key] }}" {{ $transaction->currency == config('global.currency_label')[$key] ? 'selected' : '' }}>{{ $item }}</option>
                            @endforeach 
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="">Amount</label>
                        <input type="number" class="form-control jsMath_trigger jsMath_validate_source @error('amount') is-invalid @enderror" step="0.01" name="amount" value="{{ $transaction->amount }}" required>
                        @include('errors.inline', ['message' => $errors->first('amount')])
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-8 mb-2">
                        <label for="">Purpose</label>
                        <textarea name="purpose" rows="1" class="form-control @error('purpose') is-invalid @enderror" required>{{ $transaction->purpose }}</textarea>
                        @include('errors.inline', ['message' => $errors->first('purpose')])
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="">Payee Name</label>
                        <input type="text" class="form-control @error('payee') is-invalid @enderror" name="payee" value="{{ $transaction->payee }}" required>
                        @include('errors.inline', ['message' => $errors->first('payee')])
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-3 mb-2">
                        <label for="">Due Date</label>
                        <input type="date" class="form-control @error('due_at') is-invalid @enderror" name="due_at" value="{{ $transaction->due_at }}" required>
                        @include('errors.inline', ['message' => $errors->first('due_at')])
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="">Requested by</label>
                        <select name="requested_id" class="form-control @error('requested_id') is-invalid @enderror">
                            @foreach ($users as $item)
                                <option value="{{ $item->id }}" {{ $item->id == $transaction->requested->id ? 'selected' : '' }}>{{ $item->name }}</option>                                        
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('requested_id')])
                    </div>
                    <div class="col-md-6 mb-2 {{ $transaction->trans_type == 'po' || $transaction->is_bills ? '' : 'd-none' }}">
                        <label for="">Statement of Account</label>
                        @if ($transaction->soa)
                            <a href="/storage/public/attachments/soa/{{ $transaction->soa }}" target="_blank" class="vlign--top ml-1">
                                <i class="material-icons mr-2 align-bottom">attachment</i>
                            </a>
                        @endif
                        {{-- <input type="file" name="soa" class="soa form_control" {{ ($transaction->trans_type == 'po' || $transaction->is_bills) && !$transaction->soa ? 'required' : '' }}> --}}
                        <input type="file" name="soa" class="soa form_control" {{ $transaction->trans_type == 'po' && !$transaction->soa ? 'required' : '' }}>
                    </div>
                </div>
                <div class="form-row">
                    <div class="card col-md-12 mt-4">
                        <div class="card-header font-weight-bold">
                            Select Transaction Category
                        </div>
                        <div class="card-body pb-1 row">                            
                            <div class="col-md-6 col-xl-4">
                                <div class="callout py-1 mx-1 row">
                                    <div class="col-2">
                                        <input type="radio" name="trans_category" value="{{ config('global.trans_category')[0] }}" class="vlign--baseline-middle m-auto outline-0" {{ $transaction->is_deposit == 0 && $transaction->is_bills == 0 && $transaction->is_hr == 0 && $transaction->is_reimbursement == 0 ? 'checked' : '' }}>
                                    </div>
                                    <div class="col-10 mt-2">
                                        <h6 class="font-weight-bold">{{ config('global.trans_category_label')[0] }}</h6>
                                        <p class="d-none">Lorem ipsum dolor sit amet, consectetur, et dolore magna aliqua.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <div class="callout py-1 mx-1 row">
                                    <div class="col-2">
                                        <input type="radio" name="trans_category" value="{{ config('global.trans_category')[1] }}" class="vlign--baseline-middle m-auto outline-0"  {{ $transaction->is_deposit == 1 ? 'checked' : '' }}>
                                    </div>
                                    <div class="col-10 mt-2">
                                        <h6 class="font-weight-bold">{{ config('global.trans_category_label')[1] }}</h6>
                                        <p class="d-none">Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <div class="callout py-1 mx-1 row">
                                    <div class="col-2">
                                        <input type="radio" name="trans_category" value="{{ config('global.trans_category')[2] }}" class="vlign--baseline-middle m-auto outline-0"  {{ $transaction->is_bills == 1 ? 'checked' : '' }}>
                                    </div>
                                    <div class="col-10 mt-2">
                                        <h6 class="font-weight-bold">{{ config('global.trans_category_label')[2] }}</h6>          
                                        <p class="d-none">Excepteur sint non proident, sunt in culpa qui mollit anim id.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <div class="callout py-1 mx-1 row">
                                    <div class="col-2">
                                        <input type="radio" name="trans_category" value="{{ config('global.trans_category')[3] }}" class="vlign--baseline-middle m-auto outline-0"  {{ $transaction->is_hr == 1 ? 'checked' : '' }}>
                                    </div>
                                    <div class="col-10 mt-2">
                                        <h6 class="font-weight-bold">{{ config('global.trans_category_label')[3] }}</h6>          
                                        <p class="d-none">Excepteur sint non proident, sunt in culpa qui mollit anim id.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <div class="callout py-1 mx-1 row">
                                    <div class="col-2">
                                        <input type="radio" name="trans_category" value="{{ config('global.trans_category')[5] }}" class="vlign--baseline-middle m-auto outline-0"  {{ $transaction->is_bank == 1 ? 'checked' : '' }}>
                                    </div>
                                    <div class="col-10 mt-2">
                                        <h6 class="font-weight-bold">{{ config('global.trans_category_label')[5] }}</h6>          
                                        <p class="d-none">Excepteur sint non proident, sunt in culpa qui mollit anim id.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-row mt-5">
                    <div class="col-md-5 {{ $transaction->is_deposit ? '' : 'd-none' }} mb-2">
                        <label for="">Payor</label>
                        <input type="text" name="payor" class="form-control @error('payor') is-invalid @enderror" value="{{ $transaction->payor }}" {{ $transaction->is_deposit ? 'required' : '' }}>
                        @include('errors.inline', ['message' => $errors->first('payor')])
                    </div>     
                    <div class="{{ $transaction->is_deposit ? 'col-md-5' : 'col-md-9' }} mb-2">
                        <label for="">COA Tagging</label>
                        <select name="coa_tagging_id" class="form-control @error('coa_tagging_id') is-invalid @enderror" required>
                            @foreach ($coa_taggings as $item)
                                <option value="{{ $item->id }}" {{ $item->id == $transaction->coa_tagging_id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('coa_tagging_id')])
                    </div>
                    <div class="{{ $transaction->is_deposit ? 'col-md-2' : 'col-md-3' }} mb-2">
                        <label for="">Tax Type</label>
                        <select name="vat_type_id" class="form-control">
                            @foreach ($vat_types as $item)
                            <option value="{{ $item->id }}" {{ $item->id == $transaction->vat_type_id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('vat_type_id')])
                    </div>

                    <div class="col-md-12">
                        <div class="jsReplicate jsMath mt-5">
                            <h4 class="text-center">Items</h4>
                            <div class="table-responsive">
                                <table class="table bg-white" style="min-width: 500px">
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
                                        @if (count($transaction->transaction_description) > 0)
                                            <tr>
                                                <td><input type="number" min="1" step="any" class="form-control jsMath_qty jsMath_trigger" name="qty[]" value="{{ $transaction->transaction_description[0]->qty }}" required></td>
                                                <td><input type="text" class="form-control" name="description[]" value="{{ $transaction->transaction_description[0]->description }}" required></td>
                                                <td>
                                                    <select name="particulars_id[]" class="form-control" required>
                                                        @foreach ($particulars as $item)
                                                        <option value="{{ $item->id }}" {{ $transaction->transaction_description[0]->particulars_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">{{ $transaction->currency }}</span>
                                                        </div>
                                                        <input type="number" class="form-control jsMath_amount jsMath_trigger" name="amount_desc[]" step="0.01" value="{{ $transaction->transaction_description[0]->amount }}" required>
                                                    </div> 
                                                </td>
                                            </tr>
                                        @else
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
                                                <td colspan="2">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">{{ $transaction->currency }}</span>
                                                        </div>
                                                        <input type="number" class="form-control jsMath_amount jsMath_trigger" name="amount_desc[]" step="0.01" value="{{ old('amount_desc.0') }}" required>
                                                    </div> 
                                                </td>
                                            </tr>
                                        @endif
                                        @foreach ($transaction->transaction_description as $key => $item)
                                            @if ($key > 0)
                                                <tr class="jsReplicate_template_item">
                                                    <td><input type="number" min="1" step="any" class="form-control jsMath_qty jsMath_trigger" name="qty[]" value="{{ $item->qty }}" required></td>
                                                    <td><input type="text" class="form-control" name="description[]" value="{{ $item->description }}" required></td>
                                                    <td>
                                                        <select name="particulars_id[]" class="form-control" required>
                                                            @foreach ($particulars as $particulars_item)
                                                                <option value="{{ $particulars_item->id }}" {{ $item->particulars_id == $particulars_item->id ? 'selected' : '' }}>{{ $particulars_item->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">{{ $transaction->currency }}</span>
                                                            </div>
                                                            <input type="number" class="form-control jsMath_amount jsMath_trigger" name="amount_desc[]" step="0.01" value="{{ $item->amount }}" required>
                                                        </div> 
                                                    </td>
                                                    <td><button type="button" class="btn btn-danger jsReplicate_remove jsMath_trigger"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                                                </tr>
                                            @endif
                                        @endforeach
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
                        <a href="/transaction-form/view/{{ $transaction->id }}" class="mr-3">Cancel</a>
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
                        <td>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ $transaction->currency }}</span>
                                </div>
                                <input type="number" class="form-control jsMath_amount jsMath_trigger" name="amount_desc[]" step="0.01" required>
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
                    
                    $(cls_2+'_validate').text($(cls_2+'_validate_source').val().toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","))

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