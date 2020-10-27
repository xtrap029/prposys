@extends('layouts.app')

@section('title', 'View '.strtoupper($transaction->trans_type))

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="form-row"> 
                <div class="col-md-6 mb-4">
                    <a href="/transaction-form/{{ $trans_page_url }}/{{ $transaction->project->company_id }}" class="btn btn-default"><i class="align-middle font-weight-bolder material-icons text-md">arrow_back_ios</i> Back</a>
                    <a data-toggle="modal" data-target="#modal-make" href="#_" class="btn btn-default"><i class="align-middle font-weight-bolder material-icons text-md">add</i> Add New</a>                
                    <a href="/transaction-form/reset/{{ $transaction->id }}" class="btn btn-default {{ $perms['can_reset'] ? '' : 'd-none' }}" onclick="return confirm('Are you sure?')"><i class="align-middle font-weight-bolder material-icons text-md">autorenew</i> Renew Edit Limit</a>
                </div>
                <div class="col-md-6 text-right mb-4">
                    <a href="/transaction-form/edit/{{ $transaction->id }}" class="btn btn-primary {{ $perms['can_edit'] ? '' : 'd-none' }}"><i class="align-middle font-weight-bolder material-icons text-md">edit</i> Edit</a>
                    {{-- <a href="#_" class="btn btn-success {{ $perms['can_approval'] ? '' : 'd-none' }}" data-toggle="modal" data-target="#modal-approval"><i class="align-middle font-weight-bolder material-icons text-md">grading</i> For Approval</a> --}}
                    <a href="/transaction-form/approval/{{ $transaction->id }}" class="btn btn-success {{ $perms['can_approval'] ? '' : 'd-none' }}" onclick="return confirm('Are you sure?')"><i class="align-middle font-weight-bolder material-icons text-md">grading</i> For Approval</a>
                    <a href="#_" class="btn btn-danger {{ $perms['can_cancel'] ? '' : 'd-none' }}" data-toggle="modal" data-target="#modal-cancel"><i class="align-middle font-weight-bolder material-icons text-md">delete</i> Cancel</a>
                    
                    <a href="#_" class="btn btn-danger {{ $perms['can_print'] ? '' : 'd-none' }}" onclick="window.open('/transaction-form/print/{{ $transaction->id }}','name','width=800,height=800')"><i class="align-middle font-weight-bolder material-icons text-md">print</i> Print</a>
                    <a href="#" class="btn btn-success {{ $perms['can_issue'] ? '' : 'd-none' }} px-4" data-toggle="modal" data-target="#modal-issue"><i class="align-middle font-weight-bolder material-icons text-md">check</i> {{ $transaction->is_deposit ? 'Deposit Notes' : 'Issue' }}</a>
                </div>

                <div class="modal fade" id="modal-make" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title">Select {{ $transaction->trans_type == 'pc' ? 'PC' : 'PR/PO' }} to make</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <form action="/transaction-form/create" method="get">
                                    <input type="text" name="key" class="form-control" placeholder="{{ $transaction->trans_type == 'pc' ? 'PC' : 'PR/PO' }}-XXXX-XXXXX" required>
                                    <input type="submit" class="btn btn-primary mt-2" value="Check">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($perms['can_cancel'])
                    <div class="modal fade" id="modal-cancel" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-md" role="document">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title">{{ __('messages.cancel_prompt') }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <form action="/transaction-form/cancel/{{ $transaction->id }}" method="post">
                                        @csrf
                                        @method('put')
                                        <textarea name="cancellation_reason" class="form-control @error('cancellation_reason') is-invalid @enderror" rows="3" placeholder="Cancellation Reason" required></textarea>
                                        @include('errors.inline', ['message' => $errors->first('cancellation_reason')])
                                        <input type="submit" class="btn btn-danger mt-2" value="Cancel Now">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($perms['can_approval'])
                    <div class="modal fade" id="modal-approval" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-md" role="document">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title">{{ __('messages.approval_prompt') }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <form action="/transaction-form/approval/{{ $transaction->id }}" method="post">
                                        @csrf
                                        @method('put')
                                        <div class="text-left"><label for="">Select Authorized Approver</label></div>
                                        <select name="form_approver_id" class="form-control @error('form_approver_id') is-invalid @enderror" required>
                                            @foreach ($approvers as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @include('errors.inline', ['message' => $errors->first('form_approver_id')])
                                        <input type="submit" class="btn btn-success mt-2" value="Confirm">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($perms['can_issue'])
                    <div class="modal fade" id="modal-issue" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-md" role="document">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title">{{ __('messages.issue_prompt') }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="/transaction-form/issue/{{ $transaction->id }}" method="post">
                                        @csrf
                                        @method('put')
                                        <div class="row mb-3">
                                            <div class="col-md-5">
                                                <label for="">Type</label>
                                                @if ($trans_page_url == 'prpo')
                                                    <select name="control_type" class="form-control">
                                                        @foreach (config('global.control_types') as $control_type)
                                                            <option value="{{ $control_type }}">{{ $control_type }}</option>
                                                        @endforeach
                                                    </select>
                                                    @include('errors.inline', ['message' => $errors->first('control_type')])
                                                @else
                                                    <h5>Petty Cash</h5>
                                                    <input type="hidden" name="control_type" value="{{ config('global.control_types_pc') }}">
                                                @endif
                                            </div>
                                            <div class="col-md-7">
                                                <label for="">No.</label>
                                                @if ($trans_page_url == 'prpo')
                                                    <input type="text" name="control_no" class="form-control @error('control_no') is-invalid @enderror" required>
                                                    @include('errors.inline', ['message' => $errors->first('control_no')])
                                                @else
                                                    <h5>{{ strtoupper($transaction->trans_type) }}-{{ $transaction->trans_year }}-{{ sprintf('%05d',$transaction->trans_seq) }}</h5>
                                                    <input type="hidden" name="control_no" value="{{ strtoupper($transaction->trans_type) }}-{{ $transaction->trans_year }}-{{ sprintf('%05d',$transaction->trans_seq) }}">
                                                @endif                                                
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-5">
                                                <label for="">{{ $transaction->is_deposit ? 'Date Deposited' : 'Release Date' }}</label>
                                                <input type="date" class="form-control @error('released_at') is-invalid @enderror" name="released_at" required>
                                                @include('errors.inline', ['message' => $errors->first('released_at')])
                                            </div>
                                            <div class="col-md-7">
                                                <label for="">Amount</label>
                                                <input type="number" class="form-control @error('amount_issued') is-invalid @enderror" name="amount_issued" step="0.01" value="{{ $transaction->amount }}" required>
                                                @include('errors.inline', ['message' => $errors->first('amount_issued')])
                                            </div>
                                        </div>
                                        <div class="form-row mb-3">
                                            <div class="col-md-12">
                                                <label for="">{{ $transaction->is_deposit ? 'Issued By' : 'Released By' }}</label>
                                                <select name="released_by_id" class="form-control @error('released_by_id') is-invalid @enderror" required>
                                                    @foreach ($released_by as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                                @include('errors.inline', ['message' => $errors->first('released_by_id')])
                                            </div>
                                        </div>
                                        <div class="text-center mt-2">
                                            <input type="submit" class="btn btn-success" value="{{ $transaction->is_deposit ? 'Save' : 'Issue Now' }}">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <div class="pb-2 mb-4 border-bottom">
                        <h1 class="d-inline-block mr-3">
                            <input type="text" value="{{ strtoupper($transaction->trans_type) }}-{{ $transaction->trans_year }}-{{ sprintf('%05d',$transaction->trans_seq) }}" class="input--label" readonly>
                            <a href="#_" class="btn btn-default vlign--top jsCopy" data-toggle="tooltip" data-placement="top" title="Copy to clipboard"><i class="align-middle font-weight-bolder material-icons text-md">content_copy</i></a>
                        </h1>
                        <h6 class="d-inline-block">
                            <img src="/storage/public/images/companies/{{ $transaction->project->company->logo }}" alt="" class="thumb--xxs mr-1">
                            {{ $transaction->project->company->name }}
                        </h6>
                    </div>
                    <div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="">Project</label>
                                <h5>{{ $transaction->project->project }}</h5>
                            </div>
                            <div class="col-md-6">
                                <label for="">Particulars</label>
                                <h5>{{ $trans_page_url == 'prpo' ? $transaction->particulars->name : $transaction->particulars_custom }}</h5>
                            </div>                            
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="">COA Tagging</label>
                                <h5>{{ $transaction->coatagging->name }}</h5>
                            </div>
                            <div class="col-md-6">
                                <label for="">Due Date</label>
                                <h5>{{ $transaction->due_at }}</h5>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="">Vendor / Payee</label>
                                <h5>{{ $transaction->payee }}</h5>
                            </div>
                            <div class="col-md-6">
                                <label for="">Tax Type</label>
                                <h5>{{ $transaction->form_vat_name && !in_array($transaction->status_id, config('global.generated_form')) ? $transaction->form_vat_name : $transaction->vattype->name }}</h5>
                            </div>
                        </div>
                        <div class="row mb-3">                            
                            <div class="col-md-6">
                                <label for="">Prepared By</label>
                                <h5>{{ $transaction->owner->name }}</h5>
                            </div>
                            <div class="col-md-6">
                                <label for="">Status</label>
                                <h5>{{ $transaction->status->name }}</h5>
                            </div>
                            <div class="col-md-6 mt-3 {{ $transaction->form_approver_id ? '' : 'd-none' }}">
                                <label for="">Authorized Approver</label>
                                <h5>{{ $transaction->form_approver_id ? $transaction->formapprover->name : '' }}</h5>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="">For Deposit?</label>
                                <h5>{{ $transaction->is_deposit ? 'Yes' : 'No' }}</h5>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="">Bills Payment?</label>
                                <h5>{{ $transaction->is_bills ? 'Yes' : 'No' }}</h5>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-11">
                                <label for="">Purpose</label>
                                <h6>{{ $transaction->purpose }}</h6>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Qty</th>
                                        <th>Description</th>
                                        <th>Particulars</th>
                                        <th class="text-right">Unit Price</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transaction->transaction_description as $item_desc)
                                        <tr>
                                            <td>{{ $item_desc->qty }}</td>
                                            <td>{{ $item_desc->description }}</td>
                                            <td>{{ $item_desc->particulars->name }}</td>
                                            <td class="text-right">{{ number_format($item_desc->amount, 2, '.', ',') }}</td>
                                            <td class="text-right">{{ number_format($item_desc->amount * $item_desc->qty, 2, '.', ',') }}</td>
                                        </tr>
                                    @endforeach
                                    {{-- <tr class="border-bottom-2">
                                        <td>1</td>
                                        <td>{{ $transaction->expense_type_description }}</td>
                                        <td class="text-right">{{ $transaction->currency }} {{ number_format($transaction->form_amount_unit && !in_array($transaction->status_id, config('global.generated_form')) ? $transaction->form_amount_unit : $transaction->amount, 2, '.', ',') }}</td>
                                        <td class="text-right">{{ $transaction->currency }} {{ number_format($transaction->form_amount_unit && !in_array($transaction->status_id, config('global.generated_form')) ? $transaction->form_amount_unit : $transaction->amount, 2, '.', ',') }}</td>
                                    </tr> --}}
                                    <tr class="font-weight-bold">
                                        <td colspan="3" class="text-right">
                                            {{ $transaction->form_amount_vat && !in_array($transaction->status_id, config('global.generated_form')) ? $transaction->form_amount_vat : $transaction->vattype->vat 
                                                + $transaction->form_amount_wht && !in_array($transaction->status_id, config('global.generated_form')) ? $transaction->form_amount_wht : $transaction->vattype->wht == 0 ? 'Total' : 'Subtotal' }}
                                        </td>
                                        <td colspan="2" class="text-right">{{ number_format($transaction->form_amount_subtotal && !in_array($transaction->status_id, config('global.generated_form')) ? $transaction->form_amount_subtotal : $transaction->custom_subtotal, 2, '.', ',') }}</td>
                                    </tr>
                                    @if ($transaction->custom_vat > 0 || ($transaction->form_amount_vat && !in_array($transaction->status_id, config('global.generated_form'))))
                                    <tr>
                                        <td colspan="3" class="text-right">VAT</td>
                                        <td colspan="2" class="text-right">{{ number_format($transaction->form_amount_vat && !in_array($transaction->status_id, config('global.generated_form')) ? $transaction->form_amount_vat : $transaction->custom_vat, 2, '.', ',') }}</td>
                                    </tr>
                                    @endif
                                    @if ($transaction->custom_wht > 0 || ($transaction->form_vat_wht && !in_array($transaction->status_id, config('global.generated_form'))))
                                    <tr>
                                        <td colspan="3" class="text-right">Less Withholding Tax</td>
                                        <td class="text-right">{{ $transaction->form_vat_wht && !in_array($transaction->status_id, config('global.generated_form')) ? $transaction->form_vat_name." (".$transaction->form_vat_wht."%)" : $transaction->vattype->name." (".$transaction->vattype->wht."%)" }}</td>
                                        <td class="text-right">({{ number_format($transaction->form_amount_wht ? $transaction->form_amount_wht : $transaction->custom_wht, 2, '.', ',') }})</td>
                                    </tr>
                                    @endif
                                    @if ($transaction->custom_vat > 0 || ($transaction->form_amount_payable && !in_array($transaction->status_id, config('global.generated_form'))))
                                    <tr class="font-weight-bold">
                                        <td colspan="3" class="text-right">Total Payable</td>
                                        <td colspan="2" class="text-right">{{ number_format($transaction->form_amount_payable && !in_array($transaction->status_id, config('global.generated_form')) ? $transaction->form_amount_payable : $transaction->custom_total_payable, 2, '.', ',') }}</td>
                                    </tr>
                                    @endif
                                    <tr class="font-weight-bold border-top-2">
                                        <td colspan="3" class="text-right">Amount</td>
                                        <td colspan="2" class="text-right">{{ $transaction->currency }} {{ number_format($transaction->form_amount_payable && !in_array($transaction->status_id, config('global.generated_form')) ? $transaction->form_amount_payable : $transaction->custom_total_payable, 2, '.', ',') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row mb-3">

                        </div>
                    </div>
                    @if ($transaction->status_id == 3)
                        <div class="pb-2 mt-5 mb-4 border-bottom">
                            <h3 class="d-inline-block mr-3">Cancellation Reason</h3>
                        </div>
                        <div>{{ $transaction->cancellation_reason }}</div>
                    @elseif ($transaction->status_id == 4)
                        <div class="pb-2 mt-3 mb-4 border-bottom">
                            <h3 class="d-inline-block mr-3">Issuing Details</h3>
                        </div>
                        <div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="">Type</label>
                                    <h5>{{ $transaction->control_type }}</h5>
                                </div>
                                <div class="col-md-6">
                                    <label for="">No.</label>
                                    <h5>{{ $transaction->control_no }}</h5>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="">{{ $transaction->is_deposit ? 'Date Deposited' : 'Released Date' }}</label>
                                    <h5>{{ $transaction->released_at }}</h5>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Amount</label>
                                    <h5>{{ $transaction->currency }} {{ number_format($transaction->amount_issued, 2, '.', ',') }}</h5>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="">{{ $transaction->is_deposit ? 'Issued By' : 'Released By' }}</label>
                                    <h5>{{ $transaction->releasedby->name }}</h5>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-sm-4">
                    <div class="pb-2 text-right"><h1>History</h1></div>
                    <table class="table table-striped table-bordered table-sm small">
                        <tbody>
                            @foreach ($logs as $item)
                                <tr>
                                    <td>
                                        <a href="#_" data-toggle="modal" data-target="#modal-{{ $item->id }}">
                                            @if ($item->description == 'created')
                                                <i class="align-middle font-weight-bolder material-icons text-md">add</i>
                                            @else
                                                <i class="align-middle font-weight-bolder material-icons text-md">edit</i>
                                            @endif
                                        </a>
                                        <div class="modal fade" id="modal-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title">{{ ucfirst($item->description) }} {{ Carbon::parse($item->created_at)->diffForHumans() }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @switch($item->description)
                                                            @case('created')
                                                                <table class="table table-sm table-bordered">
                                                                    <thead class="bg-gradient-gray">
                                                                        <tr>
                                                                            <th></th>
                                                                            <th>Value</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($item->changes['attributes'] as $key => $attribute)
                                                                            <tr>
                                                                                <td class="font-weight-bold">{{ ucwords($key) }}</td>
                                                                                <td>{{ $attribute }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                                @break
                                                            @case('updated')
                                                                    <table class="table table-sm table-bordered">
                                                                        <thead class="bg-gradient-gray">
                                                                            <tr>
                                                                                <th></th>
                                                                                <th>From</th>
                                                                                <th>To</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($item->changes['old'] as $key => $attribute)
                                                                                <tr>
                                                                                    <td class="font-weight-bold">{{ ucwords($key) }}</td>
                                                                                    <td>{{ $attribute }}</td>
                                                                                    <td>{{ $item->changes['attributes'][$key] }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                @break
                                                            @default                                                    
                                                        @endswitch
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->causer->name }}</td>
                                    <td class="text-right">{{ Carbon::parse($item->created_at)->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="text-center">
                        <div class="d-inline-block">
                            {{ $logs->links() }}
                        </div>
                    </div>
                </div>
            </div>        
        </div>
    </section>
@endsection

@section('script')
    <script type="text/javascript">
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()

            $('.jsCopy').click(function() {
                var copyText = $('.input--label')
                copyText.select()
                document.execCommand("copy")
                document.getSelection().removeAllRanges()

                $(this).attr('data-original-title', "Copied")
                    .tooltip('show')
            })

            $('.jsCopy').mouseleave(function() {
                $(this).attr("data-original-title","Copy to clipboard")
            })
        })
    </script>
@endsection