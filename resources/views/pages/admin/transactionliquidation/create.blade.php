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
                        {{ $transaction->is_bank ? 'Deposit Form' : 'Liquidate '.strtoupper($transaction->trans_type) }}</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <table class="table col-md-6">
                    <tr>
                        <td class="font-weight-bold">Transaction</td>
                        <td>{{ strtoupper($transaction->trans_type) }}-{{ $transaction->trans_year }}-{{ sprintf('%05d',$transaction->trans_seq) }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Requested by</td>
                        <td>{{ $transaction->requested->name }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Project</td>
                        <td>{{ $transaction->project->project }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">COA Tagging</td>
                        <td>{{ $transaction->coatagging->name }}</td>
                    </tr>
                </table>
                <div class="col-md-6">
                    <div>
                        <label for="" class="font-weight-bold">Purpose</label>
                        <p>{{ $transaction->purpose }}</p>
                    </div>
                </div>
            </div>
            @if ($transaction->is_bank)
                <div class="row my-4 pb-3">
                    <div class="col-12 font-weight-bold">
                        <h5 class="pb-2 border-bottom">Issue Payment Details</h5>
                    </div>
                    <div class="col-lg-3">
                        <label for="" class="font-weight-bold">Type</label>
                        <p>{{ $transaction->control_type }}</p>
                    </div>
                    <div class="col-lg-3">
                        <label for="" class="font-weight-bold">No.</label>
                        <p>{{ $transaction->control_no }}</p>
                    </div>
                    <div class="col-lg-3">
                        <label for="" class="font-weight-bold">Released Date</label>
                        <p>{{ $transaction->released_at }}</p>
                    </div>
                    <div class="col-lg-3">
                        <label for="" class="font-weight-bold">Released By</label>
                        <p>{{ $transaction->releasedby->name }}</p>
                    </div>
                    <div class="col-lg-3">
                        <label for="" class="font-weight-bold">Debited To</label>
                        <p>{{ $transaction->project->company->name }}</p>
                    </div>
                    <div class="col-lg-3">
                        <label for="" class="font-weight-bold">Credited To</label>
                        <p>{{ $transaction->formcompany->name }}</p>
                    </div>
                    <div class="col-lg-3">
                        <label for="" class="font-weight-bold">Amount</label>
                        <p>
                            <span class="font-weight-bold">{{ $transaction->currency }} {{ number_format($transaction->amount, 2, '.', ',') }}</span>
                            to
                            <span class="font-weight-bold">
                                {{ $transaction->currency_2 ?: $transaction->currency }}
                                (<span class="font-weight-bold">{{ $transaction->currency_2_rate }})
                            </span>
                            =
                            <span class="font-weight-bold">{{ number_format($transaction->amount_issued, 2, '.', ',') }}</span>
                        </p>
                    </div>
                    @if ($transaction->form_service_charge && $transaction->form_service_charge > 0)
                        <div class="col-lg-3">
                            <label for="" class="font-weight-bold">Service Charge</label>
                            <p>{{ number_format($transaction->form_service_charge, 2, '.', ',') }}</p>
                        </div>
                    @endif
                </div>
            @endif
            <form action="" method="post" enctype="multipart/form-data" class="jsPreventMultiple" id="pageForm">
                @csrf
                <input type="hidden" name="key" value="{{ strtoupper($transaction->trans_type) }}-{{ $transaction->trans_year }}-{{ sprintf('%05d',$transaction->trans_seq) }}">
                <input type="hidden" name="company" value="{{ $transaction->project->company->id }}">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded" role="alert">
                        @foreach ($errors->all() as $key => $error)
                            <div>{{ $error }}</div>
                        @endforeach
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if (!$transaction->is_deposit && !$transaction->is_bills && !$transaction->is_hr && !$transaction->is_bank)
                    <div class="jsReplicate jsMath mt-5">
                        <h4 class="text-center">Items</h4>
                        <div class="table-responsive">
                            <table class="table bg-white" style="min-width: 1000px">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Project</th>
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
                                            <select name="project_id[]" class="form-control" required>
                                                @foreach ($projects as $item)
                                                    <option value="{{ $item->id }}" {{ old('project_id.0') == $item->id ? 'selected' : '' }}>{{ $item->project }}</option>
                                                @endforeach
                                            </select>
                                        </td>
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
                                                        <select name="project_id[]" class="form-control" required>
                                                            @foreach ($projects as $project)
                                                                <option value="{{ $project->id }}" {{ old('project_id.'.$key) == $project->id ? 'selected' : '' }}>{{ $project->project }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
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
                                                Total Amount: <span class="font-weight-bold jsMath_sum jsMath_alert">0</span> / <span class="font-weight-bold jsMath_validate">{{ number_format($transaction->amount_issued, 2, '.', ',') }}</span>
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
                @elseif($transaction->is_deposit || $transaction->is_bank)
                    <div class="row">                                            
                        <div class="col-md-3 mb-2">
                            <label for="" class="font-weight-bold">Mode</label>
                            <select name="depo_type" class="depo_type form-control @error('depo_type') is-invalid @enderror" required>
                                @foreach (config('global.deposit_type') as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                            @include('errors.inline', ['message' => $errors->first('depo_type')])
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="" class="font-weight-bold">Type / Account #</label>
                            <select name="depo_bank_branch_id" class="form-control @error('depo_bank_branch_id') is-invalid @enderror" required>
                                @foreach ($banks as $item)
                                    <optgroup label="{{ $item->name }}">
                                        @foreach ($item->bankbranches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @include('errors.inline', ['message' => $errors->first('depo_bank_branch_id')])
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="font-weight-bold">Reference Code</label>
                            <input type="text" name="depo_ref" class="depo_ref form-control @error('depo_ref') is-invalid @enderror" required>
                            @include('errors.inline', ['message' => $errors->first('depo_ref')])
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label for="" class="font-weight-bold">{{ $transaction->is_bank ? 'Processed' : 'Deposited' }} By</label>
                            <select name="liquidation_approver_id" class="form-control @error('liquidation_approver_id') is-invalid @enderror">
                                @foreach ($users as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == Auth::user()->id ? 'selected' : '' }}>{{ $item->name }}</option>                                        
                                @endforeach
                            </select>
                            @include('errors.inline', ['message' => $errors->first('requested_id')])
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="" class="font-weight-bold">Date Deposited</label>
                            <input type="date" name="depo_date" class="form-control @error('depo_date') is-invalid @enderror" required>
                            @include('errors.inline', ['message' => $errors->first('depo_date')])
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="font-weight-bold">Received By</label>
                            <input type="text" name="depo_received_by" class="form-control @error('depo_received_by') is-invalid @enderror" required>
                            @include('errors.inline', ['message' => $errors->first('depo_received_by')])
                        </div>
                    </div>
                    {{-- <div class="row {{ !$transaction->is_bank ? 'd-none' : '' }}">
                        <div class="col-md-3 mb-2">
                            <label for="" class="font-weight-bold">Currency</label>
                            <select name="currency_2" id="currencyChange" class="form-control @error('currency_2') is-invalid @enderror">
                                @foreach (config('global.currency') as $key => $item)
                                    <option value="{{ config('global.currency_label')[$key] }}" {{ $transaction->currency == config('global.currency_label')[$key] ? 'selected' : '' }}>{{ $item }}</option>
                                @endforeach
                            </select>
                            @include('errors.inline', ['message' => $errors->first('currency_2')])
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="" class="font-weight-bold">FX Rate</label>
                            <input type="number" id="currencyFx" class="form-control @error('currency_2_rate') is-invalid @enderror" step="0.01" name="currency_2_rate" value="1" readonly required>
                            @include('errors.inline', ['message' => $errors->first('currency_2_rate')])
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="" class="font-weight-bold">Issued Amount (Converted)</label>
                            <div class="pt-2">{{ $transaction->currency }} <span id="currencyAmount">{{ number_format($transaction->amount_issued, 2, '.', ',') }}</span></div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="" class="font-weight-bold">Bank Transfer Amount</label>
                            <div class="pt-2">{{ $transaction->currency }} {{ number_format($transaction->amount, 2, '.', ',') }}</div>
                        </div>
                    </div> --}}
                @endif
                <div class="jsReplicate mt-5 pt-5">
                    <h4 class="text-center">Attachments</h4>
                    <div class="text-center mb-3">Attach receipts and documents here. Accepts .jpg, .png and .pdf file types, not more than 5mb each.</div>
                    <div class="table-responsive">
                        <table class="table bg-white" style="min-width: 500px">
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
                <div class="mt-5 pt-5 table-responsive">
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
                    <input type="submit" class="btn btn-primary" value="Save">
                </div>
            </form>

            @if (!$transaction->is_deposit && !$transaction->is_bills && !$transaction->is_hr && !$transaction->is_bank)
                <table class="d-none">
                    <tbody class="jsReplicate_template">
                        <tr class="jsReplicate_template_item">
                            <td><input type="date" class="form-control" name="date[]" required></td>
                            <td>
                                <select name="project_id[]" class="form-control" required>
                                    @foreach ($projects as $item)
                                        <option value="{{ $item->id }}">{{ $item->project }}</option>
                                    @endforeach
                                </select>
                            </td>
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
            @endif

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

            checkDepo()

            $('.depo_type').on('change', function() {
                checkDepo()
            })

            function checkDepo() {
                if ($('.depo_type').val() == 'CASH') {
                    $('.depo_ref').removeAttr('required')
                } else {
                    $('.depo_ref').prop('required',true)
                }
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
                    } else {
                        $(cls_2+'_alert').removeClass('text-danger').addClass('text-success')
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

            // $('#currencyChange').change(function() {
            //     if ($(this).val() != '{{ $transaction->currency }}') {
            //         $('#currencyFx').attr("readonly", false) 
            //     } else {
            //         $('#currencyFx').val(1).attr("readonly", "readonly") 
            //     }

            //     $('#currencyFx').trigger('change')
            // })

            // $('#currencyFx').on('keyup keypress blur change', function() {
            //     convertedAmt = parseFloat($(this).val() == "" ? 0 : $(this).val()) * parseFloat('{{ $transaction->amount_issued }}')
            //     $('#currencyAmount').text(convertedAmt.toLocaleString())
            // })
        })
    </script>
@endsection