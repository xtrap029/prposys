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
                    <h1>{{ config('global.trans_category_label_make_form')[4] }}</h1>
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
                <thead>
                    <tr>
                        <tr>
                            <th colspan="5">Purpose</th>
                        </tr>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5">{{ $transaction->purpose }}</td>
                    </tr>
                </tbody>
            </table>
            <form action="" method="post" enctype="multipart/form-data" class="jsPreventMultiple">
                @csrf
                <input type="hidden" name="key" value="{{ strtoupper($transaction->trans_type) }}-{{ $transaction->trans_year }}-{{ sprintf('%05d',$transaction->trans_seq) }}">
                <input type="hidden" name="company" value="{{ $transaction->project->company->id }}">
                <div class="form-row mb-3">
                    <div class="col-md-6">
                        <label for="">Particulars</label>
                        @if ($trans_page_url == 'prpo')
                            <select name="particulars_id_single" class="form-control @error('particulars_id') is-invalid @enderror">
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
                    <div class="col-6">
                        <label for="">COA Tagging</label>
                        <select name="coa_tagging_id" class="form-control @error('coa_tagging_id') is-invalid @enderror" required>
                            @foreach ($coa_taggings as $item)
                                <option value="{{ $item->id }}" {{ $item->id == old('coa_tagging_id') ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('coa_tagging_id')])
                    </div>

                    <div class="col-12 jsReplicate jsMath mt-5">
                        <h4 class="text-center">Items</h4>
                        <table class="table bg-white">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Location/Route</th>
                                    <th class="text-center">Receipt</th>
                                    <th>Amount</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="jsReplicate_container">
                                <tr>
                                    <td><input type="date" class="form-control" name="date[]" value="{{ old('date.0') }}" required></td>
                                    <td>
                                        <select name="expense_type_id[]" class="form-control" required>
                                            @foreach ($expense_types as $item)
                                                <option value="{{ $item->id }}" {{ old('expense_type_id.0') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" name="description[]" value="{{ old('description.0') }}" required></td>
                                    <td><input type="text" class="form-control" name="location[]" value="{{ old('location.0') }}" required></td>
                                    <td class="text-center">
                                        <select name="receipt[]" class="form-control">
                                            <option value="1" {{ old('receipt.0') == 1 ? 'selected' : '' }}>Y</option>
                                            <option value="0" {{ old('receipt.0') == 0 ? 'selected' : '' }}>N</option>
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
                                @if (old('date'))
                                    @foreach (old('date') as $key => $item)
                                        @if ($key > 0)
                                            <tr class="jsReplicate_template_item">
                                                <td><input type="date" class="form-control" name="date[]" value="{{ old('date.'.$key) }}" required></td>
                                                <td>
                                                    <select name="expense_type_id[]" class="form-control" required>
                                                        @foreach ($expense_types as $expense_type)
                                                            <option value="{{ $expense_type->id }}" {{ old('expense_type_id.'.$key) == $expense_type->id ? 'selected' : '' }}>{{ $expense_type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control" name="description[]" value="{{ old('description.'.$key) }}" required></td>
                                                <td><input type="text" class="form-control" name="location[]" value="{{ old('location.'.$key) }}" required></td>
                                                <td class="text-center">
                                                    <select name="receipt[]" class="form-control">
                                                        <option value="1" {{ old('receipt.'.$key) == 1 ? 'selected' : '' }}>Y</option>
                                                        <option value="0" {{ old('receipt.'.$key) == 0 ? 'selected' : '' }}>N</option>
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
                                    <td colspan="7">
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

                    <div class="col-12 jsReplicate mt-5 pt-5">
                        <h4 class="text-center">Attachments</h4>
                        <div class="text-center mb-3">Attach receipts and documents here. Accepts .jpg, .png and .pdf file types, not more than 5mb each.</div>
                        <table class="table bg-white">
                            <thead>
                                <tr>
                                    <th class="w-25">File</th>
                                    <th class="w-75">Description</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="jsReplicate_container">
                                <tr>
                                    <td><input type="file" name="file[]" class="form-control overflow-hidden" required></td>
                                    <td colspan="2"><input type="text" name="attachment_description[]" class="form-control" value="{{ old('attachment_description.0') }}" required></td>
                                </tr>
                                @if (old('attachment_description'))
                                    @foreach (old('attachment_description') as $key => $item)
                                        @if ($key > 0)
                                            <tr>
                                                <td><input type="file" name="file[]" class="form-control overflow-hidden" required></td>
                                                <td><input type="text" name="attachment_description[]" class="form-control" value="{{ old('attachment_description.'.$key) }}" required></td>
                                                <td><button type="button" class="btn btn-danger jsReplicate_remove jsMath_trigger"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div class="text-center">
                            <button type="button" class="btn btn-secondary jsReplicate_add"><i class="nav-icon material-icons icon--list">add_box</i> Add More</button>
                        </div>
                    </div>
                    
                    <div class="col-md-12 text-right mt-4">
                        <a href="/transaction/{{ $trans_page_url }}/{{ $transaction->project->company_id }}" class="mr-3">Cancel</a>
                        <input type="submit" class="btn btn-primary jsMath_submit" value="Save">
                    </div>
                </div>
            </form>

            <table class="d-none">
                <tbody class="jsReplicate_template">
                    <tr class="jsReplicate_template_item">
                        <td><input type="date" class="form-control" name="date[]" required></td>
                        <td>
                            <select name="expense_type_id[]" class="form-control" required>
                                @foreach ($expense_types as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" class="form-control" name="description[]" required></td>
                        <td><input type="text" class="form-control" name="location[]" required></td>
                        <td class="text-center">
                            <select name="receipt[]" class="form-control">
                                <option value="1">Y</option>
                                <option value="0">N</option>
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

            <table class="d-none">
                <tbody class="jsReplicate_template">
                    <tr class="jsReplicate_template_item">
                        <td><input type="file" name="file[]" class="form-control overflow-hidden" required></td>
                        <td><input type="text" name="attachment_description[]" class="form-control" required></td>
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
                        sum += parseFloat($(this).val() != "" ? $(this).val() : 0)
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