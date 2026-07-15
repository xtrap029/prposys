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
                            @elseif ($transaction->is_tdsa_bill)    
                                {{ config('global.trans_category_label')[6] }}
                            @elseif ($transaction->is_tdsa_payment)    
                                {{ config('global.trans_category_label')[7] }}
                            @elseif ($transaction->is_aec_bill)    
                                {{ config('global.trans_category_label')[8] }}
                            @elseif ($transaction->is_aec_payment)    
                                {{ config('global.trans_category_label')[9] }}
                            @elseif ($transaction->is_aff_advances)    
                                {{ config('global.trans_category_label')[10] }}
                            @else
                                {{ config('global.trans_category_label')[0] }}    
                            @endif
                        </span>
                        <span class="badge badge-pill bg-purple p-2">{{ $transaction->status->name }}</span>
                        <a href="#_" class="badge badge-pill bg-secondary p-2 d-lg-none" data-toggle="modal" data-target="#modal-notes">
                            <i class="align-middle material-icons text-xs">speaker_notes</i> Notes
                            <span class="badge badge-danger {{ $transaction->notes->count() > 0 ? '' : 'd-none' }}">{{$transaction->notes->count()}}</span>
                        </a>
                    </div>
                </div>
                <div class="mt-4 w-100 d-lg-none">
                    <div class="row">
                        <div class="col-6 px-1">
                            <a href="/transaction/{{ $trans_page_url }}/{{ $transaction->project->company_id }}{{ isset($_GET['page']) ? '?page='.$_GET['page'] : '' }}" class="btn btn-sm btn-flat mb-2 btn-light col-12 col-lg-auto"><i class="align-middle font-weight-bolder material-icons text-md">arrow_back_ios</i> Back</a>
                        </div>
                        <div class="col-6 px-1 {{ $perms['can_create'] ? '' : 'd-none' }}">
                            <a data-toggle="modal" data-target="#modal-liquidate" href="#_" class="btn btn-sm btn-flat mb-2 btn-light col-12 col-lg-auto"><i class="align-middle font-weight-bolder material-icons text-md">add</i> Add New</a>
                        </div>
                        <div class="col-6 px-1 {{ $perms['can_duplicate'] ? '' : 'd-none' }}">
                            <a href="/transaction/create/{{ $transaction->trans_type }}/{{ $transaction->project->company_id }}?project_id={{ $transaction->project_id }}&currency={{ $transaction->currency }}&amount={{ $transaction->amount }}&purpose={{ str_replace('%', '', $transaction->purpose) }}&vendor_id={{ $transaction->vendor_id ?: $transaction->payee }}&due_at={{ $transaction->due_at }}&requested_id={{ $transaction->requested_id }}&is_deposit={{ $transaction->is_deposit }}&is_bills={{ $transaction->is_bills }}&is_hr={{ $transaction->is_hr }}&is_reimbursement={{ $transaction->is_reimbursement }}&is_bank={{ $transaction->is_bank }}&is_tdsa_bill={{ $transaction->is_tdsa_bill }}&is_tdsa_payment={{ $transaction->is_tdsa_payment }}&is_aec_bill={{ $transaction->is_aec_bill }}&is_aec_payment={{ $transaction->is_aec_payment }}&is_aff_advances={{ $transaction->is_aff_advances }}" target="_blank" class="btn btn-sm btn-flat mb-2 btn-light col-12 col-lg-auto"><i class="align-middle font-weight-bolder material-icons text-md">content_copy</i> Duplicate</a>
                        </div>
                        <div class="col-6 px-1 {{ $perms['can_edit'] ? '' : 'd-none' }}">
                            <a href="/transaction-liquidation/edit/{{ $transaction->id }}" class="btn mb-2 btn-sm btn-flat btn-primary col-12 col-lg-auto"><i class="align-middle font-weight-bolder material-icons text-md">edit</i> Edit</a>
                        </div>
                        <div class="col-6 px-1 {{ $perms['can_approval'] ? '' : 'd-none' }}">
                            <a href="/transaction-liquidation/approval/{{ $transaction->id }}" class="btn mb-2 btn-sm btn-flat btn-success col-12 col-lg-auto" onclick="return confirm('Are you sure?')"><i class="align-middle font-weight-bolder material-icons text-md">grading</i> For Approval</a>
                        </div>
                        <div class="col-6 px-1 {{ !$transaction->is_reimbursement ? '' : 'd-none' }} {{ ($ua['form_print'] == $non || ($ua['form_print'] == $own && $transaction->owner_id != Auth::user()->id && $transaction->requested_id != Auth::user()->id)) ? 'd-none' : '' }}">
                            <a href="#_" class="btn mb-2 btn-sm btn-flat btn-danger col-12 col-lg-auto" onclick="window.open('/transaction-form/print/{{ $transaction->id }}','name','width=800,height=800')"><i class="align-middle font-weight-bolder material-icons text-md">print</i> Print Generated {{ strtoupper($transaction->trans_type) }} Form</a>
                        </div>
                        <div class="col-6 px-1 {{ $perms['can_print'] ? '' : 'd-none' }}">
                            <a href="#_" class="btn mb-2 btn-sm btn-flat btn-danger col-12 col-lg-auto" onclick="window.open('/transaction-liquidation/print/{{ $transaction->id }}','name','width=800,height=800')"><i class="align-middle font-weight-bolder material-icons text-md">print</i>
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
                                @elseif ($transaction->is_tdsa_bill)    
                                    {{ config('global.trans_category_label_liq_print')[6] }}
                                @elseif ($transaction->is_tdsa_payment)    
                                    {{ config('global.trans_category_label_liq_print')[7] }}
                                @elseif ($transaction->is_aec_bill)    
                                    {{ config('global.trans_category_label_liq_print')[8] }}
                                @elseif ($transaction->is_aec_payment)    
                                    {{ config('global.trans_category_label_liq_print')[9] }}
                                @elseif ($transaction->is_aff_advances)    
                                    {{ config('global.trans_category_label_liq_print')[10] }}
                                @else
                                    {{ config('global.trans_category_label_liq_print')[0] }}
                                @endif
                            </a>
                        </div>
                        <div class="col-6 px-1 {{ $perms['can_hierarchy_approve'] ? '' : 'd-none' }}">
                            <a href="#_" class="btn mb-2 btn-sm btn-flat btn-light col-12 col-lg-auto" data-toggle="modal" data-target="#modal-hierarchy-approve"><i class="align-middle font-weight-bolder material-icons text-md">check</i> Approve</a>
                        </div>
                        <div class="col-6 px-1 {{ $perms['can_hierarchy_disapprove'] ? '' : 'd-none' }}">
                            <a href="#_" class="btn mb-2 btn-sm btn-flat btn-danger col-12 col-lg-auto" data-toggle="modal" data-target="#modal-hierarchy-disapprove"><i class="align-middle font-weight-bolder material-icons text-md">close</i> Disapprove</a>
                        </div>
                        <div class="col-6 px-1 {{ $perms['can_clear'] ? '' : 'd-none' }}">
                            <a href="#_" class="btn mb-2 btn-sm btn-flat btn-success col-12 col-lg-auto px-4" data-toggle="modal" data-target="#modal-clear"><i class="align-middle font-weight-bolder material-icons text-md">payments</i> Clear / Deposit</a>
                        </div>
                        <div class="col-6 px-1 {{ $perms['can_edit_cleared'] && $transaction->liq_balance != 0 ? '' : 'd-none' }}">
                            <a href="#_" class="btn mb-2 btn-sm btn-flat btn-primary col-12 col-lg-auto px-4" data-toggle="modal" data-target="#modal-clear-edit"><i class="align-middle font-weight-bolder material-icons text-md">{{ !$transaction->is_bills && !$transaction->is_hr ? 'edit' : 'visibility' }}</i> {{ !$transaction->is_bills && !$transaction->is_hr ? 'Edit' : 'View' }} Deposit Info</a>
                        </div>
                        
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
                    </div>
                </div>
                <div class="col-lg-6 text-right mt-4 d-none d-lg-block">
                    <div>
                        <a href="/transaction/{{ $trans_page_url }}/{{ $transaction->project->company_id }}{{ isset($_GET['page']) ? '?page='.$_GET['page'] : '' }}" class="btn btn-sm btn-flat mb-2 btn-light col-12 col-lg-auto"><i class="align-middle font-weight-bolder material-icons text-md">arrow_back_ios</i> Back</a>
                        <a data-toggle="modal" data-target="#modal-liquidate" href="#_" class="btn btn-sm btn-flat mb-2 btn-light col-12 col-lg-auto {{ $perms['can_create'] ? '' : 'd-none' }}"><i class="align-middle font-weight-bolder material-icons text-md">add</i> Add New</a>
                        <a href="/transaction/create/{{ $transaction->trans_type }}/{{ $transaction->project->company_id }}?project_id={{ $transaction->project_id }}&currency={{ $transaction->currency }}&amount={{ $transaction->amount }}&purpose={{ str_replace('%', '', $transaction->purpose) }}&vendor_id={{ $transaction->vendor_id ?: $transaction->payee }}&due_at={{ $transaction->due_at }}&requested_id={{ $transaction->requested_id }}&is_deposit={{ $transaction->is_deposit }}&is_bills={{ $transaction->is_bills }}&is_hr={{ $transaction->is_hr }}&is_reimbursement={{ $transaction->is_reimbursement }}&is_bank={{ $transaction->is_bank }}&is_tdsa_bill={{ $transaction->is_tdsa_bill }}&is_tdsa_payment={{ $transaction->is_tdsa_payment }}&is_aec_bill={{ $transaction->is_aec_bill }}&is_aec_payment={{ $transaction->is_aec_payment }}&is_aff_advances={{ $transaction->is_aff_advances }}" target="_blank" class="btn btn-sm btn-flat mb-2 btn-light col-12 col-lg-auto {{ $perms['can_duplicate'] ? '' : 'd-none' }}"><i class="align-middle font-weight-bolder material-icons text-md">content_copy</i> Duplicate</a>
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
                            @elseif ($transaction->is_tdsa_bill)    
                                {{ config('global.trans_category_label_liq_print')[6] }}
                            @elseif ($transaction->is_tdsa_payment)    
                                {{ config('global.trans_category_label_liq_print')[7] }}
                            @elseif ($transaction->is_aec_bill)    
                                {{ config('global.trans_category_label_liq_print')[8] }}
                            @elseif ($transaction->is_aec_payment)    
                                {{ config('global.trans_category_label_liq_print')[9] }}
                            @elseif ($transaction->is_aff_advances)    
                                {{ config('global.trans_category_label_liq_print')[10] }}
                            @else
                                {{ config('global.trans_category_label_liq_print')[0] }}
                            @endif
                        </a>
                        <a href="#_" class="btn mb-2 btn-sm btn-flat btn-light col-12 col-lg-auto {{ $perms['can_hierarchy_approve'] ? '' : 'd-none' }}" data-toggle="modal" data-target="#modal-hierarchy-approve"><i class="align-middle font-weight-bolder material-icons text-md">check</i> Approve</a>
                        <a href="#_" class="btn mb-2 btn-sm btn-flat btn-danger col-12 col-lg-auto {{ $perms['can_hierarchy_disapprove'] ? '' : 'd-none' }}" data-toggle="modal" data-target="#modal-hierarchy-disapprove"><i class="align-middle font-weight-bolder material-icons text-md">close</i> Disapprove</a>
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

                @if ($perms['can_hierarchy_approve'])
                    <div class="modal fade" id="modal-hierarchy-approve" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-md" role="document">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title">Approve Liquidation</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <form action="/transaction-liquidation/hierarchy-approve/{{ $transaction->id }}" method="post">
                                        @csrf
                                        @method('put')
                                        <textarea name="note" class="form-control @error('note') is-invalid @enderror" rows="4" placeholder="Add approval notes/comments (optional)"></textarea>
                                        @include('errors.inline', ['message' => $errors->first('note')])
                                        <input type="submit" class="btn btn-primary mt-2" value="Approve Now">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if ($perms['can_hierarchy_disapprove'])
                    <div class="modal fade" id="modal-hierarchy-disapprove" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-md" role="document">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title">Disapprove Liquidation</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <form action="/transaction-liquidation/hierarchy-disapprove/{{ $transaction->id }}" method="post">
                                        @csrf
                                        @method('put')
                                        <textarea name="note" class="form-control @error('note') is-invalid @enderror" rows="4" placeholder="Remarks are required" required></textarea>
                                        @include('errors.inline', ['message' => $errors->first('note')])
                                        <input type="submit" class="btn btn-danger mt-2" value="Disapprove">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($perms['can_reassign_approver'])
                    <div class="modal fade" id="modal-reassign-approver" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-md" role="document">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title">Reassign Approver</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Select Approver</label>
                                        <select name="user_id" form="form-reassign-approver" class="form-control" required>
                                            <option value="">-- Select User --</option>
                                            @foreach ($users as $approver)
                                                <option value="{{ $approver->id }}" {{ $transaction->liq_assigned_approver_id == $approver->id ? 'selected' : '' }}>{{ $approver->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <form id="form-reassign-approver" action="/transaction-liquidation/reassign-approver/{{ $transaction->id }}" method="post">
                                            @csrf
                                            @method('put')
                                            <button type="submit" class="btn btn-primary">Reassign</button>
                                        </form>
                                        @if ($transaction->liq_assigned_approver_id)
                                            <form action="/transaction-liquidation/clear-reassigned-approver/{{ $transaction->id }}" method="post">
                                                @csrf
                                                @method('put')
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Clear reassigned approver?')"><i class="align-middle material-icons text-md">clear</i> Clear Reassign</button>
                                            </form>
                                        @endif
                                    </div>
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
                                                        <label for="" class="font-weight-bold">Attachment <small>( Accepts .jpg, .png and .pdf file types, not more than {{ config('global.max_tl_deposit') }}. )</small></label>
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
                                                            <label for="" class="font-weight-bold">Replace Slip Attachment <small>( Accepts .jpg, .png and .pdf file types, not more than {{ config('global.max_tl_deposit') }}. )</small></label>
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
                            <div class="table" style="overflow: auto;">
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
                                    {{-- Form-stage "Approved By" (hierarchy_approver_id) hidden on liquidation view to avoid confusion with Liq. Approved By --}}
                                    @if ($transaction->liquidation_approver_id && !$transaction->is_deposit && !$transaction->is_bills && !$transaction->is_hr)
                                        <tr>
                                            <td class="font-weight-bold text-gray">Auth. Approver</td>
                                            <td class="font-weight-bold">
                                                <img src="/storage/public/images/users/{{ $transaction->liquidationapprover->avatar }}" class="img-circle img-size-32 mr-2">
                                                {{ $transaction->liquidationapprover->name }}
                                            </td>
                                        </tr>
                                    @endif
                                    @php
                                        $liqPendingApprover = null;
                                        $liqPendingApprovers = collect();
                                        if (!$transaction->hierarchy_liq_approver_id && in_array($transaction->status_id, config('global.liquidation_approval'))) {
                                            if ($transaction->liq_assigned_approver_id) {
                                                $liqPendingApprover = $transaction->liqassignedapprover;
                                            } else {
                                                $requestor = $transaction->requested;
                                                if ($transaction->class_type_id) {
                                                    $liqPendingApprovers = \App\ClassTypeCompanyApprover::where('class_type_id', $transaction->class_type_id)
                                                        ->where('company_id', $transaction->project->company_id)
                                                        ->with('user')
                                                        ->get()
                                                        ->pluck('user')
                                                        ->filter();
                                                }
                                                if ($liqPendingApprovers->isEmpty()) {
                                                    if ($requestor->approver_id) {
                                                        $liqPendingApprover = \App\User::find($requestor->approver_id);
                                                    } else {
                                                        $requestorHierarchy = \App\Hierarchy::where('user_id', $requestor->id)
                                                            ->where('company_id', $transaction->project->company_id)
                                                            ->first();
                                                        if ($requestorHierarchy && $requestorHierarchy->parent_id) {
                                                            $liqPendingApprover = \App\User::find($requestorHierarchy->parent_id);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        $hasLiqApprover = $liqPendingApprover || $liqPendingApprovers->isNotEmpty();
                                    @endphp
                                    @if ($transaction->hierarchy_liq_approver_id || in_array($transaction->status_id, config('global.liquidation_approval')))
                                        <tr>
                                            <td class="font-weight-bold text-gray">{{ $transaction->hierarchy_liq_approver_id ? 'Liq. Approved By' : 'Liq. Approver' }}</td>
                                            <td class="font-weight-bold">
                                                @if ($transaction->hierarchy_liq_approver_id)
                                                    <img src="/storage/public/images/users/{{ $transaction->hierarchyliqapprover->avatar }}" class="img-circle img-size-32 mr-2">
                                                    {{ $transaction->hierarchyliqapprover->name }}
                                                @elseif ($liqPendingApprovers->isNotEmpty())
                                                    @foreach ($liqPendingApprovers as $ca)
                                                        <div class="mb-1">
                                                            <img src="/storage/public/images/users/{{ $ca->avatar }}" class="img-circle img-size-32 mr-1">
                                                            {{ $ca->name }}
                                                        </div>
                                                    @endforeach
                                                @elseif ($liqPendingApprover)
                                                    <img src="/storage/public/images/users/{{ $liqPendingApprover->avatar }}" class="img-circle img-size-32 mr-2">
                                                    {{ $liqPendingApprover->name }}
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                                @if (!$transaction->hierarchy_liq_approver_id && in_array($transaction->status_id, config('global.liquidation_approval')))
                                                    @if ($hasLiqApprover)
                                                        <form action="/transaction-liquidation/resend-notif/{{ $transaction->id }}" method="post" class="d-inline-block ml-2">
                                                            @csrf
                                                            <button type="submit" class="btn btn-xs rounded-pill btn-warning" onclick="return confirm('Resend approval notification?')" title="Resend Approver Notification"><i class="align-middle material-icons text-md">notifications</i></button>
                                                        </form>
                                                    @endif
                                                    @if ($perms['can_reassign_approver'])
                                                        <button type="button" class="btn btn-xs rounded-pill btn-secondary ml-1" data-toggle="modal" data-target="#modal-reassign-approver" title="Reassign Approver"><i class="align-middle material-icons text-md">swap_horiz</i></button>
                                                    @endif
                                                @endif
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
                                        <td class="font-weight-bold">{{ $transaction->cost_control_no }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-gray">Class</td>
                                        <td class="font-weight-bold">
                                            {{ $transaction->class_type_id ? $transaction->classtype->code : '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-gray">Budgeted</td>
                                        <td class="font-weight-bold">
                                            {{ $transaction->budgeted ? "Yes" : "No" }}
                                        </td>
                                    </tr>
                                    @if ($transaction->is_bills == 1 
                                        || $transaction->is_deposit == 1
                                    )
                                        <tr>
                                            <td class="font-weight-bold text-gray">Bill/Statement No.</td>
                                            <td class="font-weight-bold">{{ $transaction->bill_statement_no ?: '-' }}</td>
                                        </tr>
                                    @endif
                                    @if ($autogenerated_transaction)
                                        <tr>
                                            <td class="font-weight-bold text-gray">Payment Transaction</td>
                                            <td class="font-weight-bold">
                                                <a href="/transaction/view/{{ $autogenerated_transaction->id }}" target="_blank">{{ strtoupper($autogenerated_transaction->trans_type) }}-{{ $autogenerated_transaction->trans_year }}-{{ sprintf('%05d',$autogenerated_transaction->trans_seq) }}</a>
                                                <p class="small font-weight-bold mb-0">{{ $autogenerated_transaction->project->company->name }}</p>
                                                <span class="badge badge-pill bg-gray small">
                                                    {{ $autogenerated_transaction->status->name }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($transaction->bill_series_no)
                                        <tr>
                                            <td class="font-weight-bold text-gray">Bill Series No.</td>
                                            <td class="font-weight-bold">{{ $transaction->bill_series_no }}</td>
                                        </tr>
                                    @endif
                                    @if ($transaction->src_transaction_id)
                                        <tr>
                                            <td class="font-weight-bold text-gray">Bill Transaction</td>
                                            <td class="font-weight-bold">
                                                <a href="/transaction/view/{{ $transaction->srctransaction->id }}" target="_blank">{{ strtoupper($transaction->srctransaction->trans_type) }}-{{ $transaction->srctransaction->trans_year }}-{{ sprintf('%05d',$transaction->srctransaction->trans_seq) }}</a>
                                                <p class="small font-weight-bold mb-0">{{ $transaction->srctransaction->project->company->name }}</p>
                                                <span class="badge badge-pill bg-gray small">
                                                    {{ $transaction->srctransaction->status->name }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endif
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
                                    @if ($autogenerated_transaction)
                                        <tr>
                                            <td class="font-weight-bold text-gray">Payor Name</td>
                                            <td class="font-weight-bold">
                                                <a href="/company-project/{{ $autogenerated_transaction->project->company_id }}" target="_blank">{{ $autogenerated_transaction->project->company->name }}</a>
                                            </td>
                                        </tr>
                                    @else
                                        @if (!$transaction->src_transaction_id)
                                            <tr>
                                                <td class="font-weight-bold text-gray">Payor Name</td>
                                                <td class="font-weight-bold">{{ $transaction->is_deposit ? $transaction->payor : $transaction->project->company->name }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td class="font-weight-bold text-gray">Payee Name</td>
                                            <td class="font-weight-bold">
                                                @if ($transaction->vendor_id)
                                                    <a href="#_" data-toggle="modal" data-target="#modal-vendor">{{ $transaction->vendor->name }}</a>
                                                @else
                                                    {{ $transaction->payee ?: "-" }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
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
                                        <td>
                                            <span class="font-weight-bold text-gray">Purpose</span>
                                        </td>
                                        <td class="font-weight-bold">
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ $transaction->purpose_option_id ? ($transaction->purposeOption->code.' - '.$transaction->purposeOption->name) : '-' }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <span class="font-weight-bold text-gray">Purpose Details</span>
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
                                    <a class="btn btn-app p-2 {{ $transaction->soa ? '' : 'd-none' }}" href="/attachments/soa/{{ $transaction->soa }}" target="_blank">
                                        <i class="align-middle font-weight-bolder material-icons text-orange">folder</i>
                                        <p class="text-dark">SOA</p>
                                    </a>
                                    @if ($transaction->issue_slip)
                                        <a class="btn btn-app p-2" href="/attachments/issue_slip/{{ $transaction->issue_slip }}" target="_blank">
                                            <i class="align-middle font-weight-bolder material-icons text-orange">folder</i>
                                            <p class="text-dark">Slip</p>
                                        </a>
                                        <form method="POST" action="{{ route('transaction-form.reset-issue-slip-key', ['transaction' => $transaction->id]) }}" class="d-inline" onsubmit="return confirm('Generate a new vendor access key for this attachment? The old key will stop working.');">
                                            @csrf
                                            <button type="submit" class="btn btn-app p-2">
                                                <i class="align-middle font-weight-bolder material-icons text-orange">vpn_key</i>
                                                <p class="text-dark">Reset Key</p>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @if ($transaction->vendor_id)
                    <div class="modal fade" id="modal-vendor" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title">
                                        Vendor / Payee details
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <table class="table">
                                        <tr>
                                            <td>Name</td>
                                            <td>{{ $transaction->vendor->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Account Bank</td>
                                            <td>{{ $transaction->vendor->account_bank }}</td>
                                        </tr>
                                        <tr>
                                            <td>Account Name</td>
                                            <td>{{ $transaction->vendor->account_name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Account No.</td>
                                            <td>{{ $transaction->vendor->account_number }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
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
                                                @if ($transaction->depo_bank_branch_id)
                                                    <td class="font-weight-bold">{{ $transaction->bankbranch->bank->name }} ({{ $transaction->bankbranch->name }})</td>
                                                @else
                                                    <td class="font-weight-bold">N/A</td>
                                                @endif
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
                                        <a class="btn btn-app p-2" href="/attachments/deposit_slip/{{ $transaction->depo_slip }}" target="_blank">
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