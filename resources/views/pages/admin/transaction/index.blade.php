@extends('layouts.app')

@section('title', 'Transaction - Generate')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Generate {{ $page_label }}</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle d-none" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Select Company
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @foreach ($companies as $item)
                                <a class="dropdown-item" href="/transaction/{{ $trans_page }}/{{ $item->id }}">{{ $item->name }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            @if ($company)
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th colspan="11" class="border-0">
                                <img src="/storage/public/images/companies/{{ $company->logo }}" alt="" class="thumb thumb--xxs mr-2 vlign--baseline-middle">
                                <span class="mr-3 vlign--baseline-middle">{{ $company->name }}</span>

                                <div class="d-inline-block dropdown mx-3 vlign--baseline-middle show">
                                    <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Create</a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        @if ($trans_page == 'prpo')
                                            <a href="/transaction/create/pr/{{ $company->id }}" class="dropdown-item">Generate PR</a>
                                            <a href="/transaction/create/po/{{ $company->id }}" class="dropdown-item">Generate PO</a>
                                            <a data-toggle="modal" data-target="#modal-make-pr" href="#_" class="dropdown-item">Make Form</a>
                                            <a data-toggle="modal" data-target="#modal-liquidate-pr" href="#_" class="dropdown-item">Liquidate PR/PO</a>  
                                        @else
                                            <a href="/transaction/create/pc/{{ $company->id }}" class="dropdown-item">Generate PC</a>
                                            <a data-toggle="modal" data-target="#modal-make-pc" href="#_" class="dropdown-item">Make Form</a>
                                            <a data-toggle="modal" data-target="#modal-liquidate-pc" href="#_" class="dropdown-item">Liquidate PC</a>   
                                        @endif
                                    </div>
                                </div>

                                @if (in_array(Auth::user()->role_id, [1, 2]))
                                    <div class="d-inline-block dropdown mx-3 vlign--baseline-middle show">
                                        <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Reports</a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <a href="/transaction/report?type={{ $trans_types[0] }}&company={{ $company->id }}&status={{ config('global.generated')[0] }}" class="dropdown-item">Generates</a>
                                            <a href="/transaction-form/report?type={{ $trans_types[0] }}&company={{ $company->id }}&status={{ config('global.generated_form')[0] }}" class="dropdown-item">Forms</a>    
                                            <a href="/transaction-liquidation/report?type={{ $trans_types[0] }}&company={{ $company->id }}&status={{ config('global.liquidation_generated')[0] }}" class="dropdown-item">Liquidation</a>
                                        </div>
                                    </div>
                                @endif

                                @if ($trans_page == 'prpo')
                                    <div class="modal fade" id="modal-make-pr" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-md" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title">Select PR/PO to make</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <form action="/transaction-form/create" method="get">
                                                        <input type="text" name="key" class="form-control" placeholder="PR/PO-XXXX-XXXXX" required>
                                                        <input type="submit" class="btn btn-primary mt-2" value="Check">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="modal-liquidate-pr" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-md" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title">Select PR/PO to Liquidate</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <form action="/transaction-liquidation/create" method="get">
                                                        <input type="text" name="key" class="form-control" placeholder="PR/PO-XXXX-XXXXX" required>
                                                        <input type="submit" class="btn btn-primary mt-2" value="Check">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="modal fade" id="modal-make-pc" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-md" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title">Select PC to make</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <form action="/transaction-form/create" method="get">
                                                        <input type="text" name="key" class="form-control" placeholder="PC-XXXX-XXXXX" required>
                                                        <input type="submit" class="btn btn-primary mt-2" value="Check">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="modal-liquidate-pc" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-md" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title">Select PC to Liquidate</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <form action="/transaction-liquidation/create" method="get">
                                                        <input type="text" name="key" class="form-control" placeholder="PC-XXXX-XXXXX" required>
                                                        <input type="submit" class="btn btn-primary mt-2" value="Check">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <form action="/transaction/{{ $trans_page }}/{{ $company->id }}" method="GET" class="input-group w-25 float-right">
                                    <input type="text" class="form-control" name="s" placeholder="Transaction Code" value="{{ app('request')->input('s') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary py-0 px-2" type="submit">
                                            <i class="material-icons mt-1">search</i>
                                        </button>
                                    </div>
                                </form>
                            </th>
                        </tr>
                        <tr>
                            <th>Transaction</th>
                            <th>Payee</th>
                            <th class="text-right">Amount (PHP)</th>
                            <th>Particulars</th>
                            <th>Date Gen.</th>
                            <th>Date Req.</th>
                            <th>Req. By</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $item)
                            <tr>
                                <td>{{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}</td>
                                <td>{{ $item->payee }}</td>
                                <td class="text-right">{{ number_format($item->amount, 2, '.', ',') }}</td>
                                <td>{{ $trans_page == 'prpo' ? $item->particulars->name : $item->particulars_custom }}</td>
                                <td>{{ Carbon::parse($item->created_at)->format('Y-m-d') }}</td>
                                <td>{{ $item->created_at->toDateString() }}</td>
                                <td>{{ $item->requested->name }}</td>
                                <td>{{ $item->status->name }}</td>
                                <td>
                                    <div class="dropdown show dropleft">
                                        <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="material-icons text-lg">apps</i>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            @if (in_array($item->status_id, config('global.page_generated')))
                                                <a class="dropdown-item" href="/transaction/view/{{ $item->id }}">View</a>  
                                                <a class="dropdown-item {{ $item->can_edit ? '' : 'd-none' }}" href="/transaction/edit/{{ $item->id }}">Edit</a>  
                                                <a class="dropdown-item {{ $item->can_cancel ? '' : 'd-none' }}" data-toggle="modal" data-target="#modal-cancel-{{ $item->id }}" href="#_">Cancel</a>  
                                                <div class="dropdown-divider {{ $item->can_reset ? '' : 'd-none' }}"></div>
                                                <a class="dropdown-item {{ $item->can_reset ? '' : 'd-none' }}" href="/transaction/reset/{{ $item->id }}" onclick="return confirm('Are you sure?')">Renew Edit Limit</a>
                                            @elseif (in_array($item->status_id, config('global.page_form')))
                                                <a class="dropdown-item" href="/transaction-form/view/{{ $item->id }}">View</a>  
                                                <a class="dropdown-item {{ $item->can_edit ? '' : 'd-none' }}" href="/transaction-form/edit/{{ $item->id }}">Edit</a>  
                                                <a class="dropdown-item {{ $item->can_cancel ? '' : 'd-none' }}" data-toggle="modal" data-target="#modal-cancel-{{ $item->id }}" href="#_">Cancel</a>  
                                                <a class="dropdown-item {{ $item->can_approval ? '' : 'd-none' }}" href="/transaction-form/approval/{{ $item->id }}" onclick="return confirm('Are you sure?')">For Approval</a>
                                                <a class="dropdown-item {{ $item->can_print ? '' : 'd-none' }}" href="#_" onclick="window.open('/transaction-form/print/{{ $item->id }}','name','width=800,height=800')">Print</a>
                                                <div class="dropdown-divider {{ $item->can_reset ? '' : 'd-none' }}"></div>
                                                <a class="dropdown-item {{ $item->can_reset ? '' : 'd-none' }}" href="/transaction-form/reset/{{ $item->id }}" onclick="return confirm('Are you sure?')">Renew Edit Limit</a>
                                            @elseif (in_array($item->status_id, config('global.page_liquidation')))
                                                <a class="dropdown-item" href="/transaction-liquidation/view/{{ $item->id }}">View</a>  
                                                <a class="dropdown-item {{ $item->can_edit ? '' : 'd-none' }}" href="/transaction-liquidation/edit/{{ $item->id }}">Edit</a>  
                                                <a class="dropdown-item {{ $item->can_approval ? '' : 'd-none' }}" href="/transaction-liquidation/approval/{{ $item->id }}" onclick="return confirm('Are you sure?')">For Approval</a>
                                                <a class="dropdown-item" href="#_" onclick="window.open('/transaction-form/print/{{ $item->id }}','name','width=800,height=800')">Print Generated {{ strtoupper($item->trans_type) }} Form</a>
                                                <a class="dropdown-item {{ $item->can_print ? '' : 'd-none' }}" href="#_" onclick="window.open('/transaction-liquidation/print/{{ $item->id }}','name','width=800,height=800')">Print Liquidation</a>
                                                <div class="dropdown-divider {{ $item->can_reset ? '' : 'd-none' }}"></div>
                                                <a class="dropdown-item {{ $item->can_reset ? '' : 'd-none' }}" href="/transaction-liquidation/reset/{{ $item->id }}" onclick="return confirm('Are you sure?')">Renew Edit Limit</a>
                                            @endif
                                        </div>
                                    </div>

                                    @if (in_array($item->status_id, config('global.page_generated')))
                                        @if ($item->can_cancel)
                                            <div class="modal fade" id="modal-cancel-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-md" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header border-0">
                                                            <h5 class="modal-title">{{ __('messages.cancel_prompt') }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <form action="/transaction/cancel/{{ $item->id }}" method="post">
                                                                @csrf
                                                                @method('put')
                                                                <textarea name="cancellation_reason" class="form-control @error('cancellation_reason') is-invalid @enderror" rows="3" placeholder="Cancellation Reason" required></textarea>
                                                                <input type="submit" class="btn btn-danger mt-2" value="Cancel Now">
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @elseif (in_array($item->status_id, config('global.page_form')))
                                        @if ($item->can_cancel)
                                            <div class="modal fade" id="modal-cancel-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-md" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header border-0">
                                                            <h5 class="modal-title">{{ __('messages.cancel_prompt') }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <form action="/transaction-form/cancel/{{ $item->id }}" method="post">
                                                                @csrf
                                                                @method('put')
                                                                <textarea name="cancellation_reason" class="form-control @error('cancellation_reason') is-invalid @enderror" rows="3" placeholder="Cancellation Reason" required></textarea>
                                                                <input type="submit" class="btn btn-danger mt-2" value="Cancel Now">
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif                                    
                                        @if ($item->can_issue)
                                            <div class="modal fade" id="modal-issued-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-md" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header border-0">
                                                            <h5 class="modal-title">{{ __('messages.issue_prompt') }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="/transaction-form/issue/{{ $item->id }}" method="post">
                                                                @csrf
                                                                @method('put')
                                                                <div class="form-row mb-3">
                                                                    <div class="col-md-5">
                                                                        <label for="">Type</label>                                                                    
                                                                        @if ($trans_page == 'prpo')
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
                                                                        @if ($trans_page == 'prpo')
                                                                            <input type="text" name="control_no" class="form-control @error('control_no') is-invalid @enderror" required>
                                                                            @include('errors.inline', ['message' => $errors->first('control_no')])
                                                                        @else
                                                                            <h5>{{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}</h5>
                                                                            <input type="hidden" name="control_no" value="{{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}">
                                                                        @endif                                                
                                                                    </div>
                                                                </div>
                                                                <div class="form-row mb-3">
                                                                    <div class="col-md-5">
                                                                        <label for="">Release Date</label>
                                                                        <input type="date" class="form-control @error('released_at') is-invalid @enderror" name="released_at" required>
                                                                        @include('errors.inline', ['message' => $errors->first('released_at')])
                                                                    </div>
                                                                    <div class="col-md-7">
                                                                        <label for="">Amount</label>
                                                                        <input type="number" class="form-control @error('amount_issued') is-invalid @enderror" name="amount_issued" step="0.01" value="{{ $item->amount }}" required>
                                                                        @include('errors.inline', ['message' => $errors->first('amount_issued')])
                                                                    </div>
                                                                </div>
                                                                <div class="form-row mb-3">
                                                                    <div class="col-md-12">
                                                                        <label for="">Released By</label>
                                                                        <select name="released_by_id" class="form-control @error('released_by_id') is-invalid @enderror" required>
                                                                            @foreach ($released_by as $item)
                                                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        @include('errors.inline', ['message' => $errors->first('released_by_id')])
                                                                    </div>
                                                                </div>
                                                                <div class="text-center mt-2">
                                                                    <input type="submit" class="btn btn-success" value="Issue Now">
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($item->can_approval)
                                            <div class="modal fade" id="modal-approval-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-md" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header border-0">
                                                            <h5 class="modal-title">{{ __('messages.approval_prompt') }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <form action="/transaction-form/approval/{{ $item->id }}" method="post">
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
                                    @elseif (in_array($item->status_id, config('global.page_liquidation')))
                                        @if ($item->can_approval)
                                            <div class="modal fade" id="modal-approval-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-md" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header border-0">
                                                            <h5 class="modal-title">{{ __('messages.approval_prompt') }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <form action="/transaction-liquidation/approval/{{ $item->id }}" method="post">
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
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">{{ __('messages.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="text-center">
                    <div class="d-inline-block">
                        {{ $transactions->links() }}
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection