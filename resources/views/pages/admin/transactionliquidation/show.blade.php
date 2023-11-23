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
                        <a data-toggle="modal" data-target="#modal-liquidate" href="#_" class="btn btn-sm btn-flat mb-2 btn-light col-12 col-lg-auto {{ $perms['can_create'] ? '' : 'd-none' }}"><i class="align-middle font-weight-bolder material-icons text-md">add</i> Add New</a>
                        <a href="/transaction/create/{{ $transaction->trans_type }}/{{ $transaction->project->company_id }}?project_id={{ $transaction->project_id }}&currency={{ $transaction->currency }}&amount={{ $transaction->amount }}&purpose={{ str_replace('%', '', $transaction->purpose) }}&payee={{ $transaction->payee }}&due_at={{ $transaction->due_at }}&requested_id={{ $transaction->requested_id }}&is_deposit={{ $transaction->is_deposit }}&is_bills={{ $transaction->is_bills }}&is_hr={{ $transaction->is_hr }}&is_reimbursement={{ $transaction->is_reimbursement }}&is_bank={{ $transaction->is_bank }}" target="_blank" class="btn btn-sm btn-flat mb-2 btn-light col-12 col-lg-auto {{ $perms['can_duplicate'] ? '' : 'd-none' }}"><i class="align-middle font-weight-bolder material-icons text-md">content_copy</i> Duplicate</a>
                        <a href="/transaction-liquidation/reset/{{ $transaction->id }}" class="btn btn-sm btn-flat mb-2 btn-light col-12 col-lg-auto d-none {{ $perms['can_reset'] ? '' : 'd-none' }}" onclick="return confirm('Are you sure?')"><i class="align-middle font-weight-bolder material-icons text-md">autorenew</i> Renew Edit Limit</a>
                    </div>
                    <div>
                        <a href="#_" class="btn mb-2 btn-sm btn-flat btn-warning col-12 col-lg-auto" data-toggle="modal" data-target="#modal-notes">
                            <i class="align-middle font-weight-bolder material-icons text-md">speaker_notes</i> Notes
                            <span class="badge badge-danger {{ $transaction->notes->count() > 0 ? '' : 'd-none' }}">{{$transaction->notes->count()}}</span>
                        </a>
                        
                        <div class="modal fade text-dark" id="modal-liquidate" tabindex="-1" role="dialog" aria-hidden="true">
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
                                            <input type="submit" class="btn mb-2 btn-primary mt-2" value="Check">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
        
                        <a href="/transaction-liquidation/edit/{{ $transaction->id }}" class="btn mb-2 btn-sm btn-flat btn-primary col-12 col-lg-auto {{ $perms['can_edit'] ? '' : 'd-none' }}"><i class="align-middle font-weight-bolder material-icons text-md">edit</i> Edit</a>
                        <a href="/transaction-liquidation/approval/{{ $transaction->id }}" class="btn mb-2 btn-sm btn-flat btn-success col-12 col-lg-auto {{ $perms['can_approval'] ? '' : 'd-none' }}" onclick="return confirm('Are you sure?')"><i class="align-middle font-weight-bolder material-icons text-md">grading</i> For Approval</a>
                        <a href="#_" class="btn mb-2 btn-sm btn-flat btn-danger col-12 col-lg-auto {{ !$transaction->is_reimbursement ? '' : 'd-none' }} {{ ($ua['form_print'] == $non || ($ua['form_print'] == $own && $transaction->owner_id != Auth::user()->id && $transaction->requested_id != Auth::user()->id)) ? 'd-none' : '' }}" onclick="window.open('/transaction-form/print/{{ $transaction->id }}','name','width=800,height=800')"><i class="align-middle font-weight-bolder material-icons text-md">print</i> Print Generated {{ strtoupper($transaction->trans_type) }} Form</a>
                        <a href="#_" class="btn mb-2 btn-sm btn-flat btn-danger col-12 col-lg-auto {{ $perms['can_print'] ? '' : 'd-none' }}" onclick="window.open('/transaction-liquidation/print/{{ $transaction->id }}','name','width=800,height=800')"><i class="align-middle font-weight-bolder material-icons text-md">print</i>
                            Print
                            @if ($transaction->is_deposit)
                                {{ config('global.trans_category_label_liq_print')[1] }}
                            @elseif ($transaction->is_bills)    
                                {{ config('global.trans_category_label_liq_print')[2] }}
                            @elseif ($transaction->is_hr)    
                                {{ config('global.trans_category_label_liq_print')[3] }}
                            @elseif ($transaction->is_reimbursement)    
                                {{ config('global.trans_category_label_liq_print')[4] }}
                            @elseif ($transaction->is_bank)    
                                {{ config('global.trans_category_label_liq_print')[5] }}
                            @else
                                {{ config('global.trans_category_label_liq_print')[0] }}
                            @endif
                        </a>
                        <a href="#_" class="btn mb-2 btn-sm btn-flat btn-success col-12 col-lg-auto {{ $perms['can_clear'] ? '' : 'd-none' }} px-4" data-toggle="modal" data-target="#modal-clear"><i class="align-middle font-weight-bolder material-icons text-md">payments</i> Clear / Deposit</a>
                        <a href="#_" class="btn mb-2 btn-sm btn-flat btn-primary col-12 col-lg-auto {{ $perms['can_edit_cleared'] && $transaction->liq_balance != 0 ? '' : 'd-none' }} px-4" data-toggle="modal" data-target="#modal-clear-edit"><i class="align-middle font-weight-bolder material-icons text-md">{{ !$transaction->is_bills && !$transaction->is_hr ? 'edit' : 'visibility' }}</i> {{ !$transaction->is_bills && !$transaction->is_hr ? 'Edit' : 'View' }} Deposit Info</a>
                    </div>
                </div>
            </div>

            <div class="text-dark">
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
                                    <form action="/transaction-liquidation/clear/{{ $transaction->id }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <table class="table table-bordered {{ $config_confidential ? 'd-none' : '' }}">
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
                                                        {{ $transaction->currency_2 ?: $transaction->currency }}
                                                        {{ number_format($transaction->amount_issued, 2, '.', ',') }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $transaction->currency_2 ?: $transaction->currency }}
                                                        {{ number_format($transaction->liq_subtotal, 2, '.', ',') }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $transaction->currency_2 ?: $transaction->currency }}
                                                        {{ number_format($transaction->liq_balance >= 0 ? $transaction->liq_balance : $transaction->liq_balance*-1, 2, '.', ',') }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @if (!$transaction->is_bills && !$transaction->is_hr)
                                            @if ($transaction->liq_balance != 0)
                                                <div class="row">                                            
                                                    <div class="mt-4 col-lg-4">
                                                        <label for="" class="font-weight-bold">Mode</label>
                                                        <select name="depo_type" class="depo_type form-control @error('depo_type') is-invalid @enderror" required>
                                                            @foreach (config('global.deposit_type') as $item)
                                                                <option value="{{ $item }}">{{ $item }}</option>
                                                            @endforeach
                                                        </select>
                                                        @include('errors.inline', ['message' => $errors->first('depo_type')])
                                                    </div>
                                                    <div class="mt-4 col-md-8">
                                                        <label for="" class="font-weight-bold">Received from: (Type/Account #)</label>
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
                                                </div>
                                                <div class="row"> 
                                                    <div class="mt-4 col-md-4">
                                                        <label for="" class="font-weight-bold">Reference Code</label>
                                                        <input type="text" name="depo_ref" class="depo_ref form-control @error('depo_ref') is-invalid @enderror" required>
                                                        @include('errors.inline', ['message' => $errors->first('depo_ref')])
                                                    </div>
                                                    <div class="mt-4 col-md-8">
                                                        <label for="" class="font-weight-bold">Received By</label>
                                                        <input type="text" name="depo_received_by" class="form-control @error('depo_received_by') is-invalid @enderror" required>
                                                        @include('errors.inline', ['message' => $errors->first('depo_received_by')])
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <div class="mt-4 col-md-4">
                                                        <label for="" class="font-weight-bold">Deposited/Received Date</label>
                                                        <input type="date" name="depo_date" class="form-control @error('depo_date') is-invalid @enderror" required>
                                                        @include('errors.inline', ['message' => $errors->first('depo_date')])
                                                    </div>
                                                    <div class="mt-4 col-md-8">
                                                        <label for="" class="font-weight-bold">Attachment <small>( Accepts .jpg, .png and .pdf file types, not more than 5mb. )</small></label>
                                                        <input type="file" name="depo_slip" class="form-control @error('depo_slip') is-invalid @enderror" required>
                                                        @include('errors.inline', ['message' => $errors->first('depo_slip')])
                                                    </div>
                                                </div>
                                            @endif
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
                                        <table class="table table-bordered {{ $config_confidential ? 'd-none' : '' }}">
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
                                                        {{ $transaction->currency_2 ?: $transaction->currency }}
                                                        {{ number_format($transaction->amount_issued, 2, '.', ',') }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $transaction->currency_2 ?: $transaction->currency }}
                                                        {{ number_format($transaction->liq_subtotal, 2, '.', ',') }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $transaction->currency_2 ?: $transaction->currency }}
                                                        {{ number_format($transaction->liq_balance >= 0 ? $transaction->liq_balance : $transaction->liq_balance*-1, 2, '.', ',') }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @if (!$transaction->is_bills && !$transaction->is_hr)
                                            @if ($transaction->liq_balance != 0)
                                                <div class="row">                                            
                                                    <div class="mt-4 col-md-4">
                                                        <label for="" class="font-weight-bold">Type</label>
                                                        <select name="depo_type" class="depo_type form-control @error('depo_type') is-invalid @enderror" required>
                                                            @foreach (config('global.deposit_type') as $item)
                                                                <option value="{{ $item }}" {{ $transaction->depo_type == $item ? 'selected' : '' }}>{{ $item }}</option>
                                                            @endforeach
                                                        </select>
                                                        @include('errors.inline', ['message' => $errors->first('depo_type')])
                                                    </div>
                                                    <div class="mt-4 col-md-8">
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
                                                </div>
                                                <div class="row">      
                                                    <div class="mt-4 col-md-4">
                                                        <label for="" class="font-weight-bold">Reference Code</label>
                                                        <input type="text" name="depo_ref" class="depo_ref form-control @error('depo_ref') is-invalid @enderror" value="{{ $transaction->depo_ref }}" required>
                                                        @include('errors.inline', ['message' => $errors->first('depo_ref')])
                                                    </div>
                                                    <div class="mt-4 col-md-8">
                                                        <label for="" class="font-weight-bold">Received By</label>
                                                        <input type="text" name="depo_received_by" class="form-control @error('depo_received_by') is-invalid @enderror"  value="{{ $transaction->depo_received_by }}" required>
                                                        @include('errors.inline', ['message' => $errors->first('depo_received_by')])
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <div class="mt-4 col-md-4">
                                                        <label for="" class="font-weight-bold">Date Deposited</label>
                                                        <input type="date" name="depo_date" class="form-control @error('depo_date') is-invalid @enderror" value="{{ $transaction->depo_date }}" required>
                                                        @include('errors.inline', ['message' => $errors->first('depo_date')])
                                                    </div>
                                                    @if (!$transaction->is_deposit)
                                                        <div class="mt-4 col-md-8">
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

                @include('pages.admin.transaction.notes')
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
                                    @if ($transaction->liquidation_approver_id && !$transaction->is_deposit && !$transaction->is_bills && !$transaction->is_hr)
                                        <tr>
                                            <td class="font-weight-bold text-gray">Auth. Approver</td>
                                            <td class="font-weight-bold">
                                                <img src="/storage/public/images/users/{{ $transaction->liquidationapprover->avatar }}" class="img-circle img-size-32 mr-2">
                                                {{ $transaction->liquidationapprover->name }}
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
                                    <tr>
                                        <td class="font-weight-bold text-gray">Cost Control No.</td>
                                        {{-- <td class="font-weight-bold">{{ $transaction->cost_type_id
                                            ? $transaction->project->company->qb_code
                                                .'.'
                                                .$transaction->project->company->qb_no.$transaction->cost_type->control_no
                                                .'.'
                                                .sprintf("%03d", $transaction->cost_seq)
                                                .'.'
                                                .config('global.cost_control_v')
                                            : '-' }}
                                        </td> --}}
                                        <td class="font-weight-bold">{{ $transaction->cost_control_no }}</td>
                                    </tr>
                                    @if (1==0)
                                        <tr>
                                            <td class="font-weight-bold text-gray">Particulars</td>
                                            <td class="font-weight-bold">{{ $trans_page_url == 'prpo' ? $transaction->particulars->name : $transaction->particulars_custom }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td class="font-weight-bold text-gray">{{ !$transaction->is_reimbursement ? 'Payor' : 'Payee' }}</td>
                                        <td class="font-weight-bold">{{ $transaction->payor ?: 'n/a' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-gray">Category / Class</td>
                                        <td class="font-weight-bold">{{ $transaction->coatagging->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-gray">Vendor / Payee</td>
                                        <td class="font-weight-bold">{{ $transaction->payee }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-gray">Tax Type</td>
                                        <td class="font-weight-bold">{{ $transaction->form_vat_name ? $transaction->form_vat_name : $transaction->vattype->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-gray">Issue Type</td>
                                        <td class="font-weight-bold">{{ $transaction->control_type }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-gray">Issue No.</td>
                                        <td class="font-weight-bold">{{ $transaction->control_no }}</td>
                                    </tr>
                                    @if ($transaction->is_bank)
                                        <tr>
                                            <td class="font-weight-bold text-gray">Transferred To</td>
                                            <td class="font-weight-bold">{{ $transaction->formcompany->name }}</td>
                                        </tr> 
                                    @endif
                                    <tr>
                                        <td class="font-weight-bold text-gray">Released Date</td>
                                        <td class="font-weight-bold">{{ $transaction->released_at }}</td>
                                    </tr>
                                    @if ($transaction->form_service_charge && $transaction->form_service_charge > 0)
                                        <tr>
                                            <td class="font-weight-bold text-gray">Released By</td>
                                            <td class="font-weight-bold">{{ $transaction->releasedby->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold text-gray">Service Charge</td>
                                            <td class="font-weight-bold">
                                                @if ($config_confidential)
                                                    -
                                                @else
                                                    {{ $transaction->form_service_charge_currency_id.' '.number_format($transaction->form_service_charge, 2, '.', ',') }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold text-gray">Amount FX Rate</td>
                                            <td class="font-weight-bold">
                                                @if ($config_confidential)
                                                    -
                                                @else
                                                    {{ $transaction->currency }} {{ number_format($transaction->amount, 2, '.', ',') }}
                                                    <span class="small px-2 vlign--top">x</span>
                                                    {{ number_format($transaction->currency_2_rate, 2, '.', ',') }}
                                                    ({{ $transaction->currency_2 }})
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td class="font-weight-bold text-gray">{{ $transaction->is_bank ? 'Transferred' : 'Released' }} Amount</td>
                                        <td class="font-weight-bold">
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ $transaction->currency_2 ?: $transaction->currency }} {{ number_format($transaction->amount_issued, 2, '.', ',') }}
                                            @endif
                                        </td>
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
                                    @if (!$transaction->is_deposit && !$transaction->is_bills && !$transaction->is_hr)
                                        @include('pages.admin.transactionliquidation.show-attachment-2')
                                    @endif
                                    @if ($transaction->transaction_soa->count() > 0)
                                        @include('pages.admin.transaction.show-attachment-2')
                                    @endif
                                    <a class="btn btn-app p-2 {{ $transaction->soa ? '' : 'd-none' }}" href="/storage/public/attachments/soa/{{ $transaction->soa }}" target="_blank">
                                        <i class="align-middle font-weight-bolder material-icons text-orange">folder</i>
                                        <p class="text-dark">SOA</p>
                                    </a>
                                    @if ($transaction->issue_slip)
                                        <a class="btn btn-app p-2" href="/storage/public/attachments/issue_slip/{{ $transaction->issue_slip }}" target="_blank">
                                            <i class="align-middle font-weight-bolder material-icons text-orange">folder</i>
                                            <p class="text-dark">Slip</p>
                                        </a>
                                    @endif   
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 {{ $config_confidential ? 'd-none' : '' }}">
                    @if (!$transaction->is_reimbursement)
                        <div class="card">
                            <div class="card-body table-responsive">
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
                                                <td>{{ $item_desc->particulars_id ? $item_desc->particulars->name : $item_desc->expensetype->name }}</td>
                                                <td class="text-right">{{ number_format($item_desc->amount, 2, '.', ',') }}</td>
                                                <td class="text-right">{{ number_format($item_desc->amount * $item_desc->qty, 2, '.', ',') }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="font-weight-bold">
                                            <td colspan="3" class="text-right">
                                                {{ $transaction->form_amount_vat && !in_array($transaction->status_id, config('global.generated_form')) ? $transaction->form_amount_vat : ($transaction->vattype->vat 
                                                    + $transaction->form_amount_wht && !in_array($transaction->status_id, config('global.generated_form')) ? $transaction->form_amount_wht : ($transaction->vattype->wht == 0 ? 'Total' : 'Subtotal')) }}
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
                                            <td colspan="3" class="text-right text-nowrap">Less Withholding Tax</td>
                                            <td class="text-right">{{ $transaction->form_vat_wht && !in_array($transaction->status_id, config('global.generated_form')) ? $transaction->form_vat_name." (".$transaction->form_vat_wht."%)" : $transaction->vattype->name." (".$transaction->vattype->wht."%)" }}</td>
                                            <td class="text-right">({{ number_format($transaction->form_amount_wht ? $transaction->form_amount_wht : $transaction->custom_wht, 2, '.', ',') }})</td>
                                        </tr>
                                        @endif
                                        @if ($transaction->custom_vat > 0 || ($transaction->form_amount_payable && !in_array($transaction->status_id, config('global.generated_form'))))
                                        <tr class="font-weight-bold">
                                            <td colspan="3" class="text-right text-nowrap">Total Payable</td>
                                            <td colspan="2" class="text-right">{{ number_format($transaction->form_amount_payable && !in_array($transaction->status_id, config('global.generated_form')) ? $transaction->form_amount_payable : $transaction->custom_total_payable, 2, '.', ',') }}</td>
                                        </tr>
                                        @endif
                                        <tr class="font-weight-bold border-top-2">
                                            <td colspan="3" class="text-right">Amount</td>
                                            <td colspan="2" class="text-right text-nowrap">{{ $transaction->currency }} {{ number_format($transaction->form_amount_payable && !in_array($transaction->status_id, config('global.generated_form')) ? $transaction->form_amount_payable : $transaction->custom_total_payable, 2, '.', ',') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif  
                    <div class="card {{ $transaction->is_deposit || $transaction->is_hr || $transaction->is_bank ? 'd-none' : '' }}">
                        <div class="card-body table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="border-top">
                                        <th>Date</th>
                                        <th>Project</th>
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
                                            <td class="text-nowrap">{{ $item->project_id ? $item->project->project : 'n/a' }}</td>
                                            <td class="text-nowrap">{{ $item->expensetype->name }}</td>
                                            <td>{{ $item->description }}</td>
                                            <td>{{ $item->location }}</td>
                                            <td class="text-center">{{ $item->receipt ? 'Y' : 'N' }}</td>
                                            <td class="text-right">{{ number_format($item->amount, 2, '.', ',') }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="7" class="py-4"></td>
                                    </tr>
                                    @foreach ($transaction_summary as $index => $item)
                                        <tr>
                                            <td class="{{ $index == 0 ? 'font-weight-bold' : '' }}">{{ $index == 0 ? 'Type' : '' }}</td>
                                            <td class="bg-white" colspan="6">
                                                {{ $item->name }}
                                                <span class="float-right">{{ number_format($item->amount, 2, '.', ',') }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="{{ count($transaction_summary_proj) == 0 ? 'd-none' : '' }}">
                                        <td colspan="7" class="py-4"></td>
                                    </tr>
                                    @foreach ($transaction_summary_proj as $index => $item)
                                        <tr>
                                            <td class="{{ $index == 0 ? 'font-weight-bold' : '' }}">{{ $index == 0 ? 'Project' : '' }}</td>
                                            <td class="bg-white" colspan="6">
                                                {{ $item->project }}
                                                <span class="float-right">{{ number_format($item->amount, 2, '.', ',') }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="7" class="py-4"></td>
                                    </tr>
                                    @if ($transaction->liq_before_vat)
                                        <tr>
                                            <td colspan="5" class="font-weight-bold small text-right">Before VAT</td>
                                            <td colspan="2" class="bg-white text-right">
                                                <span class="float-left">{{ $transaction->currency }}</span>
                                                {{ number_format($transaction->liq_before_vat, 2, '.', ',') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="font-weight-bold small text-right">VAT (12%)</td>
                                            <td colspan="2" class="bg-white text-right font-italic">
                                                <span class="float-left">{{ $transaction->currency }}</span>
                                                {{ number_format($transaction->liq_vat, 2, '.', ',') }}
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="5" class="font-weight-bold small text-right">Subtotal</td>
                                        <td colspan="2" class="bg-white text-right font-weight-bold">
                                            <span class="float-left">{{ $transaction->currency }}</span>
                                            {{ number_format($transaction->liq_subtotal, 2, '.', ',') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="font-weight-bold small text-right">Less: Deposit/Payment</td>
                                        <td colspan="2" class="bg-white text-right text-danger">
                                            <span class="float-left">{{ $transaction->currency }}</span>
                                            {{ number_format($transaction->amount_issued, 2, '.', ',') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="small font-weight-bold text-right">Balance</td>
                                        <td colspan="2" class="bg-white text-right font-weight-bold">
                                            <span class="float-left">{{ $transaction->currency }}</span>
                                            {{ number_format($transaction->liq_balance, 2, '.', ',') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="small text-right">
                                            <span>(+) For Reimbursement / (-) Return Money</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>                  
                </div>
                @if (in_array($transaction->status_id, config('global.liquidation_cleared')))
                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-body table-responsive">
                                <h5>Clearing Information</h5>
                                <table class="table my-3">
                                    @if ($transaction->liq_balance != 0 || $transaction->is_reimbursement)
                                        @if (!$transaction->is_reimbursement)
                                            <tr>
                                                <td class="border-0 font-weight-bold text-gray">Amt. {{ $transaction->liq_balance >= 0 ? 'Reimbursed' : 'Returned' }}</td>
                                                <td class="border-0 font-weight-bold">
                                                    @if ($config_confidential)
                                                        -
                                                    @else
                                                        {{ $transaction->currency_2 ?: $transaction->currency }}
                                                        {{ number_format($transaction->liq_balance >= 0 ? $transaction->liq_balance : $transaction->liq_balance*-1, 2, '.', ',') }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif                                    
                                        
                                        @if (!$transaction->is_bills && !$transaction->is_hr && !$transaction->is_reimbursement && !$transaction->is_bank)
                                            <tr>
                                                <td class="font-weight-bold text-gray">Type</td>
                                                <td class="font-weight-bold">{{ $transaction->depo_type }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold text-gray">Bank</td>
                                                <td class="font-weight-bold">{{ $transaction->bankbranch->bank->name }} ({{ $transaction->bankbranch->name }})</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold text-gray">Reference Code</td>
                                                <td class="font-weight-bold">{{ $transaction->depo_ref }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold text-gray">Received By</td>
                                                <td class="font-weight-bold">{{ $transaction->depo_received_by }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold text-gray">Date Deposited</td>
                                                <td class="font-weight-bold">{{ $transaction->depo_date }}</td>
                                            </tr>    
                                        @endif
                                        
                                        @if ($transaction->is_deposit && $transaction->liquidation_approver_id)
                                            <tr>
                                                <td class="font-weight-bold text-gray">Deposited By</td>
                                                <td class="font-weight-bold">{{ $transaction->liquidationapprover->name }}</td>
                                            </tr>
                                        @endif
                                    @else
                                        <tr>
                                            <td colspan="2">No deposit information.</td>
                                        </tr>
                                    @endif                                    
                                </table>
                                @if ($config_confidential)
                                @else
                                    @if (($transaction->is_deposit && $transaction->liquidation_approver_id)
                                        || ($transaction->is_bills || $transaction->is_hr))
                                        @include('pages.admin.transactionliquidation.show-attachment-2')
                                    @endif
                                    @if ($transaction->depo_slip)
                                        <a class="btn btn-app p-2" href="/storage/public/attachments/deposit_slip/{{ $transaction->depo_slip }}" target="_blank">
                                            <i class="align-middle font-weight-bolder material-icons text-orange">folder</i>
                                            <p class="text-dark">Slip</p>
                                        </a>
                                    @endif                                
                                @endif
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
        })
    </script>
@endsection