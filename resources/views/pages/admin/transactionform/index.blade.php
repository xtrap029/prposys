@extends('layouts.app')

@section('title', 'Transaction - Form')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $page_label }} Form</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle d-none" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Select Company
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @foreach ($companies as $item)
                                <a class="dropdown-item" href="/transaction-form/{{ $trans_page_url }}/{{ $item->id }}">{{ $item->name }}</a>
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
                            <th colspan="10" class="border-0">
                                <img src="/storage/public/images/companies/{{ $company->logo }}" alt="" class="thumb thumb--xxs mr-2 vlign--baseline-middle">
                                <span class="mr-3 vlign--baseline-middle">{{ $company->name }}</span>
                                
                                @if ($trans_page_url == 'prpo')
                                    <a data-toggle="modal" data-target="#modal-make-pr" href="#_" class="mx-3 vlign--baseline-middle">Make Form</a>
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
                                                        <input type="hidden" name="company" value="{{ $company->id }}" required>
                                                        <input type="text" name="key" class="form-control" placeholder="PR/PO-XXXX-XXXXX" required>
                                                        <input type="submit" class="btn btn-primary mt-2" value="Check">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <a data-toggle="modal" data-target="#modal-make-pc" href="#_" class="mx-3 vlign--baseline-middle">Make Form</a>
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
                                                        <input type="hidden" name="company" value="{{ $company->id }}" required>
                                                        <input type="text" name="key" class="form-control" placeholder="PC-XXXX-XXXXX" required>
                                                        <input type="submit" class="btn btn-primary mt-2" value="Check">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if (in_array(Auth::user()->role_id, [1, 2]))
                                    <a href="/transaction-form/report?type={{ $trans_types[0] }}&company={{ $company->id }}&status={{ config('global.generated_form')[0] }}" class="mx-3 vlign--baseline-middle">Reports</a>
                                @endif

                                <form action="/transaction-form/{{ $trans_page_url }}/{{ $company->id }}" method="GET" class="input-group w-40 float-right">
                                    <select name="status" class="form-control">
                                        <option value="">All Status</option>
                                        <option value="requested" {{ app('request')->input('status') == 'requested' ? 'selected' : '' }}>Requested</option>
                                        <option value="prepared" {{ app('request')->input('status') == 'prepared' ? 'selected' : '' }}>Prepared</option>
                                        <option value="approval" {{ app('request')->input('status') == 'approval' ? 'selected' : '' }}>Awaiting Approval</option>
                                        <option value="issued" {{ app('request')->input('status') == 'issued' ? 'selected' : '' }}>Issued</option>
                                    </select>
                                    <select name="type" class="form-control {{ $trans_page_url == 'prpo' ? '' : 'd-none' }}">
                                        <option value="">All Types</option>
                                        <option value="pr" {{ app('request')->input('type') == 'pr' ? 'selected' : '' }}>PR</option>
                                        <option value="po" {{ app('request')->input('type') == 'po' ? 'selected' : '' }}>PO</option>
                                    </select>
                                    <input type="text" class="form-control" name="s" value="{{ app('request')->input('s') }}" placeholder="keyword here...">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary py-0 px-2" type="submit">
                                            <i class="material-icons mt-1">search</i>
                                        </button>
                                    </div>
                                </form>
                            </th>
                        </tr>
                        <tr>
                            <th>{{ $trans_page_url == 'prpo' ? 'PR/PO' : 'PC' }} #</th>
                            <th>Category/Class</th>
                            <th>Vendor/Payee</th>
                            <th>Particulars</th>
                            <th class="text-center">Currency</th>
                            <th class="text-right">Amount</th>
                            <th>Check/Online #</th>
                            <th>Prep. By</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $item)
                            <tr>
                                <td>{{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}</td>
                                <td>{{ $item->coatagging->name }}</td>
                                <td>{{ $item->payee }}</td>
                                <td>{{ $item->particulars->name }}</td>
                                <td class="text-center">{{ $item->currency }}</td>
                                <td class="text-right">{{ number_format($item->amount, 2, '.', ',') }}</td>
                                <td>{{ $item->control_no ? $item->control_no : '-' }}</td>
                                <td>{{ $item->owner->name }}</td>
                                <td>{{ $item->status->name }}</td>
                                <td>
                                    <div class="dropdown show dropleft">
                                        <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="material-icons text-lg">apps</i>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <a class="dropdown-item" href="/transaction-form/view/{{ $item->id }}">View</a>  
                                            <a class="dropdown-item {{ $item->can_edit ? '' : 'd-none' }}" href="/transaction-form/edit/{{ $item->id }}">Edit</a>  
                                            <a class="dropdown-item {{ $item->can_cancel ? '' : 'd-none' }}" data-toggle="modal" data-target="#modal-cancel-{{ $item->id }}" href="#_">Cancel</a>  
                                            <a class="dropdown-item {{ $item->can_approval ? '' : 'd-none' }}" href="/transaction-form/approval/{{ $item->id }}" onclick="return confirm('Are you sure?')">For Approval</a>
                                            {{-- <a class="dropdown-item {{ $item->can_issue ? '' : 'd-none' }}" data-toggle="modal" data-target="#modal-issued-{{ $item->id }}" href="#_">Issue</a> --}}
                                            <a class="dropdown-item {{ $item->can_print ? '' : 'd-none' }}" href="#_" onclick="window.open('/transaction-form/print/{{ $item->id }}','name','width=800,height=800')">Print</a>
                                            <div class="dropdown-divider {{ $item->can_reset ? '' : 'd-none' }}"></div>
                                            <a class="dropdown-item {{ $item->can_reset ? '' : 'd-none' }}" href="/transaction-form/reset/{{ $item->id }}" onclick="return confirm('Are you sure?')">Renew Edit Limit</a>
                                        </div>
                                    </div>

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
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">{{ __('messages.empty') }}</td>
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