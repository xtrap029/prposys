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
            <form action="" method="get" class="no-print">
                <div class="form-row mb-3">
                    <div class="col-sm-6 col-md-3 my-1">
                        <label for="">Type</label>
                        <select name="type" class="form-control">
                            <option value="">All</option>
                            <option value="pr" {{ $trans_type == "pr" ? 'selected' : '' }}>Payment Release</option>
                            <option value="po" {{ $trans_type == "po" ? 'selected' : '' }}>Purchase Order</option>
                            {{-- <option value="pc" {{ $trans_type == "pc" ? 'selected' : '' }}>PC</option> --}}
                        </select>
                    </div>
                    <div class="col-sm-6 my-1">
                        <label for="">Company</label>
                        <select name="company" class="form-control">
                            <option value="">All</option>
                            @foreach ($companies as $item)
                                <option value="{{ $item->id }}" {{ !empty($trans_company) ? $trans_company == $item->id ? 'selected' : '' : '' }}>{{ $item->name }}</option>
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
                        <label for="">Status</label>
                        <select name="status" class="form-control">
                            <option value="">All</option>
                            @foreach (config('global.status_filter_reports') as $item)
                                <option value="{{ $item[1] }}" {{ !empty($_GET['status']) && $_GET['status'] == $item[1] ? 'selected' : '' }}>{{ $item[0] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 my-1">
                        <label for="">Category</label>
                        <select name="category" class="form-control">
                            @foreach (config('global.trans_category_column') as $key => $item)
                                <option value="{{ $item }}" {{ !empty($_GET['category']) && $_GET['category'] == $item ? 'selected' : '' }}>{{ config('global.trans_category_label_filter_2')[$key] }}</option>
                            @endforeach
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
                </div>
                <div class="form-row mb-3">
                    <div class="my-1 col-sm-3 col-lg-2 col-xl-1">
                        <a href="/transaction/report-all?from={{ date('Y-m-01') }}&to={{ date('Y-m-t') }}" class="btn btn-secondary btn-block">Reset</a>
                    </div>
                    <div class="my-1 col-sm-3 col-lg-2 col-xl-1">
                        <input type="submit" value="Generate" class="btn btn-primary btn-block">
                    </div>
                    <div class="my-1 offset-lg-4 offset-xl-8 col-sm-3 col-lg-2 col-xl-1">
                        <div class="btn-group btn-block" role="group">
                            <button type="button" class="btn btn-danger" onclick="window.print();">Print</button>
                            @if (!empty($_GET['status']) && in_array($_GET['status'], config('global.issued_cleared')))
                                <button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item {{ !in_array($_GET['status'], config('global.form_issued')) ? 'd-none' : '' }}" href="#"
                                        onclick="window.open('/transaction-form/print-issued?type={{ $trans_type }}&company={{ $trans_company }}&status={{ $trans_status }}&category={{ $trans_category }}&from={{ $trans_from }}&to={{ $trans_to }}','name','width=800,height=800')">
                                        Issued Forms
                                    <a class="dropdown-item {{ !in_array($_GET['status'], config('global.liquidation_cleared')) ? 'd-none' : '' }}" href="#"
                                        onclick="window.open('/transaction-liquidation/print-cleared?type={{ $trans_type }}&company={{ $trans_company }}&status={{ $trans_status }}&category={{ $trans_category }}&from={{ $trans_from }}&to={{ $trans_to }}','name','width=800,height=800')">
                                        Cleared Forms
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="my-1 col-sm-3 col-lg-2 col-xl-1">
                        <a href="{{ url()->current().'?'.http_build_query(array_merge(request()->all(),['csv' => ''])) }}" class="btn btn-success btn-block" target="_blank">CSV</a>
                    </div>
                </div>
            </form>

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
                                <td class="border-0 font-weight-bold">{{ isset($_GET['status']) && $_GET['status'] != "" ? config('global.status_filter_reports')[array_search($_GET['status'], array_column(config('global.status_filter_reports'), 1))][0] : 'All' }}</td>
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

                <table class="table mb-0 small table-sm table-striped table-responsive-md">
                    <tr class="bg-gray font-weight-bold">
                        <td style="min-width: 75px;">PO/PR #</td>
                        <td>Company</td>
                        <td>Project</td>
                        <td>Purpose</td>
                        <td>Currency</td>
                        <td>Amount</td>
                        <td>Date Gen.</td>
                        <td>Trans. #</td>
                        <td>Last Updated</td>
                        <td>Req. By</td>
                        <td>Status</td>
                    </tr>
                    @foreach ($transactions as $item)
                        <tr>
                            <td><h6 class="font-weight-bold">{{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}</h6></td>
                            <td>{{ $item->project->company->name }}</td>
                            <td>{{ $item->project->project }}</td>
                            <td>{{ $item->purpose }}</td>
                            <td>{{ $item->currency }}</td>
                            <td>{{ number_format($item->form_amount_payable ?: $item->amount, 2, '.', ',') }}</td>
                            <td>{{ Carbon::parse($item->created_at)->format('Y-m-d') }}</td>
                            <td style="word-break: break-all;">{{ $item->control_no }}</td>
                            <td>{{ Carbon::parse($item->updated_at)->format('Y-m-d') }}</td>
                            <td>{{ $item->requested->name }}</td>
                            <td>{{ $item->status->name }}</td>
                            {{-- <td class="{{ empty($_GET['status']) || $_GET['status'] == "" ? '' : 'd-none' }}">
                                {{ $item->status->name }}
                            </td> --}}
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection