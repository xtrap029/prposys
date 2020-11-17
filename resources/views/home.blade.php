@extends('layouts.app')

@section('content')
<section class="content-header pb-0">
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card card-widget widget-user">
                    <div class="widget-user-header bg--tecc">
                        <h3 class="widget-user-username">{{ $user->name }}</h3>
                        <h5 class="widget-user-desc">{{ $user->role->name }}</h5>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle elevation-2" src="/storage/public/images/users/{{ $user->avatar }}" alt="User Avatar">
                    </div>
                    <div class="card-footer bg-white">
                        <div class="row">
                            <div class="col-sm-6 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">Email</h5>
                                    <span>{{ $user->email }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="description-block">
                                    <h5 class="description-header">Joined</h5>
                                    <span>{{ Carbon::parse($user->created_at)->diffInDays(Carbon::now()) >= 1 ? $user->created_at->format('Y-m-d') : $user->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="col-12 py-3">
                                <form action="/my-account" method="post">
                                    @csrf
                                    @method('put')
                                    <select name="company_id" class="form-control" onchange="this.form.submit()">
                                        @foreach ($companies as $item)
                                            <option value="{{ $item->id }}" {{ $user->company_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 d-flex">
                {{-- <div class="card jsInspire w-100">
                    <div class="card-body h-100 d-table">
                        <div class="text-white d-table-cell vlign--middle text-center">
                            <h5 class="jsInspire_quote"></h5>
                            <h6 class="jsInspire_author"></h6>
                        </div>
                    </div>
                </div> --}}
                <div class="card w-100">
                    <div class="card-body rounded h-100 d-table" style="
                        background-image:url('{{ config('global.site_banner') }}');
                        background-size:cover;
                        background-position:center;
                        margin-right: -1px;
                        min-height: 200px;">
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="info-box p-3">
                    <span class="info-box-icon"><i class="material-icons text-olive" style="font-size:45px">payment</i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Balance Amount</span>
                        <span class="info-box-number">
                            P {{ number_format($liquidated_bal['balance'], 2, '.', ',') }}
                            <span class="float-right {{ $liquidated_bal['percentage_amount'] >= 100 ? 'text-danger' : 'text-primary' }}">{{ number_format($liquidated_bal['percentage_amount'], 1) }}%</span>
                        </span>
                        <div class="progress">
                            <div class="progress-bar {{ $liquidated_bal['percentage_amount'] >= 100 ? 'progress-bar--danger' : '' }}" style="width: {{ $liquidated_bal['percentage_amount'] }}%"></div>
                        </div>
                        <span>
                            with P{{ number_format($liquidated_bal['liq_amount_sum'], 2, '.', ',') }} liquidated
                            versus P{{ number_format($liquidated_bal['issued_amount_sum'], 2, '.', ',') }} issued
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-box p-3">
                    <span class="info-box-icon"><i class="material-icons text-olive" style="font-size:45px">payments</i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Unliquidated Amount</span>
                        <span class="info-box-number">
                            P {{ number_format($unliquidated_bal['used_amount'], 2, '.', ',') }}
                            <span class="float-right {{ $unliquidated_bal['percentage_amount'] >= 100 ? 'text-danger' : 'text-primary' }}">{{ number_format($unliquidated_bal['percentage_amount'], 1) }}%</span>
                        </span>
                        <div class="progress">
                            <div class="progress-bar {{ $unliquidated_bal['percentage_amount'] >= 100 ? 'progress-bar--danger' : '' }}" style="width: {{ $unliquidated_bal['percentage_amount'] }}%"></div>
                        </div>
                        <span>
                            with P{{ number_format($unliquidated_bal['limit_amount'], 2, '.', ',') }} limit
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="my-3">
                    <div class="description-block description-block--status">
                        <h5 class="description-header text-danger">{{ $stats['cancelled'] }}</h5>
                        <span class="description-text">Cancelled</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="my-3">
                    <div class="description-block description-block--status">
                        <h5 class="description-header text-orange">{{ $stats['generated'] }}</h5>
                        <span class="description-text">Generated</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="my-3">
                    <div class="description-block description-block--status">
                        <h5 class="description-header text-primary">{{ $stats['issued'] }}</h5>
                        <span class="description-text">Issued / For Liq.</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="my-3">
                    <div class="description-block description-block--status">
                        <h5 class="description-header text-success">{{ $stats['cleared'] }}</h5>
                        <span class="description-text">Cleared</span>
                    </div>
                </div>
            </div>

            @if (in_array($user->role_id, config('global.admin_subadmin')))
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header pb-2">
                            <h3 class="card-title">Form Approval / For Issue</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool pr-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons text-primary">list</i>
                                </button>                            
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="transaction-form/prpo/{{ $user->company_id }}?status=approval&type=pr&s=">Payment Release</a>
                                    <a class="dropdown-item" href="transaction-form/prpo/{{ $user->company_id }}?status=approval&type=po&s=">Purchase Order</a>
                                    {{-- <a class="dropdown-item" href="transaction-form/pc/{{ $user->company_id }}?status=approval&type=&s=">Petty Cash</a> --}}
                                </div>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="material-icons text-primary">arrow_drop_up</i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped small m-0">
                                <thead>
                                    <tr>
                                        <th>Transaction</th>
                                        <th class="text-right">Amount</th>
                                        <th class="text-center">Last Update</th>
                                    </tr>
                                </thead>
                                @foreach ($for_issue as $item)
                                    <tr>
                                        <td>
                                            <a href="transaction-form/view/{{ $item->id }}">
                                                {{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}
                                            </a>
                                        </td>
                                        <td class="text-right">{{ number_format($item->amount, 2, '.', ',') }}</td>
                                        <td class="text-center">{{ Carbon::parse($item->updated_at)->diffInDays(Carbon::now()) >= 1 ? $item->updated_at->format('Y-m-d') : $item->updated_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header pb-2">
                            <h3 class="card-title">Form Clearing</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool pr-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons text-primary">list</i>
                                </button>                            
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="transaction-liquidation/prpo/{{ $user->company_id }}?status=approval&type=pr&s=">Payment Release</a>
                                    <a class="dropdown-item" href="transaction-liquidation/prpo/{{ $user->company_id }}?status=approval&type=po&s=">Purchase Order</a>
                                    {{-- <a class="dropdown-item" href="transaction-liquidation/pc/{{ $user->company_id }}?status=approval&type=&s=">Petty Cash</a> --}}
                                </div>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="material-icons text-primary">arrow_drop_up</i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped small m-0">
                                <thead>
                                    <tr>
                                        <th>Transaction</th>
                                        <th class="text-right">Amount</th>
                                        <th class="text-center">Last Update</th>
                                    </tr>
                                </thead>
                                @foreach ($for_clearing as $item)
                                    <tr>
                                        <td>
                                            <a href="transaction-liquidation/view/{{ $item->id }}">
                                                {{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}
                                            </a>
                                        </td>
                                        <td class="text-right">{{ number_format($item->amount, 2, '.', ',') }}</td>
                                        <td class="text-center">{{ Carbon::parse($item->updated_at)->diffInDays(Carbon::now()) >= 1 ? $item->updated_at->format('Y-m-d') : $item->updated_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header pb-2">
                        <h3 class="card-title">Generated</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool pr-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons text-primary">list</i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="transaction/prpo/{{ $user->company_id }}?status=&type=pr&s=">Payment Release</a>
                                <a class="dropdown-item" href="transaction/prpo/{{ $user->company_id }}?status=&type=po&s=">Purchase Order</a>
                                {{-- <a class="dropdown-item" href="transaction-form/pc/{{ $user->company_id }}?status=issued&type=&s=">Petty Cash</a> --}}
                            </div>
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="material-icons text-primary">arrow_drop_up</i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped small m-0">
                            <thead>
                                <tr>
                                    <th>Transaction</th>
                                    <th class="text-right">Amount</th>
                                    <th class="text-center">Last Update</th>
                                </tr>
                            </thead>
                            @foreach ($generated as $item)
                                <tr>
                                    <td>
                                        <a href="transaction/view/{{ $item->id }}">
                                            {{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}
                                        </a>
                                    </td>
                                    <td class="text-right">{{ number_format($item->amount, 2, '.', ',') }}</td>
                                    <td class="text-center">{{ Carbon::parse($item->updated_at)->diffInDays(Carbon::now()) >= 1 ? $item->updated_at->format('Y-m-d') : $item->updated_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header pb-2">
                        <h3 class="card-title">Unliquidated</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool pr-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons text-primary">list</i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="transaction-form/prpo/{{ $user->company_id }}?status=issued&type=pr&s=">Payment Release</a>
                                <a class="dropdown-item" href="transaction-form/prpo/{{ $user->company_id }}?status=issued&type=po&s=">Purchase Order</a>
                                {{-- <a class="dropdown-item" href="transaction-form/pc/{{ $user->company_id }}?status=issued&type=&s=">Petty Cash</a> --}}
                            </div>
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="material-icons text-primary">arrow_drop_up</i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped small m-0">
                            <thead>
                                <tr>
                                    <th>Transaction</th>
                                    <th class="text-right">Amount</th>
                                    <th class="text-center">Last Update</th>
                                </tr>
                            </thead>
                            @foreach ($unliquidated as $item)
                                <tr>
                                    <td>
                                        <a href="transaction-form/view/{{ $item->id }}">
                                            {{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}
                                        </a>
                                    </td>
                                    <td class="text-right">{{ number_format($item->amount, 2, '.', ',') }}</td>
                                    <td class="text-center">{{ Carbon::parse($item->updated_at)->diffInDays(Carbon::now()) >= 1 ? $item->updated_at->format('Y-m-d') : $item->updated_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header pb-2">
                        <h3 class="card-title">Cleared</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool pr-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons text-primary">list</i>
                            </button>                            
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="transaction-liquidation/prpo/{{ $user->company_id }}?status=cleared&type=pr&s=">Payment Release</a>
                                <a class="dropdown-item" href="transaction-liquidation/prpo/{{ $user->company_id }}?status=cleared&type=po&s=">Purchase Order</a>
                                {{-- <a class="dropdown-item" href="transaction-liquidation/pc/{{ $user->company_id }}?status=cleared&type=&s=">Petty Cash</a> --}}
                            </div>
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="material-icons text-primary">arrow_drop_up</i>
                            </button>
                        </div>  
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped small m-0">
                            <thead>
                                <tr>
                                    <th>Transaction</th>
                                    <th class="text-right">Balance</th>
                                    <th class="text-center">Last Update</th>
                                </tr>
                            </thead>
                            @foreach ($cleared as $item)
                                <tr>
                                    <td>
                                        <a href="transaction-liquidation/view/{{ $item->id }}">
                                            {{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}
                                        </a>
                                    </td>
                                    <td class="text-right">{{ number_format($item->liquidation->sum('amount') - $item->amount_issued, 2, '.', ',') }}</td>
                                    <td class="text-center">{{ Carbon::parse($item->updated_at)->diffInDays(Carbon::now()) >= 1 ? $item->updated_at->format('Y-m-d') : $item->updated_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="row">
                    {{-- <div class="col-4 col-md-2 my-3 border-right">
                        <div class="description-block">
                            <h5 class="description-header text-danger">{{ $stats['cancelled'] }}</h5>
                            <span class="description-text">Cancelled</span>
                        </div>
                    </div>
                    <div class="col-4 col-md-2 my-3 border-right">
                        <div class="description-block">
                            <h5 class="description-header text-orange">{{ $stats['generated'] }}</h5>
                            <span class="description-text">Generated</span>
                        </div>
                    </div>
                    <div class="col-4 col-md-2 my-3 border-right">
                        <div class="description-block">
                            <h5 class="description-header text-orange">{{ $stats['forms'] }}</h5>
                            <span class="description-text">Forms</span>
                        </div>
                    </div>
                    <div class="col-4 col-md-2 my-3 border-right">
                        <div class="description-block">
                            <h5 class="description-header text-primary">{{ $stats['issued'] }}</h5>
                            <span class="description-text">Issued</span>
                        </div>
                    </div>
                    <div class="col-4 col-md-2 my-3 border-right">
                        <div class="description-block">
                            <h5 class="description-header text-primary">{{ $stats['liquidation'] }}</h5>
                            <span class="description-text">Liquidations</span>
                        </div>
                    </div>
                    <div class="col-4 col-md-2 my-3">
                        <div class="description-block">
                            <h5 class="description-header text-primary">{{ $stats['cleared'] }}</h5>
                            <span class="description-text">Cleared</span>
                        </div>
                    </div> --}}
                    {{-- <div class="col-md-6">
                        <div class="card">
                            <div class="card-header pb-2">
                                <h3 class="card-title">Prepared</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="material-icons text-primary">arrow_drop_up</i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped small m-0">
                                    <thead>
                                        <tr>
                                            <th>Transaction</th>
                                            <th>Status</th>
                                            <th>Last Update</th>
                                        </tr>
                                    </thead>
                                    @foreach ($prepared as $item)
                                        <tr>
                                            <td>{{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}</td>
                                            <td>{{ $item->status->name }}</td>
                                            <td>{{ Carbon::parse($item->updated_at)->diffInDays(Carbon::now()) >= 1 ? $item->updated_at->format('Y-m-d') : $item->updated_at->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div> --}}
                    {{-- <div class="col-md-6">
                        <div class="card">
                            <div class="card-header pb-2">
                                <h3 class="card-title">Activities</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="material-icons text-primary">arrow_drop_up</i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-reponsive table-striped small m-0">
                                    <tbody>
                                        @foreach ($logs as $item)
                                            <tr>
                                                <td>
                                                    <a href="#_" data-toggle="modal" data-target="#modal-{{ $item->id }}">{{ strtoupper($item->description) }}</a>
                                                    <div class="modal fade" id="modal-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header border-0">
                                                                    <h5 class="modal-title">{{ ucfirst($item->description) }} {{ Carbon::parse($item->created_at)->diffInDays(Carbon::now()) >= 1 ? $item->updated_at->format('Y-m-d') : $item->updated_at->diffForHumans() }}</h5>
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
                                                                <div class="modal-footer border-0">
                                                                    @if (in_array($item->description, ['created', 'updated']))
                                                                    @endif
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $item->log_name }}</td>
                                                <td class="text-right">{{ Carbon::parse($item->created_at)->diffInDays(Carbon::now()) >= 1 ? $item->updated_at->format('Y-m-d') : $item->updated_at->diffForHumans() }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
    <script type="text/javascript">
        // $(function() {
        //     $.ajax({
        //         url: "https://quotes.rest/qod?language=en",
        //         context: document.body
        //     }).done(function(response) {
        //         quote = response.contents.quotes[0]
        //         item = '.jsInspire'

        //         $(item).css({
        //             "background-image":"url("+quote.background+")",
        //             "background-size":"cover",
        //             "background-position":"center",
        //         })

        //         $(item+'_quote').text(quote.quote)
        //         $(item+'_author').text(quote.author)
        //     })
        // })
    </script>
@endsection