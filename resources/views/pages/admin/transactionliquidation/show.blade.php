@extends('layouts.app')

@section('title', 'View '.strtoupper($transaction->trans_type))

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="form-row"> 
                <div class="col-md-4 mb-4">
                    <a href="/transaction/{{ $trans_page_url }}/{{ $transaction->project->company_id }}" class="btn btn-default"><i class="align-middle font-weight-bolder material-icons text-md">arrow_back_ios</i> Back</a>
                    <a data-toggle="modal" data-target="#modal-liquidate" href="#_" class="btn btn-default"><i class="align-middle font-weight-bolder material-icons text-md">add</i> Add New</a>
                    <a href="/transaction-liquidation/reset/{{ $transaction->id }}" class="btn btn-default {{ $perms['can_reset'] ? '' : 'd-none' }}" onclick="return confirm('Are you sure?')"><i class="align-middle font-weight-bolder material-icons text-md">autorenew</i> Renew Edit Limit</a>
                </div>
                <div class="col-md-8 text-right mb-4">
                    <div class="modal fade" id="modal-liquidate" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-md" role="document">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title">Select {{ $transaction->trans_type == 'pc' ? 'PC' : 'PR/PO' }} to Liquidate</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <form action="/transaction-liquidation/create" method="get">
                                        <input type="hidden" name="company" value="{{ $transaction->project->company_id }}" required>
                                        <input type="text" name="key" class="form-control" placeholder="{{ $transaction->trans_type == 'pc' ? 'PC' : 'PR/PO' }}-XXXX-XXXXX" required>
                                        <input type="submit" class="btn btn-primary mt-2" value="Check">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="/transaction-liquidation/edit/{{ $transaction->id }}" class="btn btn-primary {{ $perms['can_edit'] ? '' : 'd-none' }}"><i class="align-middle font-weight-bolder material-icons text-md">edit</i> Edit</a>
                    {{-- <a href="#_" class="btn btn-success {{ $perms['can_approval'] ? '' : 'd-none' }}" data-toggle="modal" data-target="#modal-approval"><i class="align-middle font-weight-bolder material-icons text-md">grading</i> For Approval</a> --}}
                    <a href="/transaction-liquidation/approval/{{ $transaction->id }}" class="btn btn-success {{ $perms['can_approval'] ? '' : 'd-none' }}" onclick="return confirm('Are you sure?')"><i class="align-middle font-weight-bolder material-icons text-md">grading</i> For Approval</a>
                    <a href="#_" class="btn btn-danger" onclick="window.open('/transaction-form/print/{{ $transaction->id }}','name','width=800,height=800')"><i class="align-middle font-weight-bolder material-icons text-md">print</i> Print Generated {{ strtoupper($transaction->trans_type) }} Form</a>
                    <a href="#_" class="btn btn-danger {{ $perms['can_print'] ? '' : 'd-none' }}" onclick="window.open('/transaction-liquidation/print/{{ $transaction->id }}','name','width=800,height=800')"><i class="align-middle font-weight-bolder material-icons text-md">print</i> Print Liquidation</a>
                    <a href="#_" class="btn btn-success {{ $perms['can_clear'] ? '' : 'd-none' }} px-4" data-toggle="modal" data-target="#modal-clear"><i class="align-middle font-weight-bolder material-icons text-md">payments</i> Clear / Deposit</a>
                    <a href="#_" class="btn btn-primary {{ $perms['can_edit_cleared'] && $transaction->liq_balance != 0 ? '' : 'd-none' }} px-4" data-toggle="modal" data-target="#modal-clear-edit"><i class="align-middle font-weight-bolder material-icons text-md">{{ !$transaction->is_bills && !$transaction->is_hr ? 'edit' : 'visibility' }}</i> {{ !$transaction->is_bills && !$transaction->is_hr ? 'Edit' : 'View' }} Deposit Info</a>
                </div>
                
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
                                    <form action="/transaction-liquidation/approval/{{ $transaction->id }}" method="post">
                                        @csrf
                                        @method('put')
                                        <div class="text-left"><label for="">Select Authorized Approver</label></div>
                                        <select name="liquidation_approver_id" class="form-control @error('liquidation_approver_id') is-invalid @enderror" required>
                                            @foreach ($approvers as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @include('errors.inline', ['message' => $errors->first('liquidation_approver_id')])
                                        <input type="submit" class="btn btn-success mt-2" value="Confirm">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($perms['can_clear'])
                    <div class="modal fade" id="modal-clear" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title">{{ __('messages.clear_prompt') }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="/transaction-liquidation/clear/{{ $transaction->id }}" method="post"  enctype="multipart/form-data">
                                        @csrf
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Total Advanced</th>
                                                    <th class="text-center">Amount Used</th>
                                                    <th class="text-center">Amount to be {{ $transaction->liq_balance >= 0 ? 'paid' : 'returned' }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $transaction->currency }}
                                                        {{ number_format($transaction->amount_issued, 2, '.', ',') }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $transaction->currency }}
                                                        {{ number_format($transaction->liq_subtotal, 2, '.', ',') }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $transaction->currency }}
                                                        {{ number_format($transaction->liq_balance >= 0 ? $transaction->liq_balance : $transaction->liq_balance*-1, 2, '.', ',') }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @if ($transaction->liq_balance != 0)
                                            <div class="row mt-5">                                            
                                                <div class="col-md-2">
                                                    <label for="" class="font-weight-bold">Type</label>
                                                    <select name="depo_type" class="form-control @error('depo_type') is-invalid @enderror" required>
                                                        @foreach (config('global.deposit_type') as $item)
                                                            <option value="{{ $item }}">{{ $item }}</option>
                                                        @endforeach
                                                    </select>
                                                    @include('errors.inline', ['message' => $errors->first('depo_type')])
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="" class="font-weight-bold">Bank</label>
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
                                                <div class="col-md-4">
                                                    <label for="" class="font-weight-bold">Reference Code</label>
                                                    <input type="text" name="depo_ref" class="form-control @error('depo_ref') is-invalid @enderror" required>
                                                    @include('errors.inline', ['message' => $errors->first('depo_ref')])
                                                </div>
                                            </div>
                                            <div class="row mt-3 mb-5">
                                                <div class="col-md-4">
                                                    <label for="" class="font-weight-bold">Date Deposited</label>
                                                    <input type="date" name="depo_date" class="form-control @error('depo_date') is-invalid @enderror" required>
                                                    @include('errors.inline', ['message' => $errors->first('depo_date')])
                                                </div>
                                                <div class="col-md-8">
                                                    <label for="" class="font-weight-bold">Slip Attachment <small>( Accepts .jpg, .png and .pdf file types, not more than 5mb. )</small></label>
                                                    <input type="file" name="depo_slip" class="form-control @error('depo_slip') is-invalid @enderror" required>
                                                    @include('errors.inline', ['message' => $errors->first('depo_slip')])
                                                </div>
                                            </div>
                                        @endif
                                        <div class="text-center mt-2">
                                            <input type="submit" class="btn btn-success" value="Clear Now">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($perms['can_edit_cleared'])
                    <div class="modal fade" id="modal-clear-edit" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title">{{ __('messages.clear_prompt') }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="/transaction-liquidation/clear/{{ $transaction->id }}" method="post"  enctype="multipart/form-data">
                                        @csrf
                                        @method('put')
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Total Advanced</th>
                                                    <th class="text-center">Amount Used</th>
                                                    <th class="text-center">Amount to be {{ $transaction->liq_balance >= 0 ? 'paid' : 'returned' }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $transaction->currency }}
                                                        {{ number_format($transaction->amount_issued, 2, '.', ',') }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $transaction->currency }}
                                                        {{ number_format($transaction->liq_subtotal, 2, '.', ',') }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $transaction->currency }}
                                                        {{ number_format($transaction->liq_balance >= 0 ? $transaction->liq_balance : $transaction->liq_balance*-1, 2, '.', ',') }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @if (!$transaction->is_bills && !$transaction->is_hr)
                                            @if ($transaction->liq_balance != 0)
                                            <div class="row mt-5">                                            
                                                <div class="col-md-2">
                                                    <label for="" class="font-weight-bold">Type</label>
                                                    <select name="depo_type" class="form-control @error('depo_type') is-invalid @enderror" required>
                                                        @foreach (config('global.deposit_type') as $item)
                                                            <option value="{{ $item }}" {{ $transaction->depo_type == $item ? 'selected' : '' }}>{{ $item }}</option>
                                                        @endforeach
                                                    </select>
                                                    @include('errors.inline', ['message' => $errors->first('depo_type')])
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="" class="font-weight-bold">Bank</label>
                                                    <select name="depo_bank_branch_id" class="form-control @error('depo_bank_branch_id') is-invalid @enderror" required>
                                                        @foreach ($banks as $item)
                                                            <optgroup label="{{ $item->name }}">
                                                                @foreach ($item->bankbranches as $branch)
                                                                    <option value="{{ $branch->id }}" {{ $transaction->depo_bank_branch_id == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                                                @endforeach
                                                            </optgroup>
                                                        @endforeach
                                                    </select>
                                                    @include('errors.inline', ['message' => $errors->first('depo_bank_branch_id')])
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="" class="font-weight-bold">Reference Code</label>
                                                    <input type="text" name="depo_ref" class="form-control @error('depo_ref') is-invalid @enderror" value="{{ $transaction->depo_ref }}" required>
                                                    @include('errors.inline', ['message' => $errors->first('depo_ref')])
                                                </div>
                                            </div>
                                            <div class="row mt-3 mb-5">
                                                <div class="col-md-4">
                                                    <label for="" class="font-weight-bold">Date Deposited</label>
                                                    <input type="date" name="depo_date" class="form-control @error('depo_date') is-invalid @enderror" value="{{ $transaction->depo_date }}" required>
                                                    @include('errors.inline', ['message' => $errors->first('depo_date')])
                                                </div>
                                                @if (!$transaction->is_deposit)
                                                    <div class="col-md-8">
                                                        <label for="" class="font-weight-bold">Replace Slip Attachment <small>( Accepts .jpg, .png and .pdf file types, not more than 5mb. )</small></label>
                                                        <input type="file" name="depo_slip" class="form-control @error('depo_slip') is-invalid @enderror">
                                                        @include('errors.inline', ['message' => $errors->first('depo_slip')])
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                        <div class="text-center mt-2">
                                            <input type="submit" class="btn btn-success" value="Clear Now">
                                        </div>
                                        @endif
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
                    <div class="pb-2 border-bottom">
                        <h1 class="d-inline-block mr-3">
                            <input type="text" value="{{ strtoupper($transaction->trans_type) }}-{{ $transaction->trans_year }}-{{ sprintf('%05d',$transaction->trans_seq) }}" class="input--label" readonly>
                            <a href="#_" class="btn btn-default vlign--top jsCopy" data-toggle="tooltip" data-placement="top" title="Copy to clipboard"><i class="align-middle font-weight-bolder material-icons text-md">content_copy</i></a>
                        </h1>
                        <h6 class="d-inline-block">
                            <img src="/storage/public/images/companies/{{ $transaction->project->company->logo }}" alt="" class="thumb--xxs mr-1">
                            {{ $transaction->project->company->name }}
                        </h6>
                    </div>
                    <div class="row mb-3">
                        <table class="table col-12">
                            <tr>
                                <td class="font-weight-bold w-25">Status</td>
                                <td>{{ $transaction->status->name }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold w-25">Transaction Category</td>
                                <td>
                                    @if ($transaction->is_deposit)
                                        {{ config('global.trans_category_label')[1] }}
                                    @elseif ($transaction->is_bills)    
                                        {{ config('global.trans_category_label')[2] }}
                                    @elseif ($transaction->is_hr)    
                                        {{ config('global.trans_category_label')[3] }}
                                    @else
                                        {{ config('global.trans_category_label')[4] }}    
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold w-25">Requested by</td>
                                <td>{{ $transaction->requested->name }}</td>
                            </tr>
                            @if ($transaction->liquidation_approver_id && !$transaction->is_deposit && !$transaction->is_bills && !$transaction->is_hr)
                                <tr>
                                    <td class="font-weight-bold w-25">Authorized Approver</td>
                                    <td>{{ $transaction->liquidationapprover->name }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="font-weight-bold w-25">Project</td>
                                <td>{{ $transaction->project->project }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold w-25">Particulars</td>
                                <td>{{ $trans_page_url == 'prpo' ? $transaction->particulars->name : $transaction->particulars_custom }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold w-25">Due Date</td>
                                <td>{{ $transaction->due_at }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold w-25">COA Tagging</td>
                                <td>{{ $transaction->coatagging->name }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold w-25">Vendor / Payee</td>
                                <td>{{ $transaction->payee }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold w-25">Tax Type</td>
                                <td>{{ $transaction->form_vat_name ? $transaction->form_vat_name : $transaction->vattype->name }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold w-25">Issue Type</td>
                                <td>{{ $transaction->control_type }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold w-25">Issue No.</td>
                                <td>{{ $transaction->control_no }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold w-25">Released Date</td>
                                <td>{{ $transaction->released_at }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold w-25">Released Amount</td>
                                <td>{{ $transaction->currency }} {{ number_format($transaction->amount_issued, 2, '.', ',') }}</td>
                            </tr>
                            @if (!$transaction->is_deposit && !$transaction->is_bills && !$transaction->is_hr)
                                <tr>
                                    <td><span class="font-weight-bold w-25">Attachments</span></td>
                                    @include('pages.admin.transactionliquidation.show-attachment')
                                </tr>
                            @endif
                            <tr>
                                <td colspan="2">
                                    <span class="font-weight-bold w-25">Purpose</span>
                                    <p>{{ $transaction->purpose }}</p>
                                </td>
                            </tr>

                        </table> 
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
                        <table class="table table-bordered {{ $transaction->is_deposit || $transaction->is_bills || $transaction->is_hr ? 'd-none' : '' }}">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Location/Route</th>
                                    <th class="text-center">Receipt</th>
                                    <th class="text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaction->liquidation as $item)
                                    <tr>
                                        <td>{{ $item->date }}</td>
                                        <td>{{ $item->expensetype->name }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>{{ $item->location }}</td>
                                        <td class="text-center">{{ $item->receipt ? 'Y' : 'N' }}</td>
                                        <td class="text-right">{{ number_format($item->amount, 2, '.', ',') }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="6" class="py-4"></td>
                                </tr>
                                @foreach ($transaction_summary as $item)
                                    <tr>
                                        <td></td>
                                        <td class="bg-white" colspan="5">
                                            {{ $item->name }}
                                            <span class="float-right">{{ number_format($item->amount, 2, '.', ',') }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="6" class="py-4"></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="font-weight-bold small text-right">Before VAT</td>
                                    <td colspan="2" class="bg-white text-right">
                                        <span class="float-left">{{ $transaction->currency }}</span>
                                        {{ number_format($transaction->liq_before_vat, 2, '.', ',') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="font-weight-bold small text-right">VAT (12%)</td>
                                    <td colspan="2" class="bg-white text-right font-italic">
                                        <span class="float-left">{{ $transaction->currency }}</span>
                                        {{ number_format($transaction->liq_vat, 2, '.', ',') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="font-weight-bold small text-right">Subtotal</td>
                                    <td colspan="2" class="bg-white text-right font-weight-bold">
                                        <span class="float-left">{{ $transaction->currency }}</span>
                                        {{ number_format($transaction->liq_subtotal, 2, '.', ',') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="font-weight-bold small text-right">Less: Deposit/Payment</td>
                                    <td colspan="2" class="bg-white text-right text-danger">
                                        <span class="float-left">{{ $transaction->currency }}</span>
                                        {{ number_format($transaction->amount_issued, 2, '.', ',') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="small font-weight-bold text-right">Balance</td>
                                    <td colspan="2" class="bg-white text-right font-weight-bold">
                                        <span class="float-left">{{ $transaction->currency }}</span>
                                        {{ number_format($transaction->liq_balance, 2, '.', ',') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="small text-right">
                                        <span>(+) For Reimbursement / (-) Return Money</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        @if (in_array($transaction->status_id, config('global.liquidation_cleared')))
                            <div class="pb-2 mt-3 mb-2">
                                <h3 class="d-inline-block mr-3">Clearing Information</h3>
                            </div>
                            <table class="table">
                                @if ($transaction->liq_balance != 0)
                                    <tr>
                                        <td>Amount {{ $transaction->liq_balance >= 0 ? 'Reimbursed' : 'Returned' }}</td>
                                        <td class="font-weight-bold">
                                            {{ $transaction->currency }}
                                            {{ number_format($transaction->liq_balance >= 0 ? $transaction->liq_balance : $transaction->liq_balance*-1, 2, '.', ',') }}
                                        </td>
                                    </tr>
                                    @if (!$transaction->is_bills && !$transaction->is_hr)
                                        <tr>
                                            <td>Type</td>
                                            <td class="font-weight-bold">{{ $transaction->depo_type }}</td>
                                        </tr>
                                        <tr>
                                            <td>Bank</td>
                                            <td class="font-weight-bold">{{ $transaction->bankbranch->bank->name }} ({{ $transaction->bankbranch->name }})</td>
                                        </tr>
                                        <tr>
                                            <td>Rereference Code</td>
                                            <td class="font-weight-bold">{{ $transaction->depo_ref }}</td>
                                        </tr>
                                        <tr>
                                            <td>Date Deposited</td>
                                            <td class="font-weight-bold">{{ $transaction->depo_date }}</td>
                                        </tr>    
                                    @endif
                                    @if (($transaction->is_deposit) && $transaction->liquidation_approver_id)
                                        <tr>
                                            <td>Deposited By</td>
                                            <td>{{ $transaction->liquidationapprover->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Attachments</td>
                                            @include('pages.admin.transactionliquidation.show-attachment')
                                        </tr>
                                    @elseif($transaction->is_bills || $transaction->is_hr)
                                        <tr>
                                            <td>Attachments</td>
                                            @include('pages.admin.transactionliquidation.show-attachment')
                                        </tr>
                                    @else
                                        <tr>
                                            <td>Slip Attachment</td>
                                            <td class="font-weight-bold"><a href="/storage/public/attachments/deposit_slip/{{ $transaction->depo_slip }}" target="_blank"><i class="material-icons mr-2 align-bottom">attachment</i></a></td>
                                        </tr>
                                    @endif
                                @else
                                    <tr>
                                        <td colspan="2">No deposit information.</td>
                                    </tr>
                                @endif
                                
                            </table>
                        @endif
                    </div>
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
                                                        <h5 class="modal-title">
                                                            {{ ucfirst($item->description) }} {{ Carbon::parse($item->created_at)->diffInDays(Carbon::now()) >= 1 ? $item->created_at->format('Y-m-d') : $item->created_at->diffForHumans() }}
                                                        </h5>
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
                                    <td>{{ $item->log_name }}</td>
                                    <td>{{ $item->causer->name }}</td>
                                    <td class="text-right">{{ Carbon::parse($item->created_at)->diffInDays(Carbon::now()) >= 1 ? $item->created_at->format('Y-m-d') : $item->created_at->diffForHumans() }}</td>
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