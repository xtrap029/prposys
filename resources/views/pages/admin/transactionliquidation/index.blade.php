@extends('layouts.app')

@section('title', 'Transaction - Liquidation')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $page_label }} Liquidation</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle d-none" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Select Company
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @foreach ($companies as $item)
                                <a class="dropdown-item" href="/transaction-liquidation/{{ $trans_page_url }}/{{ $item->id }}">{{ $item->name }}</a>
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
                                
                                @if ($trans_page_url == 'prpo')
                                    <a data-toggle="modal" data-target="#modal-liquidate-pr" href="#_" class="mx-3 vlign--baseline-middle">Liquidate</a>
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
                                                        <input type="hidden" name="company" value="{{ $company->id }}" required>
                                                        <input type="text" name="key" class="form-control" placeholder="PR/PO-XXXX-XXXXX" required>
                                                        <input type="submit" class="btn btn-primary mt-2" value="Check">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <a data-toggle="modal" data-target="#modal-liquidate-pc" href="#_" class="mx-3 vlign--baseline-middle">Liquidate</a>
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
                                    <a href="/transaction-liquidation/report?type={{ $trans_types[0] }}&company={{ $company->id }}&status={{ config('global.liquidation_generated')[0] }}" class="mx-3 vlign--baseline-middle">Reports</a>
                                    <a href="/transaction-liquidation/report-deposit?type={{ $trans_types[0] }}&company={{ $company->id }}" class="mx-3 vlign--baseline-middle">Rep. Deposits</a>
                                @endif

                                <form action="/transaction-liquidation/{{ $trans_page_url }}/{{ $company->id }}" method="GET" class="input-group w-40 float-right">
                                    <select name="status" class="form-control">
                                        <option value="">All Status</option>
                                        <option value="requested" {{ app('request')->input('status') == 'requested' ? 'selected' : '' }}>Requested</option>
                                        <option value="prepared" {{ app('request')->input('status') == 'prepared' ? 'selected' : '' }}>Prepared</option>
                                        <option value="approval" {{ app('request')->input('status') == 'approval' ? 'selected' : '' }}>Awaiting Approval</option>
                                        <option value="cleared" {{ app('request')->input('status') == 'cleared' ? 'selected' : '' }}>Cleared</option>
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
                            <th>Project</th>
                            <th>Category/Class</th>
                            <th class="text-center">Currency</th>
                            <th class="text-right">Req. Amount</th>
                            <th class="text-right">Liq. Amount</th>
                            <th class="text-right">Balance</th>
                            <th>Req. By</th>
                            <th>Deposit #</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $item)
                            <tr>
                                <td>{{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}</td>
                                <td>{{ $item->project->project }}</td>
                                <td>{{ $item->coatagging->name }}</td>
                                <td class="text-center">{{ $item->currency }}</td>
                                <td class="text-right">{{ number_format($item->amount_issued, 2, '.', ',') }}</td>
                                <td class="text-right">{{ number_format($item->liquidation->sum('amount'), 2, '.', ',') }}</td>
                                <td class="text-right">{{ number_format($item->liquidation->sum('amount') - $item->amount_issued, 2, '.', ',') }}</td>
                                <td>{{ $item->requested->name }}</td>
                                <td>{{ $item->depo_ref ?: '-'  }}</td>
                                <td>{{ $item->status->name }}</td>
                                <td>
                                    <div class="dropdown show dropleft">
                                        <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="material-icons text-lg">apps</i>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <a class="dropdown-item" href="/transaction-liquidation/view/{{ $item->id }}">View</a>  
                                            <a class="dropdown-item {{ $item->can_edit ? '' : 'd-none' }}" href="/transaction-liquidation/edit/{{ $item->id }}">Edit</a>  
                                            {{-- <a class="dropdown-item {{ $item->can_approval ? '' : 'd-none' }}" data-toggle="modal" data-target="#modal-approval-{{ $item->id }}" href="#_">For Approval</a>   --}}
                                            <a class="dropdown-item {{ $item->can_approval ? '' : 'd-none' }}" href="/transaction-liquidation/approval/{{ $item->id }}" onclick="return confirm('Are you sure?')">For Approval</a>
                                            <a class="dropdown-item" href="#_" onclick="window.open('/transaction-form/print/{{ $item->id }}','name','width=800,height=800')">Print Generated {{ strtoupper($item->trans_type) }} Form</a>
                                            <a class="dropdown-item {{ $item->can_print ? '' : 'd-none' }}" href="#_" onclick="window.open('/transaction-liquidation/print/{{ $item->id }}','name','width=800,height=800')">Print Liquidation</a>
                                            <div class="dropdown-divider {{ $item->can_reset ? '' : 'd-none' }}"></div>
                                            <a class="dropdown-item {{ $item->can_reset ? '' : 'd-none' }}" href="/transaction-liquidation/reset/{{ $item->id }}" onclick="return confirm('Are you sure?')">Renew Edit Limit</a>
                                        </div>
                                    </div>
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