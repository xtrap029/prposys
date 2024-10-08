@extends('layouts.app')

@section('title', 'Make Form')

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
                    <h1>{{ config('global.trans_category_label_make_form')[4] }}</h1>
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
                    <thead>
                        <tr>
                            <tr>
                                <th colspan="5">Purpose</th>
                            </tr>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5">
                                @if ($config_confidential)
                                    -
                                @else
                                    {{ $transaction->purpose_option_id ? $transaction->purposeOption->code.' - '.$transaction->purposeOption->name : $transaction->purpose }}
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <form action="" method="post" enctype="multipart/form-data" class="jsPreventMultiple" id="pageForm">
                @csrf
                <input type="hidden" name="key" value="{{ strtoupper($transaction->trans_type) }}-{{ $transaction->trans_year }}-{{ sprintf('%05d',$transaction->trans_seq) }}">
                <input type="hidden" name="company" value="{{ $transaction->project->company->id }}">
                <div class="form-row">
                    @if (1==0)
                        <div class="col-md-6 mb-2">
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
                    @endif
                    <div class="col-md-6 mb-2">
                        <label for="">Category / Class</label>
                        <select name="coa_tagging_id" id="coa_tagging_id" class="form-control chosen-select @error('coa_tagging_id') is-invalid @enderror" required>
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
                    
                    <div class="col-12 jsReplicate jsMath mt-5">
                        <h4 class="text-center">Items</h4>
                        <div class="table-responsive">
                            <table class="table bg-white" style="min-width: 1000px">
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
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-secondary jsReplicate_add"><i class="nav-icon material-icons icon--list">add_box</i> Add More</button>
                        </div>
                    </div>

                    <div class="col-12 jsReplicate mt-5 pt-5">
                        <h4 class="text-center">Attachments</h4>
                        <div class="text-center mb-3">Attach receipts and documents here. Accepts .jpg, .png and .pdf file types, not more than {{ config('global.max_tf_reimbursement') }} each.</div>
                        <div class="table-responsive">
                            <table class="table bg-white" style="min-width: 1000px">
                                <thead>
                                    <tr>
                                        <th class="w-25">File</th>
                                        <th class="w-75">Description</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="jsReplicate_container">
                                    <tr class="jsReplicate_template_item">
                                        <td><input type="file" name="file[]" class="form-control overflow-hidden" required></td>
                                        <td><input type="text" name="attachment_description[]" class="form-control" value="{{ old('attachment_description.0') }}" required></td>
                                        <td><button type="button" class="btn btn-danger jsReplicate_remove jsMath_trigger"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                                    </tr>
                                    @if (old('attachment_description'))
                                        @foreach (old('attachment_description') as $key => $item)
                                            @if ($key > 0)
                                                <tr class="jsReplicate_template_item">
                                                    <td><input type="file" name="file[]" class="form-control overflow-hidden" required></td>
                                                    <td><input type="text" name="attachment_description[]" class="form-control" value="{{ old('attachment_description.'.$key) }}" required></td>
                                                    <td><button type="button" class="btn btn-danger jsReplicate_remove jsMath_trigger"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-secondary jsReplicate_add"><i class="nav-icon material-icons icon--list">add_box</i> Add More</button>
                        </div>
                    </div>

                    <div class="col-md-12 mt-5 pt-5 table-responsive">
                        <table class="table bg-secondary rounded" style="min-width: 1000px">
                            <tbody>
                                <tr>
                                    <td class="w-25">
                                        <div class="mb-1 font-weight-bold">Batch Upload</div>
                                        <input type="file" name="zip" id="inputZip" class="form-control overflow-hidden" accept=".zip">
                                    </td>
                                    <td class="w-75 vlign--middle text-info">{!! __('messages.batch_upload') !!}</td>
                                </tr>
                            </tbody>
                        </table>
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

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.css" integrity="sha512-0nkKORjFgcyxv3HbE4rzFUlENUMNqic/EzDIeYCgsKa/nwqr2B91Vu/tNAu4Q0cBuG4Xe/D1f/freEci/7GDRA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript">
        $('.chosen-select').chosen();
        
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

            $('#pageForm').submit(function() {
                if (!$('#inputZip').val() && $("input[name='attachment_description[]']").length <= 1) {
                    alert('{{ __("messages.required_attachment") }}')
                    setTimeout(function() {
                        $('form.jsPreventMultiple [type="submit"]').removeAttr("disabled")
                    }, 1000);
                    return false
                }
            })

            $('#coa_tagging_id').change(function() {
                notes = $(this).find(':selected').data('notes')
                $('#coa_tagging_notes').text(notes != "" ? notes : '{{ __("messages.not_found") }}')
            })
        })
    </script>
@endsection