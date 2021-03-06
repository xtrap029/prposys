@extends('layouts.app')

@section('title', 'Transaction - Generate')

@section('content')
    <section class="content-header pb-0">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h1>Generate {{ $page_label }}</h1>
                </div>
                <form action="/transaction/edit-company" method="post" class="col-md-6 mt-3 mt-md-0">
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
                <form action="/transaction/{{ $trans_page }}/{{ $company->id }}" method="GET" class="mt-5 row">
                    <div class="mb-2 col-6 col-md-2 col-xl-1">
                        @if ($trans_page == 'prpo')
                            <a href="/transaction/create/pr/{{ $company->id }}" class="vlign--baseline-middle btn btn-success btn-block"><span class="font-weight-bold">+</span> PR</a>
                        @else
                            <a href="/transaction/create/pc/{{ $company->id }}" class="vlign--baseline-middle d-none">Generate PC</a>
                        @endif
                        @if (in_array(Auth::user()->role_id, [1, 2]))
                            <a href="#" class="vlign--baseline-middle d-none" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Reports</a>                            
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="/transaction/report?type={{ $trans_types[0] }}&company={{ $company->id }}&status={{ config('global.generated')[0] }}">Generated</a>
                                <a class="dropdown-item" href="/transaction-form/report?type={{ $trans_types[0] }}&company={{ $company->id }}&status={{ config('global.generated_form')[0] }}">Make Forms / Issued</a>
                                <a class="dropdown-item" href="/transaction-liquidation/report?type={{ $trans_types[0] }}&company={{ $company->id }}&status={{ config('global.liquidation_generated')[0] }}">Clearing / Liq.</a>
                                <a class="dropdown-item" href="/transaction-liquidation/report-deposit?type={{ $trans_types[0] }}&company={{ $company->id }}">Deposits</a>
                            </div>
                        @endif
                    </div>
                    <div class="mb-2 col-6 col-md-2 col-xl-1">
                        @if ($trans_page == 'prpo')
                            <a href="/transaction/create/po/{{ $company->id }}" class="vlign--baseline-middle btn btn-success btn-block"><span class="font-weight-bold">+</span> PO</a>
                        @endif
                    </div>
                    
                    <div class="mb-2 col-md-4 col-xl-2">
                        <select name="status" class="form-control filterSearch_select">
                            <option value="">All Status</option>
                            @foreach (config('global.status_filter') as $item)
                                <option value="{{ $item[1] }}" {{ app('request')->input('status') == $item[1] ? 'selected' : '' }}>{{ $item[0] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2 col-md-4 col-xl-2">
                        <select name="category" class="form-control filterSearch_select">
                            <option value="">All Categories</option>
                            @foreach (config('global.trans_category_column_2') as $key => $item)
                                <option value="{{ $item }}" {{ app('request')->input('category') == $item ? 'selected' : '' }}>{{ config('global.trans_category_label')[$key] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2 col-md-4 col-xl-2">
                        <select name="type" class="form-control filterSearch_select {{ $trans_page == 'prpo' ? '' : 'd-none' }}">
                            <option value="">All Types</option>
                            <option value="pr" {{ app('request')->input('type') == 'pr' ? 'selected' : '' }}>PR</option>
                            <option value="po" {{ app('request')->input('type') == 'po' ? 'selected' : '' }}>PO</option>
                        </select>
                    </div>
                    <div class="mb-2 col-md-4 col-xl-2">
                        <select name="user_req" class="form-control filterSearch_select">
                            <option value="">Requested By</option>
                            @foreach ($users as $item)
                                <option value="{{ $item->id }}" {{ app('request')->input('user_req') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2 col-md-4 col-xl-2">
                        <select name="user_prep" class="form-control filterSearch_select">
                            <option value="">Prepared By</option>
                            @foreach ($users as $item)
                                <option value="{{ $item->id }}" {{ app('request')->input('user_prep') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2 col-md-4 col-xl-2">
                        <select name="bal" class="form-control filterSearch_select">
                            <option value="">All Amount</option>
                            <option value="0" {{ app('request')->input('bal') != "" && app('request')->input('bal') == 0 ? 'selected' : '' }}>No Return</option>
                            <option value="1" {{ app('request')->input('bal') == '1' ? 'selected' : '' }}>( + ) For Reimbursement</option>
                            <option value="-1" {{ app('request')->input('bal') == '-1' ? 'selected' : '' }}>( - ) Return Money</option>
                        </select>
                    </div>
                    <div class="mb-2 col-md-4 col-xl-8">
                        <input type="text" class="form-control filterSearch_input" name="s" value="{{ app('request')->input('s') }}" autocomplete="off" placeholder="keyword here...">
                        <div class="d-none d-md-block position-relative">
                            <div class="card card-search bg-secondary font-weight-normal filterSearch_result" style="display: none">
                                <div class="card-body">
                                    <button type="button" class="btn btn-tool float-right filterSearch_close">close</button>
                                    <div class="filterSearch_data row small w-100" style="display: none">
                                        <table class="table mb-0 p-0">
                                        </table>
                                    </div>
                                    <img src="/images/loading.gif" class="filterSearch_loading thumb--sm m-auto" style="display: block">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2 col-6 col-md-2 col-xl-1">
                        <button class="btn btn-primary btn-block p-0" type="submit"><i class="material-icons mt-1">search</i></button>
                    </div>
                    <div class="mb-2 col-6 col-md-2 col-xl-1">
                        <a href="{{ parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) }}" class="btn btn-secondary btn-block p-0" type="submit"><i class="material-icons mt-1 text-white">clear</i></a>
                    </div>
                    
                </form>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-nowrap">{{ $trans_page == 'prpo' ? 'PR/PO' : 'PC' }} #</th>
                                <th class="text-nowrap">Vendor/Payee</th>
                                <th class="text-nowrap">Purpose</th>
                                <th class="text-nowrap text-center">Currency</th>
                                <th class="text-nowrap text-right">Amount</th>
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
                                        <a href="/{{ $item->url_view }}/view/{{ $item->id }}?page={{ $transactions->currentPage() }}">
                                            {{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}
                                        </a>
                                    </td>
                                    <td>{{ $item->payee }}</td>
                                    <td>{{ $item->purpose }}</td>
                                    <td class="text-center">{{ $item->currency }}</td>
                                    <td class="text-right text-nowrap">{{ number_format($item->form_amount_payable ?: $item->amount, 2, '.', ',') }}</td>
                                    <td class="text-nowrap">{{ Carbon::parse($item->created_at)->format('Y-m-d') }}</td>
                                    <td class="break-word">{{ $item->control_no }}</td>
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
                </div>
                <div class="overflow-auto position-relative text-center">
                    <div class="d-inline-block">
                        {{ $transactions->links() }}
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

@section('script')
    <script type="text/javascript">
        $(function() {
            if ($('.filterSearch_input')[0]){
                cls = '.filterSearch'

                $(cls+'_input').keyup(delay(function() {
                    apiSearch()
                }, 300))

                $(cls+'_select').change(delay(function() {
                    apiSearch()
                }, 300))

                $(cls+'_close').click(function() {
                    $(cls+'_result').fadeOut('fast')
                })
            }

            function apiSearch() {
                if ($(cls+'_input').val() != ''                                
                    || $('[name=s]').val() != ''
                    || $('[name=type]').val() != ''
                    || $('[name=category]').val() != ''
                    || $('[name=status]').val() != ''
                    || $('[name=user_req]').val() != ''
                    || $('[name=user_prep]').val() != ''
                    || $('[name=bal]').val() != '') {

                    $(cls+'_data').hide()
                    $(cls+'_loading').show()
                    $(cls+'_result').fadeIn('slow')
                    
                    $.ajax({
                        url: '/transaction/api-search',
                        type: 'POST',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'trans_page': '{{ $trans_page }}',
                            'trans_company': '{{ $company->id }}',
                            's': $('[name=s]').val(),
                            'type': $('[name=type]').val(),
                            'category': $('[name=category]').val(),
                            'status': $('[name=status]').val(),
                            'user_req': $('[name=user_req]').val(),
                            'user_prep': $('[name=user_prep]').val(),
                            'bal': $('[name=bal]').val(),
                        },
                        success: function(res) {
                            result = JSON.parse(res)

                            $(cls+'_loading').hide()
                            $(cls+'_data table').html('')
                            $(cls+'_data').fadeIn('slow')
                            
                            if (result.length > 0) {
                                $(cls+'_data table').append('<tr class="bg-transparent border-0">'
                                    + '<td class="border-0 py-1">PR/PO #</td>'
                                    + '<td class="border-0 py-1">Payee</td>'
                                    + '<td class="border-0 py-1 text-right">Currency</td>'
                                    + '<td class="border-0 py-1 text-right">Amount</td>'
                                    + '<td class="border-0 py-1">Date Gen.</td>'
                                    + '<td class="border-0 py-1">Date Rel.</td>'
                                    + '<td class="border-0 py-1">Req. By</td>'
                                    + '<td class="border-0 py-1">Status</td>'
                                    + '</tr>'
                                )
                                
                                $.each(result, function(i, item) {
                                    $(cls+'_data table').append('<tr class="bg-transparent border-0">'
                                        + '<td class="border-0 py-1"><a href="/'+item.url_view+'/view/'+item.id+'" class="text-info font-weight-bold" target="_blank">'
                                            + item.trans_type+'-'+item.trans_year+'-'+item.trans_seq
                                        + '</a></td>'
                                        + '<td class="border-0 py-1">'+item.payee+'</td>'
                                        + '<td class="border-0 py-1 text-right">'+item.currency+'</td>'
                                        + '<td class="border-0 py-1 text-right">'+item.amount+'</td>'
                                        + '<td class="border-0 py-1">'+item.created+'</td>'
                                        + '<td class="border-0 py-1">'+item.released+'</td>'
                                        + '<td class="border-0 py-1">'+item.requested_by+'</td>'
                                        + '<td class="border-0 py-1">'+item.status_name+'</td>'
                                        + '</tr>'
                                    )
                                })
                            } else {
                                $(cls+'_data table').html('<tr class="bg-transparent border-0"><td class="border-0">{{ __("messages.not_found") }}</td></tr>')
                            }


                        },
                        error: function() {
                            $(cls+'_data').fadeOut('fast')
                            $(cls+'_loading').fadeOut('fast')
                            $(cls+'_result').fadeOut('fast')
                        }
                    })
                } else {
                    $(cls+'_result').fadeOut('fast')
                    $(cls+'_loading').fadeOut('fast')
                }
            }

            function delay(callback, ms) {
                var timer = 0
                return function() {
                    var context = this, args = arguments;
                    clearTimeout(timer)
                    timer = setTimeout(function () {
                    callback.apply(context, args)
                    }, ms || 0)
                };
            }
        })
    </script>
@endsection