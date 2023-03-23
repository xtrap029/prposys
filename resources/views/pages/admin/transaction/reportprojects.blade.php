@extends('layouts.app')

@section('title', 'Report - Projects')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Report - Projects</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="" method="get" class="no-print" id="filter">
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
                                <label for="">Company / Projects</label>
                                <select name="company" class="form-control form-control-sm">
                                    <option value="">All Companies</option>
                                    @foreach ($companies->whereIn('id', explode(',', Auth::user()->companies)) as $item)
                                        <optgroup label="{{ $item->name }}">
                                            <option value="C{{ $item->id }}" {{ !empty($trans_company) ? $trans_company == 'C'.$item->id ? 'selected' : '' : '' }}>All Projects - {{ $item->name }}</option>
                                            @foreach ($item->companyProject as $project)
                                                <option value="{{ $project->id }}" {{ !empty($trans_company) ? $trans_company == $project->id ? 'selected' : '' : '' }}>{{ $project->project }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-2 my-1">
                                <label for="">Type</label>
                                <select name="type" class="form-control form-control-sm">
                                    <option value="">All</option>
                                    <option value="pr" {{ $trans_type == "pr" ? 'selected' : '' }}>PR</option>
                                    <option value="po" {{ $trans_type == "po" ? 'selected' : '' }}>PO</option>
                                </select>
                            </div>

                            <div class="col-sm-6 col-md-3 my-1">
                                <label for="">Status</label>
                                <select name="status[]" class="form-control form-control-sm chosen-select" multiple>
                                    @foreach (config('global.status_filter_reports') as $item)
                                        @if (in_array($item[1], config('global.page_liquidation')))
                                            <option value="{{ $item[1] }}" {{ !empty($_GET['status']) && in_array($item[1], $_GET['status']) ? 'selected' : '' }}>{{ $item[0] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-2 my-1">
                                <label for="">Date From</label>
                                <input type="date" name="from" class="form-control form-control-sm" value="{{ !empty($_GET['from']) ? $_GET['from'] : '' }}">
                            </div>
                            <div class="col-sm-6 col-md-2 my-1">
                                <label for="">Date To</label>
                                <input type="date" name="to" class="form-control form-control-sm" value="{{ !empty($_GET['to']) ? $_GET['to'] : '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </form>


            <div class="form-row mb-3 no-print">
                <div class="my-1 col-6 col-sm-3 col-lg-2 col-xl-1">
                    <a href="/transaction/report-projects?from={{ date('Y-m-01') }}&to={{ date('Y-m-t') }}" class="btn btn-secondary btn-block">Reset</a>
                </div>
                <div class="my-1 col-6 col-sm-3 col-lg-2 col-xl-1">
                    <input type="submit" value="Generate" class="btn btn-primary btn-block" form="filter">
                </div>
                <div class="my-1 col-6 offset-lg-4 offset-xl-8 col-sm-3 col-lg-2 col-xl-1">
                    <div class="btn-group btn-block" role="group">
                        <button type="button" class="btn btn-danger" onclick="window.print();">Print</button>
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

                <div class="table-responsive mt-3">
                    @foreach ($query as $company)
                        <h5 class="font-weight-bold">{{ $company->name }}</h5>
                        <table class="table small table-sm">
                            @forelse ((!empty($trans_company) && !str_starts_with($trans_company, 'C') ? $company->companyProject->where('id', $trans_company) : $company->companyProject) as $project)
                                <tr>
                                    <td>
                                        <h6 class="bg-gray-light p-2 px-3">{{ $project->project }}</h6>
                                        <div class="p-2 px-4">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th style="width:100px">PR/PO</th>
                                                        <th style="width:150px">Date</th>
                                                        <th style="width:150px">Type</th>
                                                        <th style="width:150px">Location/Route</th>
                                                        <th style="width:100px">Receipt</th>
                                                        <th style="width:150px">Status</th>
                                                        <th>Description</th>
                                                        <th class="text-right">Amount</th>
                                                    </tr>
                                                </thead>
                                                <?php
                                                    $project_liquidations = $project->liquidations->sortBy(function($item){ return $item->transaction_id.'#'.$item->date; });
                                                    if (!empty($trans_type)) {
                                                        $project_liquidations = $project_liquidations->where('transaction.trans_type', $trans_type);
                                                    }
                                                    if (!empty($trans_status)) {
                                                        $project_liquidations = $project_liquidations->whereIn('transaction.status_id', $trans_status);
                                                    }
                                                    if (!empty($trans_from)) {
                                                        $project_liquidations = $project_liquidations->where('transaction.created_at', '>=', $trans_from);
                                                    }
                                                    if (!empty($trans_to)) {
                                                        $project_liquidations = $project_liquidations->where('transaction.created_at', '<=', $trans_to);
                                                    }
                                                    if (!empty($trans_own)) {
                                                        $project_liquidations = $project_liquidations->where('transaction.requested_id', Auth::user()->id);
                                                    }
                                                ?>
                                                @forelse ($project_liquidations as $liquidation)
                                                    <tr>
                                                        <td>{{ strtoupper($liquidation->transaction->trans_type).'-'.$liquidation->transaction->trans_year.'-'.sprintf('%05d',$liquidation->transaction->trans_seq) }}</td>
                                                        <td>{{ $liquidation->date }}</td>
                                                        <td>{{ $liquidation->expenseType->name }}</td>
                                                        <td>{{ $liquidation->location }}</td>
                                                        <td>{{ $liquidation->receipt ? 'Y' : 'N' }}</td>
                                                        <td>{{ $liquidation->transaction->status->name }}</td>
                                                        <td>{{ $liquidation->description }}</td>
                                                        <td class="text-right">{{ number_format($liquidation->amount, 2, '.', ',') }}</td>
                                                    </tr>
                                                @empty
                                                    <tr><td class="p-2" colspan="100"><i>No projects available</i></td></tr>
                                                @endforelse
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td class="p-2"><i>No projects available</i></td></tr>
                            @endforelse
                        </table>
                    @endforeach
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