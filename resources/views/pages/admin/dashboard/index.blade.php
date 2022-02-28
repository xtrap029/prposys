@extends('layouts.app')

@section('content')

<?php $ua = (new \App\Helpers\UAHelper)->get(); ?>
<?php $non = config('global.ua_none'); ?>

<section class="content-header pb-0">
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-lg-4">
                <div class="card card-widget widget-user">
                    <div class="widget-user-header bg--tecc">
                        <h3 class="widget-user-username">{{ $user->name }}</h3>
                        <h6 class="widget-user-desc">{{ $user->role->name }} {{ $user->is_smt ? ' - SMT' : '' }}</h6>
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
                                    <h5 class="description-header">Hired</h5>
                                    <span>  
                                        @if ($user->e_hire_date)
                                            {{ Carbon::parse($user->e_hire_date)->diffInDays(Carbon::now()) >= 1 ? Carbon::parse($user->e_hire_date)->format('Y-m-d') : Carbon::parse($user->e_hire_date)->diffForHumans() }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="col-12 py-3">
                                <form action="/my-account" method="post">
                                    @csrf
                                    @method('put')
                                    <select name="company_id" class="form-control" onchange="this.form.submit()">
                                        @foreach ($companies->whereIn('id', explode(',', $user->companies)) as $item)
                                            <option value="{{ $item->id }}" {{ $user->company_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-8 d-flex">
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

            <div class="col-6 col-md-3 {{ $stats['cancelled'] > 0 ? '' : 'd-none' }}">
                <div class="my-3">
                    <div class="description-block description-block--status">
                        <h5 class="description-header text-danger">{{ $stats['cancelled'] }}</h5>
                        <span class="description-text">Cancelled</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 {{ $stats['generated'] > 0 ? '' : 'd-none' }}">
                <div class="my-3">
                    <div class="description-block description-block--status">
                        <h5 class="description-header text-orange">{{ $stats['generated'] }}</h5>
                        <span class="description-text">Generated</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 {{ $stats['issued'] > 0 ? '' : 'd-none' }}">
                <div class="my-3">
                    <div class="description-block description-block--status">
                        <h5 class="description-header text-primary">{{ $stats['issued'] }}</h5>
                        <span class="description-text">Issued / For Liq.</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 {{ $stats['cleared'] > 0 ? '' : 'd-none' }}">
                <div class="my-3">
                    <div class="description-block description-block--status">
                        <h5 class="description-header text-success">{{ $stats['cleared'] }}</h5>
                        <span class="description-text">Cleared</span>
                    </div>
                </div>
            </div>

            @if ($ua['trans_view'] != $non)  
                {{-- FOR APPROVAL --}}
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header pb-2">
                            <h3 class="card-title">For Approval</h3>
                            <div class="card-tools">
                                <a class="small" href="transaction/prpo/{{ $user->company_id }}?status=6&category=&type=&s=&user_req=&user_prep&bal&project&is_confidential&due_from&due_to">Show More</a>
                                <button type="button" class="btn btn-tool pr-0 d-none" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons text-primary">list</i>
                                </button>                            
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="transaction/prpo/{{ $user->company_id }}?status=6&type=pr&s=&user_req=&user_prep&bal&project&is_confidential&due_from&due_to">Payment Release</a>
                                    <a class="dropdown-item" href="transaction/prpo/{{ $user->company_id }}?status=6&type=po&s=&user_req=&user_prep&bal&project&is_confidential&due_from&due_to">Purchase Order</a>
                                    {{-- <a class="dropdown-item" href="transaction/pc/{{ $user->company_id }}?status=6&type=&s=&user_req=&user_prep&bal&is_confidential&due_from&due_to">Petty Cash</a> --}}
                                </div>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="material-icons text-primary">arrow_drop_up</i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            <table class="table table-striped small m-0">
                                <thead>
                                    <tr>
                                        <th>PO/PR #</th>
                                        <th>Vendor/Payee</th>
                                        <th>Purpose</th>
                                        <th class="text-right">Amount</th>
                                        <th class="text-center">Trans. #</th>
                                        <th class="text-center">Due Date</th>
                                    </tr>
                                </thead>
                                @foreach ($for_issue as $item)
                                    <?php 
                                        $config_confidential = false;
                                        if (Auth::user()->id != $item->requested_id) {
                                            // check levels
                                            if (Auth::user()->ualevel->code < $item->owner->ualevel->code) $config_confidential = true;
                                            // check level parallel confidential
                                            if (Auth::user()->ualevel->code == $item->owner->ualevel->code && $item->is_confidential && Auth::user()->id != $item->owner->id && Auth::user()->id != $item->requested_id) $config_confidential = true;
                                            // check level own confidential
                                            if ($item->is_confidential_own && Auth::user()->id != $item->owner->id && Auth::user()->id != $item->requested_id) $config_confidential = true;
                                        }
                                    ?>
                                    <tr>
                                        <td>
                                            @if ($config_confidential)
                                                {{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}
                                            @else
                                                <a href="transaction-form/view/{{ $item->id }}">
                                                    {{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ $item->payee }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ $item->purpose }}
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ number_format($item->form_amount_payable ?: $item->amount, 2, '.', ',') }}
                                            @endif
                                        </td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ $item->due_at }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>

                {{-- LIQ./FOR APPROVAL --}}
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header pb-2">
                            <h3 class="card-title">Liq./For Approval</h3>
                            <div class="card-tools">
                                <a class="small" href="transaction/prpo/{{ $user->company_id }}?status=7,8&category=&type=&s=&user_req=&user_prep&bal&project&is_confidential&due_from&due_to">Show More</a>
                                <button type="button" class="btn btn-tool pr-0 d-none" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons text-primary">list</i>
                                </button>                            
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="transaction/prpo/{{ $user->company_id }}?status=7,8&type=pr&s=&user_req=&user_prep&bal&project&is_confidential&due_from&due_to">Payment Release</a>
                                    <a class="dropdown-item" href="transaction/prpo/{{ $user->company_id }}?status=7,8&type=po&s=&user_req=&user_prep&bal&project&is_confidential&due_from&due_to">Purchase Order</a>
                                    {{-- <a class="dropdown-item" href="transaction-liquidation/pc/{{ $user->company_id }}?status=7,8&type=&s=&user_req=&user_prep&bal&is_confidential&due_from&due_to">Petty Cash</a> --}}
                                </div>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="material-icons text-primary">arrow_drop_up</i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            <table class="table table-striped small m-0">
                                <thead>
                                    <tr>
                                        <th>PO/PR #</th>
                                        <th>Vendor/Payee</th>
                                        <th>Purpose</th>
                                        <th class="text-right">Amount</th>
                                        <th>Trans. #</th>
                                        <th class="text-center">Date Released</th>
                                    </tr>
                                </thead>
                                @foreach ($for_clearing as $item)
                                    <?php 
                                        $config_confidential = false;
                                        if (Auth::user()->id != $item->requested_id) {
                                            // check levels
                                            if (Auth::user()->ualevel->code < $item->owner->ualevel->code) $config_confidential = true;
                                            // check level parallel confidential
                                            if (Auth::user()->ualevel->code == $item->owner->ualevel->code && $item->is_confidential && Auth::user()->id != $item->owner->id && Auth::user()->id != $item->requested_id) $config_confidential = true;
                                            // check level own confidential
                                            if ($item->is_confidential_own && Auth::user()->id != $item->owner->id && Auth::user()->id != $item->requested_id) $config_confidential = true;
                                        }
                                    ?>
                                    <tr>
                                        <td>
                                            @if ($config_confidential)
                                                {{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}
                                            @else
                                                <a href="transaction-liquidation/view/{{ $item->id }}">
                                                    {{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ $item->payee }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ $item->purpose }}
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ number_format($item->form_amount_payable ?: $item->amount, 2, '.', ',') }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ $item->control_no }}
                                            @endif    
                                        </td>
                                        <td class="text-center">
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ $item->released_at }}
                                            @endif    
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>

                {{-- GENERATED --}}
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header pb-2">
                            <h3 class="card-title">Generated</h3>
                            <div class="card-tools">
                                <a class="small" href="transaction/prpo/{{ $user->company_id }}?status=1,5&category=&type=&s=&user_req=&user_prep&bal&project&is_confidential&due_from&due_to">Show More</a>
                                <button type="button" class="btn btn-tool pr-0 d-none" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons text-primary">list</i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="transaction/prpo/{{ $user->company_id }}?status=1,5&type=pr&s=&user_req=&user_prep&bal&project&is_confidential&due_from&due_to">Payment Release</a>
                                    <a class="dropdown-item" href="transaction/prpo/{{ $user->company_id }}?status=1,5&type=po&s=&user_req=&user_prep&bal&project&is_confidential&due_from&due_to">Purchase Order</a>
                                    {{-- <a class="dropdown-item" href="transaction-form/pc/{{ $user->company_id }}?status=1,5issued&type=&s=&user_req=&user_prep&bal&is_confidential&due_from&due_to">Petty Cash</a> --}}
                                </div>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="material-icons text-primary">arrow_drop_up</i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            <table class="table table-striped small m-0">
                                <thead>
                                    <tr>
                                        <th>PO/PR #</th>
                                        <th>Vendor/Payee</th>
                                        <th>Purpose</th>
                                        <th class="text-right">Amount</th>
                                        <th class="text-center">Trans. #</th>
                                        <th class="text-center">Last Updated</th>
                                    </tr>
                                </thead>
                                @foreach ($generated as $item)
                                    <?php 
                                        $config_confidential = false;
                                        if (Auth::user()->id != $item->requested_id) {
                                            // check levels
                                            if (Auth::user()->ualevel->code < $item->owner->ualevel->code) $config_confidential = true;
                                            // check level parallel confidential
                                            if (Auth::user()->ualevel->code == $item->owner->ualevel->code && $item->is_confidential && Auth::user()->id != $item->owner->id && Auth::user()->id != $item->requested_id) $config_confidential = true;
                                            // check level own confidential
                                            if ($item->is_confidential_own && Auth::user()->id != $item->owner->id && Auth::user()->id != $item->requested_id) $config_confidential = true;
                                        }
                                    ?>
                                    <tr>
                                        <td>
                                            @if ($config_confidential)
                                                {{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}
                                            @else
                                                <a href="transaction/view/{{ $item->id }}">
                                                    {{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ $item->payee }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ $item->purpose }}
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ number_format($item->amount, 2, '.', ',') }}
                                            @endif
                                        </td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">{{ Carbon::parse($item->updated_at)->diffInDays(Carbon::now()) >= 1 ? $item->updated_at->format('Y-m-d') : $item->updated_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>

                {{-- UNLIQUIDATED --}}
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header pb-2">
                            <h3 class="card-title">Unliquidated</h3>
                            <div class="card-tools">
                                <a class="small" href="transaction/prpo/{{ $user->company_id }}?status=4&category=&type=&s=&user_req=&user_prep&bal&project&is_confidential&due_from&due_to">Show More</a>
                                <button type="button" class="btn btn-tool pr-0 d-none" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons text-primary">list</i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="transaction/prpo/{{ $user->company_id }}?status=4&type=pr&s=&user_req=&user_prep&bal&project&is_confidential&due_from&due_to">Payment Release</a>
                                    <a class="dropdown-item" href="transaction/prpo/{{ $user->company_id }}?status=4&type=po&s=&user_req=&user_prep&bal&project&is_confidential&due_from&due_to">Purchase Order</a>
                                    {{-- <a class="dropdown-item" href="transaction-form/pc/{{ $user->company_id }}?status=issued&type=&s=&user_req=&user_prep&bal&is_confidential&due_from&due_to">Petty Cash</a> --}}
                                </div>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="material-icons text-primary">arrow_drop_up</i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            <table class="table table-striped small m-0">
                                <thead>
                                    <tr>
                                        <th>PO/PR #</th>
                                        <th>Vendor/Payee</th>
                                        <th>Purpose</th>
                                        <th class="text-right">Amount</th>
                                        <th>Trans. #</th>
                                        <th class="text-center">Date Released</th>
                                    </tr>
                                </thead>
                                @foreach ($unliquidated as $item)
                                    <?php 
                                        $config_confidential = false;
                                        if (Auth::user()->id != $item->requested_id) {
                                            // check levels
                                            if (Auth::user()->ualevel->code < $item->owner->ualevel->code) $config_confidential = true;
                                            // check level parallel confidential
                                            if (Auth::user()->ualevel->code == $item->owner->ualevel->code && $item->is_confidential && Auth::user()->id != $item->owner->id && Auth::user()->id != $item->requested_id) $config_confidential = true;
                                            // check level own confidential
                                            if ($item->is_confidential_own && Auth::user()->id != $item->owner->id && Auth::user()->id != $item->requested_id) $config_confidential = true;
                                        }
                                    ?>
                                    <tr>
                                        <td>
                                            @if ($config_confidential)
                                                {{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}
                                            @else
                                                <a href="transaction-form/view/{{ $item->id }}">
                                                    {{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ $item->payee }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ $item->purpose }}
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ number_format($item->form_amount_payable ?: $item->amount, 2, '.', ',') }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ $item->control_no }}
                                            @endif    
                                        </td>
                                        <td class="text-center">
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ $item->released_at }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>

                {{-- CLEARED --}}
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header pb-2">
                            <h3 class="card-title">Cleared</h3>
                            <div class="card-tools">
                                <a class="small" href="transaction/prpo/{{ $user->company_id }}?status=9&category=&type=&s=&user_req=&user_prep&bal&project&is_confidential&due_from&due_to">Show More</a>
                                <button type="button" class="btn btn-tool pr-0 d-none" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons text-primary">list</i>
                                </button>                            
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="transaction/prpo/{{ $user->company_id }}?status=9&type=pr&s=&user_req=&user_prep&bal&project&is_confidential&due_from&due_to">Payment Release</a>
                                    <a class="dropdown-item" href="transaction/prpo/{{ $user->company_id }}?status=9&type=po&s=&user_req=&user_prep&bal&project&is_confidential&due_from&due_to">Purchase Order</a>
                                    {{-- <a class="dropdown-item" href="transaction-liquidation/pc/{{ $user->company_id }}?status=cleared&type=&s=">Petty Cash</a> --}}
                                </div>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="material-icons text-primary">arrow_drop_up</i>
                                </button>
                            </div>  
                        </div>
                        <div class="card-body p-0 table-responsive">
                            <table class="table table-striped small m-0">
                                <thead>
                                    <tr>
                                        <th>PO/PR #</th>
                                        <th>Vendor/Payee</th>
                                        <th>Purpose</th>
                                        <th class="text-right">Amount</th>
                                        <th>Trans. #</th>
                                        <th class="text-center">Last Updated</th>
                                    </tr>
                                </thead>
                                @foreach ($cleared as $item)
                                    <?php 
                                        $config_confidential = false;
                                        if (Auth::user()->id != $item->requested_id) {
                                            // check levels
                                            if (Auth::user()->ualevel->code < $item->owner->ualevel->code) $config_confidential = true;
                                            // check level parallel confidential
                                            if (Auth::user()->ualevel->code == $item->owner->ualevel->code && $item->is_confidential && Auth::user()->id != $item->owner->id) $config_confidential = true;
                                            // check level own confidential
                                            if ($item->is_confidential_own && Auth::user()->id != $item->owner->id) $config_confidential = true;
                                        }
                                    ?>
                                    <tr>
                                        <td>
                                            @if ($config_confidential)
                                                {{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}
                                            @else
                                                <a href="transaction-liquidation/view/{{ $item->id }}">
                                                    {{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ $item->payee }}
                                            @endif    
                                        </td>
                                        <td>
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ $item->purpose }}
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ number_format($item->form_amount_payable ?: $item->amount, 2, '.', ',') }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ $item->depo_ref }}
                                            @endif    
                                        </td>
                                        <td class="text-center">{{ Carbon::parse($item->updated_at)->diffInDays(Carbon::now()) >= 1 ? $item->updated_at->format('Y-m-d') : $item->updated_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
        
                {{-- DEPOSITED --}}
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header pb-2">
                            <h3 class="card-title">Deposited</h3>
                            <div class="card-tools">
                                <a class="small" href="transaction/prpo/{{ $user->company_id }}?status=9&category=is_deposit&type=&s=&user_req=&user_prep&bal&project&is_confidential&due_from&due_to">Show More</a>
                                <button type="button" class="btn btn-tool pr-0 d-none" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons text-primary">list</i>
                                </button>                            
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="transaction/prpo/{{ $user->company_id }}?status=7,8&type=pr&s=&user_req=&user_prep&bal&project&is_confidential&due_from&due_to">Payment Release</a>
                                    <a class="dropdown-item" href="transaction/prpo/{{ $user->company_id }}?status=7,8&type=po&s=&user_req=&user_prep&bal">Purchase Order</a>
                                    {{-- <a class="dropdown-item" href="transaction-liquidation/pc/{{ $user->company_id }}?status=7,8&type=&s=&user_req=&user_prep&bal">Petty Cash</a> --}}
                                </div>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="material-icons text-primary">arrow_drop_up</i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            <table class="table table-striped small m-0">
                                <thead>
                                    <tr>
                                        <th>PO/PR #</th>
                                        <th>Payor</th>
                                        <th>Purpose</th>
                                        <th class="text-right">Amount</th>
                                        <th>Trans. #</th>
                                        <th class="text-center">Date Deposited</th>
                                    </tr>
                                </thead>
                                @foreach ($deposited as $item)
                                    <?php 
                                        $config_confidential = false;
                                        if (Auth::user()->id != $item->requested_id) {
                                            // check levels
                                            if (Auth::user()->ualevel->code < $item->owner->ualevel->code) $config_confidential = true;
                                            // check level parallel confidential
                                            if (Auth::user()->ualevel->code == $item->owner->ualevel->code && $item->is_confidential && Auth::user()->id != $item->owner->id && Auth::user()->id != $item->requested_id) $config_confidential = true;
                                            // check level own confidential
                                            if ($item->is_confidential_own && Auth::user()->id != $item->owner->id && Auth::user()->id != $item->requested_id) $config_confidential = true;
                                        }
                                    ?>
                                    <tr>
                                        <td>
                                            @if ($config_confidential)
                                                {{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}
                                            @else
                                                <a href="transaction-liquidation/view/{{ $item->id }}">
                                                    {{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ $item->payor }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ $item->purpose }}
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ number_format($item->form_amount_payable ?: $item->amount, 2, '.', ',') }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ $item->control_no }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ $item->depo_date }}
                                            @endif    
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection