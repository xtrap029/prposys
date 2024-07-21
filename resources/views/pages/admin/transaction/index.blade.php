@extends('layouts.app')

@section('title', 'Transaction - Generate')

@section('content')
    <?php $ua = (new \App\Helpers\UAHelper)->get(); ?>
    <?php $non = config('global.ua_none'); ?>

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
                        @foreach ($companies->whereIn('id', explode(',', Auth::user()->companies)) as $item)
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
                            <a href="/transaction/create/pr/{{ $company->id }}" class="vlign--baseline-middle btn btn-success btn-block {{ $ua['trans_add'] == $non ? 'disabled' : '' }}"><span class="font-weight-bold">+</span> PR</a>
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
                            <a href="/transaction/create/po/{{ $company->id }}" class="vlign--baseline-middle btn btn-success btn-block {{ $ua['trans_add'] == $non ? 'disabled' : '' }}"><span class="font-weight-bold">+</span> PO</a>
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
                        <select name="user_req" class="form-control chosen-select filterSearch_select">
                            <option value="">Requested By</option>
                            @foreach ($users as $item)
                                <option value="{{ $item->id }}" {{ app('request')->input('user_req') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                            <optgroup label="Inactive" class="bg-gray-light">
                                @foreach ($users_inactive as $item)
                                    <option value="{{ $item->id }}" {{ app('request')->input('user_req') == $item->id ? 'selected' : '' }} class="bg-gray-light">{{ $item->name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    <div class="mb-2 col-md-4 col-xl-2">
                        <select name="user_prep" class="form-control chosen-select filterSearch_select">
                            <option value="">Prepared By</option>
                            @foreach ($users as $item)
                                <option value="{{ $item->id }}" {{ app('request')->input('user_prep') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                            <optgroup label="Inactive" class="bg-gray-light">
                                @foreach ($users_inactive as $item)
                                    <option value="{{ $item->id }}" {{ app('request')->input('user_prep') == $item->id ? 'selected' : '' }} class="bg-gray-light">{{ $item->name }}</option>
                                @endforeach
                            </optgroup>
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
                    <div class="mb-2 col-md-8 col-xl-6">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Due From/To</span>
                            </div>
                            <input type="date" name="due_from" class="form-control filterSearch_select" value="{{ app('request')->input('due_from') }}">
                            <input type="date" name="due_to" class="form-control filterSearch_select" value="{{ app('request')->input('due_to') }}">
                        </div>
                    </div>
                    <div class="mb-2 col-md-2">
                        <select name="project" class="form-control chosen-select filterSearch_select">
                            <option value="">All Projects</option>
                            @foreach ($projects as $item)
                                <option value="{{ $item->id }}" {{ app('request')->input('project') == $item->id ? 'selected' : '' }} class="bg-gray-light">{{ $item->project }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2 col-md-2">
                        <select name="is_confidential" class="form-control filterSearch_select">
                            <option value="">All Visible</option>
                            <option value="0" {{ app('request')->input('is_confidential') == '0' ? 'selected' : '' }}>Not Confidential</option>
                            <option value="1" {{ app('request')->input('is_confidential') == '1' ? 'selected' : '' }}>Confidential</option>
                        </select>
                    </div>
                    <div class="mb-2 col-md-2">
                        <input type="number" name="amount" class="form-control filterSearch_input" step="any" placeholder="Search Amount" value="{{ app('request')->input('amount') }}">
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
                    <table class="table table-striped table-sticky-first">
                        <thead>
                            <tr>
                                <th class="text-nowrap bg-light">{{ $trans_page == 'prpo' ? 'PR/PO' : 'PC' }} #</th>
                                <th class="text-nowrap">Vendor/Payee</th>
                                <th class="text-nowrap">Purpose</th>
                                <th class="text-nowrap">Cost Control</th>
                                <th class="text-nowrap text-center">Currency</th>
                                <th class="text-nowrap text-right">Amount</th>
                                <th class="text-nowrap">Date Gen.</th>
                                <th class="text-nowrap">Date Due</th>
                                <th class="text-nowrap">Trans. #</th>
                                <th class="text-nowrap">Date Rel.</th>
                                <th class="text-nowrap">Req. By</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $item)
                                <?php 
                                    $config_confidential = false;
                                    // if req by
                                    if (Auth::user()->id != $item->requested_id) {
                                        // check levels
                                        // if (Auth::user()->ualevel->code < $item->owner->ualevel->code) $config_confidential = true;
                                        // check level parallel confidential
                                        // if (Auth::user()->ualevel->code == $item->owner->ualevel->code && $item->is_confidential && Auth::user()->id != $item->owner->id) $config_confidential = true;
                                        if (Auth::user()->ualevel->code <= $item->owner->ualevel->code && $item->is_confidential && Auth::user()->id != $item->owner->id && !$can_view_confidential) $config_confidential = true;
                                        // check level own confidential
                                        if ($item->is_confidential_own && Auth::user()->id != $item->owner->id) $config_confidential = true;
                                    }
                                ?>
                                @include('pages.admin.transaction.notesmulti')
                                <tr>
                                    <th class="bg-light">
                                        <div class="text-nowrap">
                                            @if ($config_confidential)
                                                {{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}
                                            @else
                                                <a href="/{{ $item->url_view }}/view/{{ $item->id }}?page={{ $transactions->currentPage() }}">
                                                    {{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}
                                                </a>
                                            @endif
                                        </div>        
                                        @if ($item->is_deposit) <span class="badge badge-pill py-1 px-2 mt-2 small bg-green">Deposit Transaction</span>
                                        @elseif ($item->is_bills) <span class="badge badge-pill py-1 px-2 mt-2 small bg-blue">Bills Payment</span>
                                        @elseif ($item->is_hr) <span class="badge badge-pill py-1 px-2 mt-2 small bg-orange">Salaries and Overhead Wages</span>
                                        @elseif ($item->is_reimbursement) <span class="badge badge-pill py-1 px-2 mt-2 small bg-pink">Reimbursement</span>
                                        @elseif ($item->is_bank) <span class="badge badge-pill py-1 px-2 mt-2 small bg-purple">Fund Transfer</span>
                                        @else <span class="badge badge-pill py-1 px-2 mt-2 small bg-yellow">Regular Transaction</span>
                                        @endif

                                        <div class="mt-2 {{ $item->notes->count() == 0 || $config_confidential ? 'd-none' : '' }}">
                                            <a href="#_" class="btn mb-2 btn-xs btn-flat btn-default col-12 col-lg-auto" data-toggle="modal" data-target="#modal-notes-{{ $item->id }}">
                                                <i class="align-middle font-weight-bolder material-icons text-md">speaker_notes</i>
                                                <span class="badge badge-danger">{{$item->notes->count()}}</span>
                                            </a>
                                        </div>
                                    </th>
                                    <td>
                                        @if ($config_confidential)
                                            -
                                        @else
                                            {{ $item->vendor_id ? $item->vendor->name : $item->payee }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($config_confidential)
                                            -
                                        @else
                                            {{ $item->purpose_option_id ? $item->purposeOption->code.' - '.$item->purposeOption->name : '-'}}
                                            <br>
                                            {{ $item->purpose }}
                                        @endif
                                    </td>
                                    {{-- <td>
                                        {{ $item->cost_type_id
                                            ? $item->project->company->qb_code
                                                .'.'
                                                .$item->project->company->qb_no.$item->cost_type->control_no
                                                .'.'
                                                .sprintf("%03d", $item->cost_seq)
                                                .'.'
                                                .config('global.cost_control_v')
                                            : '-' 
                                        }}
                                    </td> --}}
                                    <td>{{ $item->cost_control_no }}</td>
                                    <td class="text-center">
                                        @if ($config_confidential)
                                            -
                                        @else
                                            {{ $item->currency }}
                                        @endif
                                    </td>
                                    <td class="text-right text-nowrap">
                                        @if ($config_confidential)
                                            -
                                        @else
                                            {{-- {{ number_format($item->form_amount_payable ?: $item->amount, 2, '.', ',') }} --}}
                                            {{ number_format($item->amount, 2, '.', ',') }}
                                        @endif                                        
                                    </td>
                                    <td class="text-nowrap">{{ Carbon::parse($item->created_at)->format('Y-m-d') }}</td>
                                    <td class="text-nowrap">
                                        @if ($config_confidential)
                                            -
                                        @else
                                            {{ Carbon::parse($item->due_at)->format('Y-m-d') }}
                                        @endif
                                    </td>
                                    <td class="break-word">
                                        @if ($config_confidential)
                                            -
                                        @else
                                            {{ $item->control_no }}
                                        @endif
                                    </td>
                                    <td class="text-nowrap">
                                        @if ($config_confidential)
                                            -
                                        @else
                                            {{ $item->released_at ? Carbon::parse($item->released_at)->format('Y-m-d') : '' }}
                                        @endif
                                    </td>
                                    <td>{{ $item->requested->name }}</td>
                                    <td class="text-nowrap">{{ $item->status->name }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">{{ __('messages.empty') }}</td>
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

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.css" integrity="sha512-0nkKORjFgcyxv3HbE4rzFUlENUMNqic/EzDIeYCgsKa/nwqr2B91Vu/tNAu4Q0cBuG4Xe/D1f/freEci/7GDRA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript">
        $('.chosen-select').chosen();
        
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
                    || $('[name=is_confidential]').val() != ''
                    || $('[name=type]').val() != ''
                    || $('[name=category]').val() != ''
                    || $('[name=status]').val() != ''
                    || $('[name=user_req]').val() != ''
                    || $('[name=user_prep]').val() != ''
                    || $('[name=project]').val() != ''
                    || $('[name=due_from]').val() != ''
                    || $('[name=due_to]').val() != ''
                    || $('[name=amount]').val() != ''
                    || $('[name=bal]').val() != '') {

                    $(cls+'_data').hide()
                    $(cls+'_loading').show()
                    $(cls+'_result').fadeIn('slow')
                    
                    $.ajax({
                        url: '/transaction/api-search',
                        type: 'POST',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'id': '{{ Auth::user()->id }}',
                            'trans_page': '{{ $trans_page }}',
                            'trans_company': '{{ $company->id }}',
                            's': $('[name=s]').val(),
                            'is_confidential': $('[name=is_confidential]').val(),
                            'type': $('[name=type]').val(),
                            'category': $('[name=category]').val(),
                            'status': $('[name=status]').val(),
                            'user_req': $('[name=user_req]').val(),
                            'user_prep': $('[name=user_prep]').val(),
                            'project': $('[name=project]').val(),
                            'due_from': $('[name=due_from]').val(),
                            'due_to': $('[name=due_to]').val(),
                            'amount': $('[name=amount]').val(),
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
                                    + '<td class="border-0 py-1">Date Due</td>'
                                    + '<td class="border-0 py-1">Date Rel.</td>'
                                    + '<td class="border-0 py-1">Req. By</td>'
                                    + '<td class="border-0 py-1">Status</td>'
                                    + '</tr>'
                                )
                                
                                $.each(result, function(i, item) {
                                    config_confidential = item.is_confidential

                                    $(cls+'_data table').append('<tr class="bg-transparent border-0">'
                                        + '<td class="border-0 py-1"><a href="'+(config_confidential ? '#' : '/'+item.url_view+'/view/'+item.id)+'" class="text-info font-weight-bold '+(config_confidential ? 'text-white' : '')+'" target="_blank">'
                                            + item.trans_type+'-'+item.trans_year+'-'+item.trans_seq
                                        + '</a></td>'
                                        + '<td class="border-0 py-1">'+(config_confidential ? "-" : item.payee)+'</td>'
                                        + '<td class="border-0 py-1 text-right">'+(config_confidential ? "-" : item.currency)+'</td>'
                                        + '<td class="border-0 py-1 text-right">'+(config_confidential ? "-" : item.amount)+'</td>'
                                        + '<td class="border-0 py-1">'+item.created+'</td>'
                                        + '<td class="border-0 py-1">'+(config_confidential ? "-" : item.due_at)+'</td>'
                                        + '<td class="border-0 py-1">'+(config_confidential ? "-" : item.released)+'</td>'
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