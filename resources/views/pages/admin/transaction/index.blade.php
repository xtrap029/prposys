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
                            <th colspan="9" class="border-0">
                                <img src="/storage/public/images/companies/{{ $company->logo }}" alt="" class="thumb thumb--xxs mr-2 vlign--baseline-middle">
                                <span class="mr-3 vlign--baseline-middle">{{ $company->name }}</span>
                                
                                @if ($trans_page == 'prpo')
                                    <a href="/transaction/create/pr/{{ $company->id }}" class="mx-3 vlign--baseline-middle">Generate PR</a>
                                    <a href="/transaction/create/po/{{ $company->id }}" class="mx-3 vlign--baseline-middle">Generate PO</a>
                                @else
                                    <a href="/transaction/create/pc/{{ $company->id }}" class="mx-3 vlign--baseline-middle">Generate PC</a>
                                @endif
                                @if (in_array(Auth::user()->role_id, [1, 2]))
                                    <a href="/transaction/report?type={{ $trans_types[0] }}&company={{ $company->id }}&status={{ config('global.generated')[0] }}" class="mx-3 vlign--baseline-middle">Reports</a>
                                @endif

                                <form action="/transaction/{{ $trans_page }}/{{ $company->id }}" method="GET" class="input-group w-40 float-right">
                                    <select name="status" class="form-control">
                                        <option value="">All Status</option>
                                        <option value="requested" {{ app('request')->input('status') == 'requested' ? 'selected' : '' }}>Requested</option>
                                        <option value="prepared" {{ app('request')->input('status') == 'prepared' ? 'selected' : '' }}>Prepared</option>
                                        <option value="approval" {{ app('request')->input('status') == 'approval' ? 'selected' : '' }}>Awaiting Approval</option>
                                    </select>
                                    <select name="type" class="form-control {{ $trans_page == 'prpo' ? '' : 'd-none' }}">
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
                            <th>{{ $trans_page == 'prpo' ? 'PR/PO' : 'PC' }} #</th>
                            <th>Vendor/Payee</th>
                            <th class="text-center">Currency</th>
                            <th class="text-right">Amount</th>
                            <th>Purpose</th>
                            <th>Date Gen.</th>
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
                                <td class="text-center">{{ $item->currency }}</td>
                                <td class="text-right">{{ number_format($item->amount, 2, '.', ',') }}</td>
                                <td>{{ $item->purpose }}</td>
                                <td>{{ Carbon::parse($item->created_at)->format('Y-m-d') }}</td>
                                <td>{{ $item->requested->name }}</td>
                                <td>{{ $item->status->name }}</td>
                                <td>
                                    <div class="dropdown show dropleft">
                                        <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="material-icons text-lg">apps</i>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <a class="dropdown-item" href="/transaction/view/{{ $item->id }}">View</a>  
                                            <a class="dropdown-item {{ $item->can_edit ? '' : 'd-none' }}" href="/transaction/edit/{{ $item->id }}">Edit</a>  
                                            <a class="dropdown-item {{ $item->can_cancel ? '' : 'd-none' }}" data-toggle="modal" data-target="#modal-cancel-{{ $item->id }}" href="#_">Cancel</a>  
                                            {{-- <a class="dropdown-item {{ $item->can_issue ? '' : 'd-none' }}" data-toggle="modal" data-target="#modal-issued-{{ $item->id }}" href="#_">Issued</a> --}}
                                            <div class="dropdown-divider {{ $item->can_reset ? '' : 'd-none' }}"></div>
                                            <a class="dropdown-item {{ $item->can_reset ? '' : 'd-none' }}" href="/transaction/reset/{{ $item->id }}" onclick="return confirm('Are you sure?')">Renew Edit Limit</a>
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
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">{{ __('messages.empty') }}</td>
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