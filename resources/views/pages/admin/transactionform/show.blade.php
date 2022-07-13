@extends('layouts.app')

@section('title', 'View '.strtoupper($transaction->trans_type))
@section('nav_class', 'navbar-dark')

@section('content')
    <?php $ua = (new \App\Helpers\UAHelper)->get(); ?>
    <?php $non = config('global.ua_none'); ?>
    <?php $own = config('global.ua_own'); ?>

    <?php $config_confidential = 0; ?>
    <section class="content-header bg-dark">
        <div class="container-fluid">
            <div class="row"> 
                <div class="col-lg-6">
                    <h1 class="mb-0">
                        <input type="text"
                            value="{{ strtoupper($transaction->trans_type) }}-{{ $transaction->trans_year }}-{{ sprintf('%05d',$transaction->trans_seq) }}"
                            class="input--label text-white" readonly>
                        <a href="#_" class="text-white vlign--top jsCopy" data-toggle="tooltip" data-placement="top" title="Copy to clipboard"><i class="align-middle font-weight-bolder material-icons text-md">content_copy</i></a>
                    </h1>
                    <span class="text-white-50">{{ $transaction->project->company->name }}</span>
                    <div class="mt-2">
                        <span class="badge badge-pill bg-warning p-2">
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
                        </span>
                        <span class="badge badge-pill bg-purple p-2">{{ $transaction->status->name }}</span>
                    </div>
                </div>
                <div class="col-lg-6 text-right mt-4">
                    <div>
                        <a href="/transaction/{{ $trans_page_url }}/{{ $transaction->project->company_id }}{{ isset($_GET['page']) ? '?page='.$_GET['page'] : '' }}" class="btn btn-sm btn-flat mb-2 btn-light col-12 col-lg-auto"><i class="align-middle font-weight-bolder material-icons text-md">arrow_back_ios</i> Back</a>
                        <a data-toggle="modal" data-target="#modal-make" href="#_" class="btn btn-sm btn-flat mb-2 btn-light col-12 col-lg-auto {{ $perms['can_create'] ? '' : 'd-none' }}"><i class="align-middle font-weight-bolder material-icons text-md">add</i> Add New</a>                
                        <a href="/transaction-form/reset/{{ $transaction->id }}" class="btn btn-sm btn-flat mb-2 btn-light col-12 col-lg-auto d-none {{ $perms['can_reset'] ? '' : 'd-none' }}" onclick="return confirm('Are you sure?')"><i class="align-middle font-weight-bolder material-icons text-md">autorenew</i> Renew Edit Limit</a>
                    </div>
                    <div>        
                        <a href="#_" class="btn mb-2 btn-sm btn-flat btn-warning col-12 col-lg-auto" data-toggle="modal" data-target="#modal-notes">
                            <i class="align-middle font-weight-bolder material-icons text-md">speaker_notes</i> Notes
                            <span class="badge badge-danger {{ $transaction->notes->count() > 0 ? '' : 'd-none' }}">{{$transaction->notes->count()}}</span>
                        </a>
                        
                        <a href="/transaction-liquidation/create?company={{ $transaction->project->company_id }}&key={{ strtoupper($transaction->trans_type)."-".$transaction->trans_year."-".sprintf('%05d',$transaction->trans_seq) }}" class="btn mb-2 btn-sm btn-flat btn-success col-12 col-lg-auto {{ $perms['can_create'] ? '' : 'd-none' }}">
                            <i class="align-middle font-weight-bolder material-icons text-md">add</i>
                            {{ $transaction->is_bank ? 'Deposit' : 'Liquidate' }}
                        </a>
                    
                        <!-- if issued and bank and diff company-->
                        <a href="/transaction-form/edit-issued-clear/{{ $transaction->id }}" class="btn mb-2 btn-sm btn-flat btn-success col-12 col-lg-auto {{ !$perms['can_create'] && $transaction->is_bank && $transaction->form_company_id && !in_array($transaction->status_id, config('global.cancelled')) ? '' : 'd-none' }}" onclick="return confirm('Are you sure?')">
                            <i class="align-middle font-weight-bolder material-icons text-md">check</i>
                            Clear Now
                        </a>
                    
                        <a href="/transaction-form/edit{{ $transaction->is_reimbursement ? '-reimbursement' : '' }}/{{ $transaction->id }}" class="btn mb-2 btn-sm btn-flat btn-primary col-12 col-lg-auto {{ $perms['can_edit'] ? '' : 'd-none' }}"><i class="align-middle font-weight-bolder material-icons text-md">edit</i> Edit</a>
                        {{-- <a href="#_" class="btn mb-2 btn-sm btn-flat btn-success col-12 col-lg-auto {{ $perms['can_approval'] ? '' : 'd-none' }}" data-toggle="modal" data-target="#modal-approval"><i class="align-middle font-weight-bolder material-icons text-md">grading</i> For Approval</a> --}}
                        <a href="/transaction-form/approval/{{ $transaction->id }}" class="btn mb-2 btn-sm btn-flat btn-success col-12 col-lg-auto {{ $perms['can_approval'] ? '' : 'd-none' }}" onclick="return confirm('Are you sure?')"><i class="align-middle font-weight-bolder material-icons text-md">grading</i> For Approval</a>
                        <a href="#_" class="btn mb-2 btn-sm btn-flat btn-danger col-12 col-lg-auto {{ $perms['can_cancel'] ? '' : 'd-none' }}" data-toggle="modal" data-target="#modal-cancel"><i class="align-middle font-weight-bolder material-icons text-md">delete</i> Cancel</a>
                        
                        <a href="#_" class="btn mb-2 btn-sm btn-flat btn-danger col-12 col-lg-auto {{ $perms['can_print'] ? '' : 'd-none' }}" onclick="window.open('/transaction-form/print/{{ $transaction->id }}','name','width=800,height=800')"><i class="align-middle font-weight-bolder material-icons text-md">print</i> Print</a>
                        <a href="#" class="btn mb-2 btn-sm btn-flat btn-success col-12 col-lg-auto {{ $perms['can_issue'] ? '' : 'd-none' }} px-4" data-toggle="modal" data-target="#modal-issue"><i class="align-middle font-weight-bolder material-icons text-md">check</i> {{ $transaction->is_deposit ? 'Deposit Notes' : 'Issue' }}</a>
                    </div>
                </div>

                <div class="text-dark">
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
    
                                            <div class="col-12 mt-4 {{ $transaction->is_bank ? '' : 'd-none' }} {{ $config_confidential ? 'd-none' : '' }}"></div>
                                            <div class="col-md-5 mb-2 {{ $transaction->is_bank ? '' : 'd-none' }} {{ $config_confidential ? 'd-none' : '' }}">
                                                <label for="" class="font-weight-bold text-center d-block">Amount</label>
                                                <div class="row">
                                                    <div class="col-4 pt-2 font-weight-bold">{{ $transaction->currency }}</div>
                                                    <input type="number" id="amount" class="form-control col-8 row @error('amount') is-invalid @enderror" name="amount" step="0.01" value="{{ $transaction->amount }}" readonly required>
                                                </div>
                                                @include('errors.inline', ['message' => $errors->first('amount')])
                                            </div>
                                            <div class="col-md-4 mb-2 {{ $transaction->is_bank ? '' : 'd-none' }} {{ $config_confidential ? 'd-none' : '' }}">
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
                                            <div class="col-md-3 mb-2 {{ $transaction->is_bank ? '' : 'd-none' }} {{ $config_confidential ? 'd-none' : '' }}">
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
                                                <div class="{{ $transaction->is_deposit || $transaction->is_reimbursement ? 'col-md-6' : 'col-md-7' }} mb-2 {{ $config_confidential ? 'd-none' : '' }}">
                                                    <label for="" class="font-weight-bold">Amount</label>
                                                    <input type="number" class="form-control @error('amount_issued') is-invalid @enderror" name="amount_issued" step="0.01" value="{{ $transaction->amount }}" required>
                                                    @include('errors.inline', ['message' => $errors->first('amount_issued')])
                                                </div>
                                            @else
                                                <div class="col-12 my-2 row {{ $config_confidential ? 'd-none' : '' }}">
                                                    <div class="col-6 font-weight-bold">Total Amount Transferred:</div>
                                                    <input type="text" id="currencyAmount" name="amount_issued" value="{{ $transaction->amount }}" class="border-0 col-6 pr-0 text-right outline-0" readonly>
                                                </div>
                                                <div class="col-12 mb-4"></div>
                                            @endif
                                            <div class="col-md-12 mb-2 {{ $transaction->is_bank ? '' : 'd-none' }} {{ $config_confidential ? 'd-none' : '' }}">
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
                                            <div id="issue_slip" class="col-md-12 mb-2 {{ $transaction->is_reimbursement ? 'd-none' : '' }}">
                                                <label for="" class="font-weight-bold">Attachment <small>( Accepts .jpg, .png and .pdf file types, not more than 5mb. )</small></label>
                                                <input type="file" name="issue_slip" class="form-control @error('issue_slip') is-invalid @enderror" {{ $transaction->is_reimbursement ? '' : 'required' }}>
                                                @include('errors.inline', ['message' => $errors->first('issue_slip')])
                                            </div>
                                            <div id="depo_slip" class="col-md-12 mb-2 {{ $transaction->is_reimbursement ? '' : 'd-none' }}">
                                                <label for="" class="font-weight-bold">Attachment <small>( Accepts .jpg, .png and .pdf file types, not more than 5mb. )</small></label>
                                                <input type="file" name="depo_slip" class="form-control @error('depo_slip') is-invalid @enderror" {{ $transaction->is_reimbursement ? 'required' : '' }}>
                                                @include('errors.inline', ['message' => $errors->first('depo_slip')])
                                            </div>
                                            <div class="col-12 mb-2">
                                                <div class="alert alert-default-warning rounded text-center" role="alert">
                                                   {{ config('global.issue_attachment_note') }} 
                                                </div>
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

                    @include('pages.admin.transaction.notes')
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid pt-3">
            <div class="row">
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-body">                                
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <td class="font-weight-bold text-gray border-0">Requested By</td>
                                        <td class="font-weight-bold border-0">
                                            <img src="/storage/public/images/users/{{ $transaction->requested->avatar }}" class="img-circle img-size-32 mr-2">
                                            {{ $transaction->requested->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-gray">Prepared By</td>
                                        <td class="font-weight-bold">
                                            <img src="/storage/public/images/users/{{ $transaction->owner->avatar }}" class="img-circle img-size-32 mr-2">
                                            {{ $transaction->owner->name }}
                                        </td>
                                    </tr>
                                    @if ($transaction->form_approver_id)
                                        <tr>
                                            <td class="font-weight-bold text-gray">Auth. Approver</td>
                                            <td class="font-weight-bold">
                                                <img src="/storage/public/images/users/{{ $transaction->formapprover->avatar }}" class="img-circle img-size-32 mr-2">
                                                {{ $transaction->form_approver_id ? $transaction->formapprover->name : '' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($perms['can_edit_issued'])
                                        <tr>
                                            <td class="font-weight-bold text-gray">Transaction Category</td>
                                            <td>
                                                <form action="/transaction-form/edit-issued/{{ $transaction->id }}" method="post">
                                                    @csrf
                                                    @method('put')
                                                    <select name="trans_category" class="form-control border-0 font-weight-bold" onchange="this.form.submit()">
                                                        <option value="{{ config('global.trans_category')[0] }}" {{ $transaction->is_deposit == 0 && $transaction->is_bills == 0 && $transaction->is_hr == 0 ? 'selected' : '' }}>{{ config('global.trans_category_label')[0] }}</option>
                                                        <option value="{{ config('global.trans_category')[1] }}" {{ $transaction->is_deposit == 1 ? 'selected' : '' }}>{{ config('global.trans_category_label')[1] }}</option>
                                                        <option value="{{ config('global.trans_category')[2] }}" {{ $transaction->is_bills == 1 ? 'selected' : '' }}>{{ config('global.trans_category_label')[2] }}</option>
                                                        <option value="{{ config('global.trans_category')[3] }}" {{ $transaction->is_hr == 1 ? 'selected' : '' }}>{{ config('global.trans_category_label')[3] }}</option>
                                                        <option value="{{ config('global.trans_category')[5] }}" {{ $transaction->is_bank == 1 ? 'selected' : '' }}>{{ config('global.trans_category_label')[5] }}</option>
                                                    </select>
                                                </form>
                                            </td>
                                        </tr>
                                    @endif
                                    <!-- if fund transfer and issued status -->
                                    @if ($transaction->is_bank && in_array($transaction->status_id, config('global.form_issued')))
                                        <tr>
                                            <td class="font-weight-bold text-gray">Transfer to</td>
                                            <td class="font-weight-bold">
                                                {{ $transaction->formcompany->name }}
                                                <a href="#" class="ml-1" data-toggle="modal" data-target="#modal-transfer-to">
                                                    <i class="align-middle material-icons small">edit</i>
                                                </a>

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
                                            </td>
                                        </tr>                
                                    @endif
                                    <tr>
                                        <td class="font-weight-bold text-gray">Project</td>
                                        <td class="font-weight-bold">
                                            <img src="/storage/public/images/companies/{{ $transaction->project->company->logo }}" class="img-circle img-size-32 mr-2">
                                            {{ $transaction->project->project }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-gray">Due By</td>
                                        <td class="font-weight-bold">{{ $transaction->due_at }}</td>
                                    </tr>
                                    @if (1==0)
                                        <tr>
                                            <td class="font-weight-bold text-gray">Particulars</td>
                                            <td class="font-weight-bold">{{ $trans_page_url == 'prpo' ? $transaction->particulars->name : $transaction->particulars_custom }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td class="font-weight-bold text-gray">Category / Class</td>
                                        <td class="font-weight-bold">{{ $transaction->coatagging->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-gray">Vendor / Payee</td>
                                        <td class="font-weight-bold">{{ $transaction->payee }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-gray">Payor</td>
                                        <td class="font-weight-bold">{{ $transaction->payee ?: 'n/a' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-gray">Tax Type</td>
                                        <td class="font-weight-bold">{{ $transaction->form_vat_name && !in_array($transaction->status_id, config('global.generated_form')) ? $transaction->form_vat_name : $transaction->vattype->name }}</td>
                                    </tr>
                                    @if ($ua['trans_toggle_conf'] == $non || ($ua['trans_toggle_conf'] == $own && $transaction->owner_id != Auth::user()->id && $transaction->requested_id != Auth::user()->id))
                                    @else
                                        <tr>
                                            <td class="font-weight-bold text-gray">Is Confidential?</td>
                                            <td class="font-weight-bold">
                                                <a href="/transaction/toggle-visibility/{{ $transaction->id }}" class="mr-1" onclick="return confirm('Toggle visibility?');">
                                                    @if ($transaction->is_confidential == 0)
                                                        <i class="material-icons font-weight-bold text-xl text-gray vlign--middle" title="Visible">toggle_off</i>
                                                    @else
                                                        <i class="material-icons font-weight-bold text-xl text-danger vlign--middle" title="Hidden">toggle_on</i>
                                                    @endif
                                                </a> 
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($ua['trans_toggle_conf_own'] == $non || ($ua['trans_toggle_conf_own'] == $own && $transaction->owner_id != Auth::user()->id && $transaction->requested_id != Auth::user()->id))
                                    @else
                                        <tr>
                                            <td class="font-weight-bold text-gray">Is Confidential (Own)?</td>
                                            <td class="font-weight-bold">
                                                <a href="/transaction/toggle-visibility-own/{{ $transaction->id }}" class="mr-1" onclick="return confirm('Toggle visibility?');">
                                                    @if ($transaction->is_confidential_own == 0)
                                                        <i class="material-icons font-weight-bold text-xl text-gray vlign--middle" title="Visible">toggle_off</i>
                                                    @else
                                                        <i class="material-icons font-weight-bold text-xl text-danger vlign--middle" title="Hidden">toggle_on</i>
                                                    @endif
                                                </a> 
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="2">
                                            <span class="font-weight-bold text-gray">Purpose</span>
                                            <p class="mb-0">
                                                @if ($config_confidential)
                                                    -
                                                @else
                                                    {{ $transaction->purpose }}
                                                @endif
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                                @if ($config_confidential)
                                @else
                                    <a class="btn btn-app p-2 {{ $transaction->soa ? '' : 'd-none' }}" href="/storage/public/attachments/soa/{{ $transaction->soa }}" target="_blank">
                                        <i class="align-middle font-weight-bolder material-icons text-orange">folder</i>
                                        <p class="text-dark">SOA</p>
                                    </a>
                                    @if ($transaction->transaction_soa->count() > 0)
                                        @include('pages.admin.transaction.show-attachment-2')
                                    @endif
                                    @if ($transaction->is_reimbursement)
                                        @include('pages.admin.transactionform.show-attachment-2')
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 {{ $config_confidential ? 'd-none' : '' }}">                        
                    <div class="card">
                        <div class="card-body table-responsive">
                            @if (!$transaction->is_reimbursement)
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Qty</th>
                                            <th>Description</th>
                                            <th>Expense Type</th>
                                            <th class="text-right text-nowrap">Unit Price</th>
                                            <th class="text-right">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transaction->transaction_description as $item_desc)
                                            <tr>
                                                <td>{{ $item_desc->qty }}</td>
                                                <td>{{ $item_desc->description }}</td>
                                                <td>{{ $item_desc->expense_type_id ? $item_desc->expensetype->name : $item_desc->particulars->name }}</td>
                                                <td class="text-right">{{ number_format($item_desc->amount, 2, '.', ',') }}</td>
                                                <td class="text-right">{{ number_format($item_desc->amount * $item_desc->qty, 2, '.', ',') }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="font-weight-bold">
                                            <td colspan="3" class="text-right">
                                                {{ $transaction->form_amount_vat && !in_array($transaction->status_id, config('global.generated_form')) ? $transaction->form_amount_vat : (($transaction->vattype->vat 
                                                    + $transaction->form_amount_wht) && !in_array($transaction->status_id, config('global.generated_form')) ? $transaction->form_amount_wht : ($transaction->vattype->wht == 0 ? 'Total' : 'Subtotal')) }}
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
                                <table class="table">
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
                    </div>
                </div>
                @if ($transaction->status_id == 4)
                    <div class="col-lg-7">
                        <div class="card">
                            <div class="card-body table-responsive">
                                <h5>Issuing Details</h5>
                                <table class="table my-3">
                                    <tr>
                                        <td class="font-weight-bold text-gray">Type</td>
                                        <td class="font-weight-bold">{{ $transaction->control_type }}</td>
                                    </tr>  
                                    <tr>
                                        <td class="font-weight-bold text-gray">No.</td>
                                        <td class="font-weight-bold">{{ $transaction->control_no }}</td>
                                    </tr>  
                                    <tr>
                                        <td class="font-weight-bold text-gray">{{ $transaction->is_deposit ? 'Date Deposited' : 'Released Date' }}</td>
                                        <td class="font-weight-bold">{{ $transaction->released_at }}</td>
                                    </tr>  
                                    <tr>
                                        <td class="font-weight-bold text-gray">{{ $transaction->is_deposit ? 'Issued By' : 'Released By' }}</td>
                                        <td class="font-weight-bold">{{ $transaction->releasedby->name }}</td>
                                    </tr>                    
                                    @if ($transaction->is_bank)        
                                        <tr class="{{ $config_confidential ? 'd-none' : '' }}">
                                            <td class="font-weight-bold text-gray">Amount / FX Rate</td>
                                            <td class="font-weight-bold">
                                                {{ $transaction->currency }} {{ number_format($transaction->amount, 2, '.', ',') }}
                                                <span class="small px-2 vlign--top">x</span>
                                                {{ number_format($transaction->currency_2_rate, 2, '.', ',') }}
                                                ({{ $transaction->currency_2 }})
                                            </td>
                                        </tr>  
                                    @endif   
                                    <tr class="{{ $config_confidential ? 'd-none' : '' }}">
                                        <td class="font-weight-bold text-gray">{{ $transaction->is_bank ? 'Transferred ' : '' }}Amount</td>
                                        <td class="font-weight-bold">{{ $transaction->currency_2 ?: $transaction->currency }} {{ number_format($transaction->amount_issued, 2, '.', ',') }}</td>
                                    </tr>  
                                    @if ($transaction->formcompany)
                                        <tr>
                                            <td class="font-weight-bold text-gray">Company</td>
                                            <td class="font-weight-bold">{{ $transaction->formcompany->name }}</td>
                                        </tr>
                                    @endif
                                    @if ($transaction->form_service_charge && $transaction->form_service_charge > 0)
                                        <tr class="{{ $config_confidential ? 'd-none' : '' }}">
                                            <td class="font-weight-bold text-gray">Service Charge</td>
                                            <td class="font-weight-bold">{{ number_format($transaction->form_service_charge, 2, '.', ',') }}</td>
                                        </tr>
                                    @endif
                                </table>
                                @if ($config_confidential)
                                @else
                                    @if ($transaction->is_bank && $transaction->depo_slip)
                                        <a class="btn btn-app p-2" href="/storage/public/attachments/deposit_slip/{{ $transaction->depo_slip }}" target="_blank">
                                            <i class="align-middle font-weight-bolder material-icons text-orange">folder</i>
                                            <p class="text-dark">Attachment</p>
                                        </a>
                                    @endif
                                    @if ($transaction->issue_slip)
                                        <a class="btn btn-app p-2" href="/storage/public/attachments/issue_slip/{{ $transaction->issue_slip }}" target="_blank">
                                            <i class="align-middle font-weight-bolder material-icons text-orange">folder</i>
                                            <p class="text-dark">Attachment</p>
                                        </a>
                                    @endif                                
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                @if ($transaction->status_id == 3)
                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-body">
                                <code class="float-right">{{ $transaction->cancellation_number }}</code>
                                <h5>Cancellation Reason</h5>
                                <div class="pt-3">{{ $transaction->cancellation_reason }}</div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-body table-responsive">
                            <h5>History</h5>
                            <table class="table table-striped table-bordered table-sm small my-3">
                                <tbody>
                                    @foreach ($logs as $item)
                                        <tr>
                                            <td>
                                                @if ($config_confidential)
                                                    <span class="text-secondary">
                                                        @if ($item->description == 'created')
                                                            <i class="align-middle font-weight-bolder material-icons text-md">add</i>
                                                        @else
                                                            <i class="align-middle font-weight-bolder material-icons text-md">edit</i>
                                                        @endif
                                                    </span>
                                                @else
                                                    <a href="#_" data-toggle="modal" data-target="#modal-{{ $item->id }}">
                                                        @if ($item->description == 'created')
                                                            <i class="align-middle font-weight-bolder material-icons text-md">add</i>
                                                        @else
                                                            <i class="align-middle font-weight-bolder material-icons text-md">edit</i>
                                                        @endif
                                                    </a>
                                                @endif
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
                                <div class="d-inline-block small">
                                    {{ $logs->links() }}
                                </div>
                            </div>
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