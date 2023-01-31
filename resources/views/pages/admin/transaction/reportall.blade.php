@extends('layouts.app')

@section('title', 'General Report')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>General Report</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="modal fade" id="modal-filter" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title">Available Filters</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <form action="" method="get" class="no-print row" id="filter">
                                <div class="col-sm-6 col-md-3 my-1">
                                    <label for="">Template</label>
                                    <select name="template" class="form-control">
                                        @foreach ($report_templates as $item)
                                            <option value="{{ $item->id }}"
                                                @if (!empty($_GET['template']))
                                                    @if ($_GET['template'] == $item->id)
                                                        selected
                                                    @endif
                                                @elseif ($item->id == 1)
                                                    selected
                                                @endif
                                                >{{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-3 my-1">
                                    <label for="">Type</label>
                                    <select name="type" class="form-control">
                                        <option value="">All</option>
                                        <option value="pr" {{ $trans_type == "pr" ? 'selected' : '' }}>Payment Release</option>
                                        <option value="po" {{ $trans_type == "po" ? 'selected' : '' }}>Purchase Order</option>
                                        {{-- <option value="pc" {{ $trans_type == "pc" ? 'selected' : '' }}>PC</option> --}}
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-3 my-1">
                                    <label for="">Company</label>
                                    <select name="company" class="form-control">
                                        <option value="">All</option>
                                        @foreach ($companies->whereIn('id', explode(',', Auth::user()->companies)) as $item)
                                            <option value="{{ $item->id }}" {{ !empty($trans_company) ? $trans_company == $item->id ? 'selected' : '' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-3 my-1">
                                    <label for="">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="">All</option>
                                        @foreach (config('global.status_filter_reports') as $item)
                                            <option value="{{ $item[1] }}" {{ !empty($_GET['status']) && $_GET['status'] == $item[1] ? 'selected' : '' }}>{{ $item[0] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-3 my-1">
                                    <label for="">Requested By</label>
                                    <select name="user_req" class="form-control">
                                        <option value="">All</option>
                                        @foreach ($users as $item)
                                            <option value="{{ $item->id }}" {{ app('request')->input('user_req') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-3 my-1">
                                    <label for="">Prepared By</label>
                                    <select name="user_prep" class="form-control">
                                        <option value="">All</option>
                                        @foreach ($users as $item)
                                            <option value="{{ $item->id }}" {{ app('request')->input('user_prep') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-3 my-1">
                                    <label for="">Released By</label>
                                    <select name="user_rel" class="form-control">
                                        <option value="">All</option>
                                        @foreach ($releasers as $item)
                                            <option value="{{ $item->id }}" {{ app('request')->input('user_rel') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-3 my-1">
                                    <label for="">Updated By</label>
                                    <select name="user_updated" class="form-control">
                                        <option value="">All</option>
                                        @foreach ($users as $item)
                                            <option value="{{ $item->id }}" {{ app('request')->input('user_updated') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-3 my-1">
                                    <label for="">Form Approved By</label>
                                    <select name="user_approver_form" class="form-control">
                                        <option value="">All</option>
                                        @foreach ($users as $item)
                                            <option value="{{ $item->id }}" {{ app('request')->input('user_approver_form') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 my-1">
                                    <label for="">Category</label>
                                    <select name="category" class="form-control">
                                        <option value="">All</option>
                                        @foreach (config('global.trans_category_column_2') as $key => $item)
                                            <option value="{{ $item }}" {{ !empty($_GET['category']) && $_GET['category'] == $item ? 'selected' : '' }}>{{ config('global.trans_category_label')[$key] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-3 my-1">
                                    <label for="">Bank</label>
                                    <select name="bank" class="form-control">
                                        <option value="">All</option>
                                        @foreach ($banks as $item)
                                            <optgroup label="{{ $item->name }}">
                                                @foreach ($item->bankbranches as $branch)
                                                    <option value="{{ $branch->id }}" {{ app('request')->input('bank') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 my-1">
                                    <label for="">Amount</label>
                                    <select name="bal" class="form-control">
                                        <option value="">All</option>
                                        <option value="0" {{ app('request')->input('bal') != "" && app('request')->input('bal') == 0 ? 'selected' : '' }}>No Return</option>
                                        <option value="1" {{ app('request')->input('bal') == '1' ? 'selected' : '' }}>( + ) For Reimbursement</option>
                                        <option value="-1" {{ app('request')->input('bal') == '-1' ? 'selected' : '' }}>( - ) Return Money</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-3 my-1">
                                    <label for="">Date From</label>
                                    <input type="date" name="from" class="form-control" value="{{ !empty($_GET['from']) ? $_GET['from'] : '' }}">
                                </div>
                                <div class="col-sm-6 col-md-3 my-1">
                                    <label for="">Date To</label>
                                    <input type="date" name="to" class="form-control" value="{{ !empty($_GET['to']) ? $_GET['to'] : '' }}">
                                </div>
                                <div class="col-sm-6 col-md-3 my-1">
                                    <label for="">Due From</label>
                                    <input type="date" name="due_from" class="form-control" value="{{ !empty($_GET['due_from']) ? $_GET['due_from'] : '' }}">
                                </div>
                                <div class="col-sm-6 col-md-3 my-1">
                                    <label for="">Due To</label>
                                    <input type="date" name="due_to" class="form-control" value="{{ !empty($_GET['due_to']) ? $_GET['due_to'] : '' }}">
                                </div>
                                <div class="col-sm-6 col-md-3 my-1">
                                    <label for="">Depo. From</label>
                                    <input type="date" name="depo_from" class="form-control" value="{{ !empty($_GET['depo_from']) ? $_GET['depo_from'] : '' }}">
                                </div>
                                <div class="col-sm-6 col-md-3 my-1">
                                    <label for="">Depo. To</label>
                                    <input type="date" name="depo_to" class="form-control" value="{{ !empty($_GET['depo_to']) ? $_GET['depo_to'] : '' }}">
                                </div>
                                <div class="col-sm-6 col-md-3 my-1">
                                    <label for="">Released From</label>
                                    <input type="date" name="rel_from" class="form-control" value="{{ !empty($_GET['rel_from']) ? $_GET['rel_from'] : '' }}">
                                </div>
                                <div class="col-sm-6 col-md-3 my-1">
                                    <label for="">Released To</label>
                                    <input type="date" name="rel_to" class="form-control" value="{{ !empty($_GET['rel_to']) ? $_GET['rel_to'] : '' }}">
                                </div>
                                <div class="col-sm-6 col-md-3 my-1">
                                    <label for="">Deposit Type</label>
                                    <select name="depo_type" class="form-control">
                                        <option value="">All</option>
                                        @foreach (config('global.deposit_type') as $item)
                                            <option value="{{ $item }}" {{ app('request')->input('depo_type') == $item ? 'selected' : '' }}>{{ ucfirst(strtolower($item)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-3 my-1">
                                    <label for="">VAT Type</label>
                                    <select name="vat_type" class="form-control">
                                        <option value="">All</option>
                                        @foreach ($vat_types as $item)
                                            <option value="{{ $item->id }}" {{ app('request')->input('vat_type') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-3 my-1">
                                    <label for="">Particulars</label>
                                    <select name="particulars" class="form-control">
                                        <option value="">All</option>
                                        @foreach ($particulars as $item)
                                            <option value="{{ $item->id }}" {{ app('request')->input('particulars') == $item->id ? 'selected' : '' }}>{{ strtoupper($item->type).' - '.$item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-3 my-1">
                                    <label for="">Currency</label>
                                    <select name="currency" class="form-control">
                                        <option value="">All</option>
                                        @foreach (config('global.currency') as $item)
                                            <option value="{{ $item }}" {{ app('request')->input('currency') == $item ? 'selected' : '' }}>{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <a href="/transaction/report-all?from={{ date('Y-m-01') }}&to={{ date('Y-m-t') }}" class="btn btn-secondary">Reset</a>
                            <input type="submit" value="Generate" class="btn btn-primary" form="filter">
                        </div>
                    </div>
                </div>
            </div>

            <form action="" method="get" class="no-print" id="filter-old">
                <div class="card small">
                    <div class="card-header bg-gray">
                        <b>Main Filters</b>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="material-icons">arrow_drop_up</i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body bg-gray-light">
                        <div class="row">
                            <div class="col-sm-6 col-md-3 my-1">
                                <label for="">
                                    <a href="/report-template" target="_blank">Add/Edit Template</a>
                                </label>
                                <select name="template" class="form-control form-control-sm">
                                    <option disabled {{ empty($_GET['template']) ? 'selected' : '' }}>Choose Template</option>
                                    @foreach ($report_templates as $item)
                                        <option value="{{ $item->id }}"
                                            @if (!empty($_GET['template']) && $_GET['template'] == $item->id)
                                                selected
                                            @endif
                                            >{{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-3 my-1">
                                <label for="">Type</label>
                                <select name="type" class="form-control form-control-sm">
                                    <option value="">All</option>
                                    <option value="pr" {{ $trans_type == "pr" ? 'selected' : '' }}>PR</option>
                                    <option value="po" {{ $trans_type == "po" ? 'selected' : '' }}>PO</option>
                                </select>
                            </div>
                            <div class="col-sm-6 my-1">
                                <label for="">Status</label>
                                <select name="status[]" class="form-control form-control-sm chosen-select" multiple>
                                    @foreach (config('global.status_filter_reports') as $item)
                                        <option value="{{ $item[1] }}" {{ !empty($_GET['status']) && in_array($item[1], $_GET['status']) ? 'selected' : '' }}>{{ $item[0] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-3 my-1">
                                <label for="">Company</label>
                                <select name="company" class="form-control form-control-sm">
                                    <option value="">All</option>
                                    @foreach ($companies->whereIn('id', explode(',', Auth::user()->companies)) as $item)
                                        <option value="{{ $item->id }}" {{ !empty($trans_company) ? $trans_company == $item->id ? 'selected' : '' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 my-1">
                                <label for="">Category</label>
                                <select name="category" class="form-control form-control-sm">
                                    <option value="">All</option>
                                    @foreach (config('global.trans_category_column_2') as $key => $item)
                                        <option value="{{ $item }}" {{ !empty($_GET['category']) && $_GET['category'] == $item ? 'selected' : '' }}>{{ config('global.trans_category_label')[$key] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-3 my-1">
                                <label for="">Date From</label>
                                <input type="date" name="from" class="form-control form-control-sm" value="{{ !empty($_GET['from']) ? $_GET['from'] : '' }}">
                            </div>
                            <div class="col-sm-6 col-md-3 my-1">
                                <label for="">Date To</label>
                                <input type="date" name="to" class="form-control form-control-sm" value="{{ !empty($_GET['to']) ? $_GET['to'] : '' }}">
                            </div>
                            <div class="col-sm-6 col-md-3 my-1">
                                <label for="">PR/PO # Year</label>
                                <input type="number" name="series_year" class="form-control form-control-sm" min="1900" max="2100" value="{{ !empty($_GET['series_year']) ? $_GET['series_year'] : '' }}">
                            </div>
                            <div class="col-sm-6 col-md-3 my-1">
                                <label for="">PR/PO # Series (Min)</label>
                                <input type="number" name="series_min" class="form-control form-control-sm" min="0" value="{{ !empty($_GET['series_min']) ? $_GET['series_min'] : '' }}">
                            </div>
                            <div class="col-sm-6 col-md-3 my-1">
                                <label for="">PR/PO # Series (Max)</label>
                                <input type="number" name="series_max" class="form-control form-control-sm" min="0" value="{{ !empty($_GET['series_max']) ? $_GET['series_max'] : '' }}">
                            </div>                            
                            <div class="col-12 col-md-3 my-1">
                                <label for="">Control/Reference No.</label>
                                <input type="text" name="control_no" class="form-control form-control-sm" value="{{ !empty($_GET['control_no']) ? $_GET['control_no'] : '' }}">
                            </div>
                            <div class="col-12 my-1">
                                <label for="">Keyword</label>
                                <input type="text" class="form-control form-control-sm" name="s" value="{{ app('request')->input('s') }}" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card small">
                    <div class="card-header bg-gray">
                        <b>Column Filters</b>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="material-icons">arrow_drop_up</i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body bg-gray-light">
                        <div class="row">
                            @include('pages.admin.transaction.reportallcolumns', ['column_codes' => $column_codes])
                        </div>
                    </div>
                </div>
            </form>


            <div class="form-row mb-3 no-print">
                <div class="my-1 col-6 col-sm-3 col-lg-2 col-xl-1">
                    <a href="/transaction/report-all?from={{ date('Y-m-01') }}&to={{ date('Y-m-t') }}" class="btn btn-secondary btn-block">Reset</a>
                </div>
                <div class="my-1 col-6 col-sm-3 col-lg-2 col-xl-1 d-none">
                    <a data-toggle="modal" data-target="#modal-filter" href="#_" class="btn btn-secondary btn-block"><i class="align-middle font-weight-bolder material-icons text-md">filter_alt</i> Filters</a>
                </div>
                <div class="my-1 col-6 col-sm-3 col-lg-2 col-xl-1">
                    {{-- <input type="submit" value="Generate" class="btn btn-primary btn-block" form="filter"> --}}
                    <input type="submit" value="Generate" class="btn btn-primary btn-block" form="filter-old">
                </div>
                <div class="my-1 col-6 offset-lg-4 offset-xl-8 col-sm-3 col-lg-2 col-xl-1">
                    <div class="btn-group btn-block" role="group">
                        <button type="button" class="btn btn-danger" onclick="window.print();">Print</button>
                        @if (!empty($_GET['status']) && in_array($_GET['status'], config('global.issued_cleared')))
                            <button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item {{ !in_array($_GET['status'], config('global.form_issued')) ? 'd-none' : '' }}" href="#"
                                    onclick="window.open('/transaction-form/print-issued?type={{ $trans_type }}&company={{ $trans_company }}&status={{ $trans_status }}&category={{ $trans_category }}&bal={{ $trans_bal }}&from={{ $trans_from }}&to={{ $trans_to }}','name','width=800,height=800')">
                                    Issued Forms
                                <a class="dropdown-item {{ !in_array($_GET['status'], config('global.liquidation_cleared')) ? 'd-none' : '' }}" href="#"
                                    onclick="window.open('/transaction-liquidation/print-cleared?type={{ $trans_type }}&company={{ $trans_company }}&status={{ $trans_status }}&category={{ $trans_category }}&bal={{ $trans_bal }}&from={{ $trans_from }}&to={{ $trans_to }}','name','width=800,height=800')">
                                    Cleared Forms
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="my-1 col-6 col-sm-3 col-lg-2 col-xl-1">
                    <a href="{{ url()->current().'?'.http_build_query(array_merge(request()->all(),['csv' => ''])) }}" class="btn btn-success btn-block" target="_blank">CSV</a>
                </div>
            </div>
            <div class="pb-4 pt-4 px-3 card">
                <div class="mb-3">
                    <div class="float-left">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td class="border-0 pr-3">Type</td>
                                <td class="border-0 font-weight-bold">
                                    @switch($trans_type)
                                        @case('pr')
                                            Payment Release
                                            @break
                                        @case('po')
                                            Purchase Order
                                            @break
                                        @case('po')
                                            Petty Cash
                                            @break
                                        @default
                                            All
                                    @endswitch
                                </td>
                            </tr>
                            <tr>
                                <td class="border-0 pr-3">Status</td>
                                {{-- <td class="border-0 font-weight-bold">{{ $status_sel != '' ? $status_sel : 'All' }}</td> --}}
                                <td class="border-0 font-weight-bold">{{ $status_name }}</td>
                            </tr>
                            <tr>
                                <td class="border-0 pr-3">Start Date</td>
                                <td class="border-0 font-weight-bold">
                                    {{ $trans_from != '' ? $trans_from : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="border-0 pr-3">End Date</td>
                                <td class="border-0 font-weight-bold">
                                    {{ $trans_to != '' ? $trans_to : '-' }}
                                </td>
                            </tr>
                        </table>
                    </div>                    
                    <div class="float-right">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td class="border-0 pr-3">Date Generated</td>
                                <td class="border-0 font-weight-bold">{{ Carbon\Carbon::now() }}</td>
                            </tr>
                            <tr>
                                <td class="border-0 pr-3">Generated By</td>
                                <td class="border-0 font-weight-bold text-right">{{ Auth::user()->name }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                @if ($trans_company != "" && count($transactions) > 0)
                    <div class="mb-3 text-center">
                        <img src="/storage/public/images/companies/{{ $transactions[0]->project->company->logo }}" alt="" class="thumb thumb--xs mr-2 vlign--baseline-middle">
                        <span class="mr-3 vlign--baseline-middle">{{ $transactions[0]->project->company->name }}</span>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table mb-0 small table-sm table-striped">
                        <tr class="bg-gray font-weight-bold">
                            @foreach ($report_template->templatecolumn as $item)
                                <td>{{ $item->label }}</td>
                            @endforeach
                        </tr>
                        @foreach ($transactions as $item)
                            <?php $config_confidential = 0; ?>

                            <?php 
                                $config_confidential2 = false;

                                if (Auth::user()->id != $item->requested_id) {
                                    // check levels
                                    // if (Auth::user()->ualevel->code < $item->owner->ualevel->code) $config_confidential2 = true;
                                    // check level parallel confidential
                                    // if (Auth::user()->ualevel->code == $item->owner->ualevel->code && $item->is_confidential && Auth::user()->id != $item->owner->id) $config_confidential2 = true;
                                    if (Auth::user()->ualevel->code <= $item->owner->ualevel->code && $item->is_confidential && Auth::user()->id != $item->owner->id && !$can_view_confidential) $config_confidential2 = true;
                                    // check level own confidential
                                    if ($item->is_confidential_own && Auth::user()->id != $item->owner->id) $config_confidential2 = true;
                                }
                            ?>
                            @if (!$config_confidential2)
                                <tr>
                                    @foreach ($report_template->templatecolumn as $item_2)
                                        <td>{{ eval($item_2->column->code) }}</td>
                                    @endforeach
                                </tr>
                            @endif
                        @endforeach
                    </table>
                </div>
            </div>
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
    </script>
@endsection