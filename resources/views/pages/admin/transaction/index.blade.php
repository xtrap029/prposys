@extends('layouts.app')

@section('title', 'Transaction - Generate')

@section('content')
    <section class="content-header pb-0">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Generate {{ $page_label }}</h1>
                </div>
                <form action="/transaction/edit-company" method="post" class="col-sm-6">
                    @csrf
                    @method('put')
                    <select name="company_id" class="form-control w-auto border-0 bg-transparent font-weight-bold outline-0 float-right" onchange="this.form.submit()">
                        @foreach ($companies as $item)
                            <option value="{{ $item->id }}" {{ $company->id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                    <img src="/storage/public/images/companies/{{ $company->logo }}" alt="" class="thumb thumb--xxs mt-2 mr-2 vlign--baseline-middle float-right">
                </form>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            @if ($company)
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th colspan="10" class="border-0 px-0">
                                {{-- <img src="/storage/public/images/companies/{{ $company->logo }}" alt="" class="thumb thumb--xxs mr-2 vlign--baseline-middle">
                                <span class="mr-3 vlign--baseline-middle">{{ $company->name }}</span> --}}
                                
                                <div class="row">
                                    <div class="col-md-3 pt-3">
                                        @if ($trans_page == 'prpo')
                                        <a href="/transaction/create/pr/{{ $company->id }}" class="mx-3 vlign--baseline-middle">New PR</a>
                                        <a href="/transaction/create/po/{{ $company->id }}" class="mx-3 vlign--baseline-middle">New PO</a>
                                        @else
                                            <a href="/transaction/create/pc/{{ $company->id }}" class="mx-3 vlign--baseline-middle">Generate PC</a>
                                        @endif
                                        @if (in_array(Auth::user()->role_id, [1, 2]))
                                            <a href="#" class="mx-3 vlign--baseline-middle d-none" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Reports</a>                            
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="/transaction/report?type={{ $trans_types[0] }}&company={{ $company->id }}&status={{ config('global.generated')[0] }}">Generated</a>
                                                <a class="dropdown-item" href="/transaction-form/report?type={{ $trans_types[0] }}&company={{ $company->id }}&status={{ config('global.generated_form')[0] }}">Make Forms / Issued</a>
                                                <a class="dropdown-item" href="/transaction-liquidation/report?type={{ $trans_types[0] }}&company={{ $company->id }}&status={{ config('global.liquidation_generated')[0] }}">Clearing / Liq.</a>
                                                <a class="dropdown-item" href="/transaction-liquidation/report-deposit?type={{ $trans_types[0] }}&company={{ $company->id }}">Deposits</a>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-9">
                                        <form action="/transaction/{{ $trans_page }}/{{ $company->id }}" method="GET" class="input-group mt-3">
                                            <select name="status" class="form-control">
                                                <option value="">All Status</option>
                                                @foreach (config('global.status_filter') as $item)
                                                    <option value="{{ $item[1] }}" {{ app('request')->input('status') == $item[1] ? 'selected' : '' }}>{{ $item[0] }}</option>
                                                @endforeach
                                            </select>
                                            <select name="type" class="form-control {{ $trans_page == 'prpo' ? '' : 'd-none' }}">
                                                <option value="">All Types</option>
                                                <option value="pr" {{ app('request')->input('type') == 'pr' ? 'selected' : '' }}>PR</option>
                                                <option value="po" {{ app('request')->input('type') == 'po' ? 'selected' : '' }}>PO</option>
                                            </select>
                                            <select name="user_req" class="form-control">
                                                <option value="">Requested By</option>
                                                @foreach ($users as $item)
                                                    <option value="{{ $item->id }}" {{ app('request')->input('user_req') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                            <select name="user_prep" class="form-control">
                                                <option value="">Prepared By</option>
                                                @foreach ($users as $item)
                                                    <option value="{{ $item->id }}" {{ app('request')->input('user_prep') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                            <input type="text" class="form-control" name="s" value="{{ app('request')->input('s') }}" placeholder="keyword here...">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary py-0 px-2" type="submit">
                                                    <i class="material-icons mt-1">search</i>
                                                </button>
                                                <a href="{{ parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) }}" class="btn btn-secondary py-0 px-2" type="submit">
                                                    <i class="material-icons mt-1 text-white">clear</i>
                                                </a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th>{{ $trans_page == 'prpo' ? 'PR/PO' : 'PC' }} #</th>
                            <th>Vendor/Payee</th>
                            <th>Purpose</th>
                            <th class="text-center">Currency</th>
                            <th class="text-right">Amount</th>
                            <th class="text-nowrap">Date Gen.</th>
                            <th class="text-nowrap">Trans. #</th>
                            <th class="text-nowrap">Date Rel.</th>
                            <th class="text-nowrap">Req. By</th>
                            <th>Status</th>
                            {{-- <th></th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $item)
                            <tr>
                                <td class="text-nowrap">
                                    <a href="/{{ $item->url_view }}/view/{{ $item->id }}">
                                        {{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}
                                    </a>
                                </td>
                                <td>{{ $item->payee }}</td>
                                <td>{{ $item->purpose }}</td>
                                <td class="text-center">{{ $item->currency }}</td>
                                <td class="text-right text-nowrap">{{ number_format($item->form_amount_payable ?: $item->amount, 2, '.', ',') }}</td>
                                <td class="text-nowrap">{{ Carbon::parse($item->created_at)->format('Y-m-d') }}</td>
                                <td>{{ $item->control_no }}</td>
                                <td class="text-nowrap">{{ Carbon::parse($item->released_at)->format('Y-m-d') }}</td>
                                <td class="text-nowrap">{{ $item->requested->name }}</td>
                                <td class="text-nowrap">{{ $item->status->name }}</td>
                                {{-- <td> --}}
                                    {{-- <div class="dropdown show dropleft">
                                        <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="material-icons text-lg">apps</i>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <a class="dropdown-item" href="/{{ $item->url_view }}/view/{{ $item->id }}">View</a>  
                                            <a class="dropdown-item {{ $item->can_edit ? '' : 'd-none' }}" href="/transaction/edit/{{ $item->id }}">Edit</a>  
                                            <a class="dropdown-item {{ $item->can_cancel ? '' : 'd-none' }}" data-toggle="modal" data-target="#modal-cancel-{{ $item->id }}" href="#_">Cancel</a>  
                                            <div class="dropdown-divider {{ $item->can_reset ? '' : 'd-none' }}"></div>
                                            <a class="dropdown-item {{ $item->can_reset ? '' : 'd-none' }}" href="/transaction/reset/{{ $item->id }}" onclick="return confirm('Are you sure?')">Renew Edit Limit</a>
                                        </div>
                                    </div> --}}

                                    @if ($item->can_cancel)
                                        {{-- <div class="modal fade" id="modal-cancel-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
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
                                        </div> --}}
                                    @endif
                                {{-- </td> --}}
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