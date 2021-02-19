@extends('layouts.app')

@section('title', 'View '.strtoupper($transaction->trans_type))

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="form-row"> 
                <div class="col-sm-6 mb-2">
                    <a href="/transaction/{{ $trans_page_url }}/{{ $transaction->project->company_id }}{{ isset($_GET['page']) ? '?page='.$_GET['page'] : '' }}" class="btn mb-2 btn-default"><i class="align-middle font-weight-bolder material-icons text-md">arrow_back_ios</i> Back</a>
                    <a data-toggle="modal" data-target="#modal-make" href="#_" class="btn mb-2 btn-default"><i class="align-middle font-weight-bolder material-icons text-md">add</i> Add New</a>                
                    <a href="/transaction-form/reset/{{ $transaction->id }}" class="btn mb-2 btn-default {{ $perms['can_reset'] ? '' : 'd-none' }}" onclick="return confirm('Are you sure?')"><i class="align-middle font-weight-bolder material-icons text-md">autorenew</i> Renew Edit Limit</a>
                </div>
                <div class="col-sm-6 text-sm-right mb-2">
                    <a href="/transaction-liquidation/create?company={{ $transaction->project->company_id }}&key={{ strtoupper($transaction->trans_type)."-".$transaction->trans_year."-".sprintf('%05d',$transaction->trans_seq) }}" class="btn mb-2 btn-success {{ $perms['can_create'] ? '' : 'd-none' }}">
                        <i class="align-middle font-weight-bolder material-icons text-md">add</i>
                        {{ $transaction->is_bank ? 'Deposit' : 'Liquidate' }}
                    </a>

                    <!-- if issued and bank and diff company-->
                    <a href="/transaction-form/edit-issued-clear/{{ $transaction->id }}" class="btn mb-2 btn-success {{ !$perms['can_create'] && $transaction->is_bank && $transaction->form_company_id ? '' : 'd-none' }}" onclick="return confirm('Are you sure?')">
                        <i class="align-middle font-weight-bolder material-icons text-md">check</i>
                        Clear Now
                    </a>

                    <a href="/transaction-form/edit{{ $transaction->is_reimbursement ? '-reimbursement' : '' }}/{{ $transaction->id }}" class="btn mb-2 btn-primary {{ $perms['can_edit'] ? '' : 'd-none' }}"><i class="align-middle font-weight-bolder material-icons text-md">edit</i> Edit</a>
                    {{-- <a href="#_" class="btn mb-2 btn-success {{ $perms['can_approval'] ? '' : 'd-none' }}" data-toggle="modal" data-target="#modal-approval"><i class="align-middle font-weight-bolder material-icons text-md">grading</i> For Approval</a> --}}
                    <a href="/transaction-form/approval/{{ $transaction->id }}" class="btn mb-2 btn-success {{ $perms['can_approval'] ? '' : 'd-none' }}" onclick="return confirm('Are you sure?')"><i class="align-middle font-weight-bolder material-icons text-md">grading</i> For Approval</a>
                    <a href="#_" class="btn mb-2 btn-danger {{ $perms['can_cancel'] ? '' : 'd-none' }}" data-toggle="modal" data-target="#modal-cancel"><i class="align-middle font-weight-bolder material-icons text-md">delete</i> Cancel</a>
                    
                    <a href="#_" class="btn mb-2 btn-danger {{ $perms['can_print'] ? '' : 'd-none' }}" onclick="window.open('/transaction-form/print/{{ $transaction->id }}','name','width=800,height=800')"><i class="align-middle font-weight-bolder material-icons text-md">print</i> Print</a>
                    <a href="#" class="btn mb-2 btn-success {{ $perms['can_issue'] ? '' : 'd-none' }} px-4" data-toggle="modal" data-target="#modal-issue"><i class="align-middle font-weight-bolder material-icons text-md">check</i> {{ $transaction->is_deposit ? 'Deposit Notes' : 'Issue' }}</a>
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
                                    <form action="/transaction-form/issue/{{ $transaction->id }}" method="post" enctype="multipart/form-data" class="row">
                                        @csrf
                                        @method('put')
                                        <div class="col-md-5 mb-2">
                                            <label for="" class="font-weight-bold">Type</label>
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
                                        <div class="col-md-7 mb-2">
                                            <label for="" class="font-weight-bold">No.</label>
                                            @if ($trans_page_url == 'prpo')
                                                <input type="text" name="control_no" class="form-control @error('control_no') is-invalid @enderror" required>
                                                @include('errors.inline', ['message' => $errors->first('control_no')])
                                            @else
                                                <h5>{{ strtoupper($transaction->trans_type) }}-{{ $transaction->trans_year }}-{{ sprintf('%05d',$transaction->trans_seq) }}</h5>
                                                <input type="hidden" name="control_no" value="{{ strtoupper($transaction->trans_type) }}-{{ $transaction->trans_year }}-{{ sprintf('%05d',$transaction->trans_seq) }}">
                                            @endif                                                
                                        </div>
                                        <div class="col-md-7 mb-2 {{ $transaction->is_bank ? '' : 'd-none' }}">
                                            <label for="" class="font-weight-bold">Transfer to</label>
                                            <select name="form_company_id" id="form_company_id" data-val="{{ $transaction->project->company->id }}" class="form-control @error('form_company_id') is-invalid @enderror" required>
                                                @foreach ($companies as $item)
                                                    <option value="{{ $item->id }}" {{ $transaction->project->company->id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                            @include('errors.inline', ['message' => $errors->first('form_company_id')])
                                        </div>
                                        <div class="col-md-5 mb-2">
                                            <label for="" class="font-weight-bold">{{ $transaction->is_deposit ? 'Date Deposited' : 'Release Date' }}</label>
                                            <input type="date" class="form-control @error('released_at') is-invalid @enderror" name="released_at" required>
                                            @include('errors.inline', ['message' => $errors->first('released_at')])
                                        </div>

                                        <div class="col-12 mt-4 {{ $transaction->is_bank ? '' : 'd-none' }}"></div>
                                        <div class="col-md-5 mb-2 {{ $transaction->is_bank ? '' : 'd-none' }}">
                                            <label for="" class="font-weight-bold text-center d-block">Amount</label>
                                            <div class="row">
                                                <div class="col-4 pt-2 font-weight-bold">{{ $transaction->currency }}</div>
                                                <input type="number" id="amount" class="form-control col-8 row @error('amount') is-invalid @enderror" name="amount" step="0.01" value="{{ $transaction->amount }}" readonly required>
                                            </div>
                                            @include('errors.inline', ['message' => $errors->first('amount')])
                                        </div>
                                        <div class="col-md-4 mb-2 {{ $transaction->is_bank ? '' : 'd-none' }}">
                                            <label for="" class="font-weight-bold d-block text-center">Currency</label>
                                            <div class="row">
                                                <div class="col-3 font-weight-bold">x</div>
                                                <div class="col-9">
                                                    <select name="currency_2" id="currencyChange" class="form-control @error('currency_2') is-invalid @enderror">
                                                        @foreach (config('global.currency') as $key => $item)
                                                            <option value="{{ config('global.currency_label')[$key] }}" {{ $transaction->currency == config('global.currency_label')[$key] ? 'selected' : '' }}>{{ $item }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>                                                
                                            @include('errors.inline', ['message' => $errors->first('currency_2')])
                                        </div>
                                        <div class="col-md-3 mb-2 {{ $transaction->is_bank ? '' : 'd-none' }}">
                                            <label for="" class="font-weight-bold">FX Rate</label>
                                            <input type="number" id="currencyFx" class="form-control @error('currency_2_rate') is-invalid @enderror" step="0.000001" name="currency_2_rate" value="1" readonly required>
                                            @include('errors.inline', ['message' => $errors->first('currency_2_rate')])
                                        </div>

                                        @if (!$transaction->is_bank)
                                            @if ($transaction->is_deposit || $transaction->is_reimbursement)
                                                <div class="col-md-1 mb-2">
                                                    <label class="invisible">.</label>
                                                    {{ $transaction->currency }}
                                                </div>
                                            @endif
                                            <div class="{{ $transaction->is_deposit || $transaction->is_reimbursement ? 'col-md-6' : 'col-md-7' }} mb-2">
                                                <label for="" class="font-weight-bold">Amount</label>
                                                <input type="number" class="form-control @error('amount_issued') is-invalid @enderror" name="amount_issued" step="0.01" value="{{ $transaction->amount }}" required>
                                                @include('errors.inline', ['message' => $errors->first('amount_issued')])
                                            </div>
                                        @else
                                            <div class="col-12 my-2 row">
                                                <div class="col-6 font-weight-bold">Total Amount Transferred:</div>
                                                <input type="text" id="currencyAmount" name="amount_issued" value="{{ $transaction->amount }}" class="border-0 col-6 pr-0 text-right outline-0" readonly>
                                            </div>
                                            <div class="col-12 mb-4"></div>
                                        @endif
                                        <div class="col-md-12 mb-2 {{ $transaction->is_bank ? '' : 'd-none' }}">
                                            <label for="" class="font-weight-bold">Service Charge</label>
                                            <input type="number" class="form-control @error('form_service_charge') is-invalid @enderror" step="0.01" name="form_service_charge" value="0" required>
                                            @include('errors.inline', ['message' => $errors->first('form_service_charge')])
                                        </div>
                                        <div class="col-md-12 mb-2 {{ $transaction->is_deposit || $transaction->is_reimbursement ? '' : 'd-none' }}">
                                            <label for="" class="font-weight-bold">{{ $transaction->is_deposit ? 'Payor' : 'Payee' }}</label>
                                            <input type="text" name="payor" class="form-control @error('payor') is-invalid @enderror" value="{{ $transaction->payor }}" {{ $transaction->is_deposit || $transaction->is_reimbursement ? 'required' : '' }}>
                                            @include('errors.inline', ['message' => $errors->first('payor')])
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label for="" class="font-weight-bold">{{ $transaction->is_deposit ? 'Issued By' : 'Released By' }}</label>
                                            <select name="released_by_id" class="form-control @error('released_by_id') is-invalid @enderror" required>
                                                @foreach ($released_by as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                            @include('errors.inline', ['message' => $errors->first('released_by_id')])
                                        </div>
                                        <div id="depo_slip" class="col-md-12 mb-2 {{ $transaction->is_reimbursement ? '' : 'd-none' }}">
                                            <label for="" class="font-weight-bold">Attachment <small>( Accepts .jpg, .png and .pdf file types, not more than 5mb. )</small></label>
                                            <input type="file" name="depo_slip" class="form-control @error('depo_slip') is-invalid @enderror" {{ $transaction->is_reimbursement ? 'required' : '' }}>
                                            @include('errors.inline', ['message' => $errors->first('depo_slip')])
                                        </div>
                                        <div class="col-12 text-center mt-2">
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
                <div class="col-lg-8">
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
                        <div class="row mb-0 mb-lg-4">
                            <div class="mb-4 mb-lg-0 col-lg-6">
                                <label for="">Project</label>
                                <h5>{{ $transaction->project->project }}</h5>
                            </div>
                            <div class="mb-4 mb-lg-0 col-lg-6">
                                <label for="">Transaction Category</label>
                                @if ($perms['can_edit_issued'])
                                    <form action="/transaction-form/edit-issued/{{ $transaction->id }}" method="post">
                                        @csrf
                                        @method('put')
                                        <select name="trans_category" class="form-control w-50 mb-2" onchange="this.form.submit()">
                                            <option value="{{ config('global.trans_category')[0] }}" {{ $transaction->is_deposit == 0 && $transaction->is_bills == 0 && $transaction->is_hr == 0 ? 'selected' : '' }}>{{ config('global.trans_category_label')[0] }}</option>
                                            <option value="{{ config('global.trans_category')[1] }}" {{ $transaction->is_deposit == 1 ? 'selected' : '' }}>{{ config('global.trans_category_label')[1] }}</option>
                                            <option value="{{ config('global.trans_category')[2] }}" {{ $transaction->is_bills == 1 ? 'selected' : '' }}>{{ config('global.trans_category_label')[2] }}</option>
                                            <option value="{{ config('global.trans_category')[3] }}" {{ $transaction->is_hr == 1 ? 'selected' : '' }}>{{ config('global.trans_category_label')[3] }}</option>
                                            <option value="{{ config('global.trans_category')[5] }}" {{ $transaction->is_bank == 1 ? 'selected' : '' }}>{{ config('global.trans_category_label')[5] }}</option>
                                        </select>
                                    </form>
                                @else
                                    <h5>
                                        @if ($transaction->is_deposit)
                                            {{ config('global.trans_category_label')[1] }}
                                        @elseif ($transaction->is_bills)    
                                            {{ config('global.trans_category_label')[2] }}
                                        @elseif ($transaction->is_hr)    
                                            {{ config('global.trans_category_label')[3] }}
                                        @elseif ($transaction->is_reimbursement)    
                                            {{ config('global.trans_category_label')[4] }}
                                        @elseif ($transaction->is_bank)    
                                            {{ config('global.trans_category_label')[5] }}
                                        @else
                                            {{ config('global.trans_category_label')[0] }}    
                                        @endif
                                    </h5>
                                @endif
                            </div>
                            <div class="mb-4 mb-lg-0 col-lg-6">
                                <label for="">Particulars</label>
                                <h5>{{ $trans_page_url == 'prpo' ? $transaction->particulars->name : $transaction->particulars_custom }}</h5>
                            </div>
                            <!-- if fund transfer and issued status -->
                            @if ($transaction->is_bank && in_array($transaction->status_id, config('global.form_issued')))
                                <div class="mb-4 mb-lg-0 col-lg-6">
                                    <label for="">Transfer to</label>
                                    <h5>
                                        {{ $transaction->formcompany->name }}
                                        <a href="#" class="ml-1" data-toggle="modal" data-target="#modal-transfer-to">
                                            <i class="align-middle material-icons small">edit</i>
                                        </a>
                                    </h5>
                                    <div class="modal fade" id="modal-transfer-to" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title">Edit Transfer To</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="/transaction-form/edit-issued-company/{{ $transaction->id }}" method="post" class="modal-body" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('put')
                                                    <div class="form-group">
                                                        <label for="" class="font-weight-bold">Company</label>
                                                        <select name="form_company_id" id="form_company_id" data-val="{{ $transaction->project->company->id }}" class="form-control @error('form_company_id') is-invalid @enderror" required>
                                                            @foreach ($companies as $item)
                                                            <option value="{{ $item->id }}" {{ $transaction->form_company_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @include('errors.inline', ['message' => $errors->first('form_company_id')])
                                                    </div>
                                                    <div id="depo_slip" class="form-group {{ $transaction->project->company->id != $transaction->form_company_id ? '' : 'd-none' }}">
                                                        <label for="" class="font-weight-bold">Attachment <small>( Accepts .jpg, .png and .pdf file types, not more than 5mb. )</small></label>
                                                        <input type="file" name="depo_slip" class="form-control @error('depo_slip') is-invalid @enderror" {{ $transaction->project->company->id != $transaction->form_company_id ? 'required' : '' }}>
                                                        @include('errors.inline', ['message' => $errors->first('depo_slip')])
                                                    </div>
                                                    <div class="form-group text-center pt-3">
                                                        <input type="submit" class="btn btn-success" value="Save Changes">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>                      
                            @endif
                        </div>
                        <div class="row mb-0 mb-lg-4">
                            <div class="mb-4 mb-lg-0 col-lg-6">
                                <label for="">COA Tagging</label>
                                <h5>{{ $transaction->coatagging->name }}</h5>
                            </div>
                            <div class="mb-4 mb-lg-0 col-lg-6">
                                <label for="">Payor</label>
                                <h5>{{ $transaction->payor ?: 'n/a' }}</h5>
                            </div>
                        </div>
                        <div class="row mb-0 mb-lg-4">
                            <div class="mb-4 mb-lg-0 col-lg-6">
                                <label for="">Due Date</label>
                                <h5>{{ $transaction->due_at }}</h5>
                            </div>
                            <div class="mb-4 mb-lg-0 col-lg-6">
                                <label for="">Vendor / Payee</label>
                                <h5>{{ $transaction->payee }}</h5>
                            </div>
                        </div>
                        <div class="row mb-0 mb-lg-4">    
                            <div class="mb-4 mb-lg-0 col-lg-6">
                                <label for="">Tax Type</label>
                                <h5>{{ $transaction->form_vat_name && !in_array($transaction->status_id, config('global.generated_form')) ? $transaction->form_vat_name : $transaction->vattype->name }}</h5>
                            </div>                        
                            <div class="mb-4 mb-lg-0 col-lg-6">
                                <label for="">Prepared By</label>
                                <h5>{{ $transaction->owner->name }}</h5>
                            </div>
                        </div>
                        <div class="row mb-0 mb-lg-4">  
                            <div class="mb-4 mb-lg-0 col-lg-6">
                                <label for="">Requested By</label>
                                <h5>{{ $transaction->requested->name }}</h5>
                            </div>
                            <div class="mb-4 mb-lg-0 col-lg-6">
                                <label for="">Status</label>
                                <h5>{{ $transaction->status->name }}</h5>
                            </div>
                        </div>
                        <div class="row mb-0 mb-lg-4">  
                            <div class="mb-4 mb-lg-0 col-lg-12 {{ $transaction->form_approver_id ? '' : 'd-none' }}">
                                <label for="">Authorized Approver</label>
                                <h5>{{ $transaction->form_approver_id ? $transaction->formapprover->name : '' }}</h5>
                            </div>
                        </div>
                        <div class="row mb-0 mb-lg-4">
                            <div class="mb-4 mb-lg-0 {{ !$transaction->is_reimbursement ? 'col-lg-11' : 'col-lg-6' }}">
                                <label for="">Purpose</label>
                                <h6>{{ $transaction->purpose }}</h6>
                            </div>
                            @if ($transaction->is_reimbursement)
                                <div class="mb-4 mb-lg-0 col-lg-6">
                                    <label for="">Attachments</label>
                                    <h6>@include('pages.admin.transactionliquidation.show-attachment')</h6>
                                </div>
                            @endif
                        </div>
                        
                        <div class="row mb-3 table-responsive">
                        @if (!$transaction->is_reimbursement)
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Qty</th>
                                        <th>Description</th>
                                        <th>Particulars</th>
                                        <th class="text-nowrap text-right">Unit Price</th>
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
                                        <td colspan="3" class="text-nowrap text-right">Total Payable</td>
                                        <td colspan="2" class="text-right">{{ number_format($transaction->form_amount_payable && !in_array($transaction->status_id, config('global.generated_form')) ? $transaction->form_amount_payable : $transaction->custom_total_payable, 2, '.', ',') }}</td>
                                    </tr>
                                    @endif
                                    <tr class="font-weight-bold border-top-2">
                                        <td colspan="3" class="text-right">Amount</td>
                                        <td colspan="2" class="text-nowrap text-right">{{ $transaction->currency }} {{ number_format($transaction->form_amount_payable && !in_array($transaction->status_id, config('global.generated_form')) ? $transaction->form_amount_payable : $transaction->custom_total_payable, 2, '.', ',') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <table class="table table-bordered">
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
                                            <td class="text-nowrap">{{ $item->date }}</td>
                                            <td class="text-nowrap">{{ $item->expensetype->name }}</td>
                                            <td>{{ $item->description }}</td>
                                            <td>{{ $item->location }}</td>
                                            <td class="text-center">{{ $item->receipt ? 'Y' : 'N' }}</td>
                                            <td class="text-right">{{ number_format($item->amount, 2, '.', ',') }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="4" class="font-weight-bold small text-right">Total</td>
                                        <td colspan="2" class="bg-white text-right font-weight-bold">
                                            <span class="float-left">{{ $transaction->currency }}</span>
                                            {{ number_format($transaction->amount, 2, '.', ',') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        @endif
                        </div>
                        <div class="row mb-3"></div>
                    </div>
                    @if ($transaction->status_id == 3)
                        <div class="pb-2 mt-5 mb-4 border-bottom">
                            <h3 class="d-inline-block mr-3">Cancellation Reason</h3>
                            <code>{{ $transaction->cancellation_number }}</code>
                        </div>
                        <div>{{ $transaction->cancellation_reason }}</div>
                    @elseif ($transaction->status_id == 4)
                        <div class="pb-2 mt-3 mb-4 border-bottom">
                            <h3 class="d-inline-block mr-3">Issuing Details</h3>
                        </div>
                        <div>
                            <div class="row mb-0 mb-lg-4">
                                <div class="mb-4 mb-lg-0 col-lg-6">
                                    <label for="">Type</label>
                                    <h5>{{ $transaction->control_type }}</h5>
                                </div>
                                <div class="mb-4 mb-lg-0 col-lg-6">
                                    <label for="">No.</label>
                                    <h5>{{ $transaction->control_no }}</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-4 col-lg-6">
                                    <label for="">{{ $transaction->is_deposit ? 'Date Deposited' : 'Released Date' }}</label>
                                    <h5>{{ $transaction->released_at }}</h5>
                                </div>
                                <div class="mb-4 col-lg-6">
                                    <label for="">{{ $transaction->is_deposit ? 'Issued By' : 'Released By' }}</label>
                                    <h5>{{ $transaction->releasedby->name }}</h5>
                                </div>                                
                            </div>
                            <div class="row">
                                @if ($transaction->is_bank)
                                    <div class="mb-4 col-lg-6">
                                        <label for="">Amount / FX Rate</label>
                                        <h5>
                                            {{ $transaction->currency }} {{ number_format($transaction->amount, 2, '.', ',') }}
                                            <span class="small px-2 vlign--top">x</span>
                                            {{ number_format($transaction->currency_2_rate, 2, '.', ',') }}
                                            ({{ $transaction->currency_2 }})
                                            <span class="small pl-2 vlign--top">=</span>
                                        </h5>
                                    </div>
                                @endif
                                <div class="mb-4 col-lg-6">
                                    <label for="">{{ $transaction->is_bank ? 'Transferred ' : '' }}Amount</label>
                                    <h5>{{ $transaction->currency_2 ?: $transaction->currency }} {{ number_format($transaction->amount_issued, 2, '.', ',') }}</h5>
                                </div>

                                @if ($transaction->is_bank && $transaction->depo_slip)
                                    <div class="mb-4 col-lg-6">
                                        <label for="">Attachment</label>
                                        <h5>
                                            <a href="/storage/public/attachments/deposit_slip/{{ $transaction->depo_slip }}" target="_blank">
                                                <i class="material-icons mr-2 align-bottom">attachment</i>
                                            </a>
                                        </h5>
                                    </div>
                                @endif

                                @if ($transaction->formcompany)
                                    <div class="mb-4 col-lg-6">
                                        <label for="">Company</label>
                                        <h5>{{ $transaction->formcompany->name }}</h5>
                                    </div>
                                @endif
                                @if ($transaction->form_service_charge && $transaction->form_service_charge > 0)
                                    <div class="mb-4 col-lg-6">
                                        <label for="">Service Charge</label>
                                        <h5>{{ number_format($transaction->form_service_charge, 2, '.', ',') }}</h5>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-lg-4">
                    <div class="pt-4 pt-lg-0 pb-2 text-lg-right"><h1>History</h1></div>
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
                                                        <h5 class="modal-title">{{ ucfirst($item->description) }} {{ Carbon::parse($item->created_at)->diffInDays(Carbon::now()) >= 1 ? $item->created_at->format('Y-m-d') : $item->created_at->diffForHumans() }}</h5>
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

            $('#form_company_id').change(function() {
                if (parseInt($(this).val()) != parseInt($(this).data('val'))) {
                    $('#depo_slip').removeClass('d-none')
                    $('#depo_slip input').prop('required', 'true')
                } else {
                    $('#depo_slip').addClass('d-none')
                    $('#depo_slip input').val('')
                    $('#depo_slip input').removeAttr('required')
                }
            })

            $('#currencyChange').change(function() {
                if ($(this).val() != '{{ $transaction->currency }}') {
                    $('#currencyFx').attr("readonly", false) 
                } else {
                    $('#currencyFx').val(1).attr("readonly", "readonly") 
                }

                $('#currencyFx').trigger('change')
            })

            $('#amount').on('keyup keypress blur change', function() {
                $('#currencyFx').trigger('change')
            })

            $('#currencyFx').on('keyup keypress blur change', function() {
                convertedAmt = parseFloat($(this).val() == "" ? 0 : $(this).val()) * parseFloat($('#amount').val())
                $('#currencyAmount').val(convertedAmt.toFixed(2))
            })
        })
    </script>
@endsection