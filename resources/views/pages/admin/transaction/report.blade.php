@extends('layouts.app')

@section('title', 'Generated Report')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Generated Reports</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="/transaction/{{ $trans_page }}/{{ $_GET['company'] ? $_GET['company'] : '' }}" class="btn btn-default"><i class="align-middle font-weight-bolder material-icons text-md">arrow_back_ios</i> Back</a>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="" method="get" class="no-print">
                <div class="form-row mb-3">
                    <div class="col-md-2">
                        <label for="">Type</label>
                        <select name="type" class="form-control">
                            <option value="pr" {{ $_GET['type'] == "pr" ? 'selected' : '' }}>Payment Release</option>
                            <option value="po" {{ $_GET['type'] == "po" ? 'selected' : '' }}>Purchase Order</option>
                            {{-- <option value="pc" {{ $_GET['type'] == "pc" ? 'selected' : '' }}>Petty Cash</option> --}}
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="">Company</label>
                        <select name="company" class="form-control">
                            <option value="">All</option>
                            @foreach ($companies as $item)
                                <option value="{{ $item->id }}" {{ !empty($_GET['company']) ? $_GET['company'] == $item->id ? 'selected' : '' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="">Status</label>
                        <select name="status" class="form-control">
                            @foreach ($status as $item)
                                <option value="{{ $item->id }}" {{ !empty($_GET['status']) ? $_GET['status'] == $item->id ? 'selected' : '' : '' }}>{{ ucfirst(strtolower($item->name)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="">Date From</label>
                        <input type="date" name="from" class="form-control" value="{{ !empty($_GET['from']) ? $_GET['from'] : '' }}">
                    </div>
                    <div class="col-md-2">
                        <label for="">Date To</label>
                        <input type="date" name="to" class="form-control" value="{{ !empty($_GET['to']) ? $_GET['to'] : '' }}">
                    </div>
                    <div class="col-md-1">
                        <label for="" class="invisible">.</label>
                        <input type="submit" value="Generate" class="btn btn-primary btn-block">
                    </div>
                    <div class="col-md-1">
                        <label for="" class="invisible">.</label>
                        <a href="" class="btn btn-danger btn-block" onclick="window.print();">Print</a>
                    </div>
                </div>
            </form>

            <div class="pb-4 pt-4 px-3 card">
                <div class="mb-3">
                    <div class="float-left">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td class="border-0 pr-3">Transaction</td>
                                <td class="border-0 font-weight-bold">
                                    @switch($_GET['type'])
                                        @case('pr')
                                            Payment Release
                                            @break
                                        @case('po')
                                            Purchase Order
                                            @break
                                        @default
                                            Petty Cash
                                    @endswitch
                                </td>
                            </tr>
                            <tr>
                                <td class="border-0 pr-3">Status</td>
                                <td class="border-0">{{ $status_sel }}</td>
                            </tr>
                        </table>
                    </div>                    
                    <div class="float-right">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td class="border-0 pr-3">Date Generated</td>
                                <td class="border-0">{{ Carbon\Carbon::now() }}</td>
                            </tr>
                            <tr>
                                <td class="border-0 pr-3">Generated By</td>
                                <td class="border-0 text-right">{{ Auth::user()->name }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                @if ($_GET['company'] != "" && count($transactions) > 0)
                    <div class="mb-3 text-center">
                        <img src="/storage/public/images/companies/{{ $transactions[0]->project->company->logo }}" alt="" class="thumb thumb--xs mr-2 vlign--baseline-middle">
                        <span class="mr-3 vlign--baseline-middle">{{ $transactions[0]->project->company->name }}</span>
                    </div>
                @endif

                <table class="table mb-0 small table-striped">
                    <tr class="bg-gray font-weight-bold">
                        <td>Transaction</td>
                        <td>Particulars</td>
                        <td>Payee</td>
                        <td>Project</td>
                        <td>Prepared by</td>
                        <td>Requested by</td>
                        <td>Due Date</td>
                        <td colspan="2" class="text-right">Amount</td>
                        {{-- <td class="{{ empty($_GET['status']) || $_GET['status'] == "" ? '' : 'd-none' }}">Status</td> --}}
                    </tr>
                    @foreach ($transactions as $item)
                        <tr>
                            <td><h6 class="font-weight-bold">{{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}</h6></td>
                            <td>{{ $_GET['type'] == 'pc' ? $item->particulars_custom : $item->particulars->name }}</td>
                            <td>{{ $item->payee }}</td>
                            <td>{{ $item->project->project }}</td>
                            <td>{{ $item->owner->name }}</td>
                            <td>{{ $item->requested->name }}</td>
                            <td>{{ $item->due_at }}</td>
                            <td>{{ $item->currency }}</td>
                            <td class="text-right border-right">{{ number_format($item->amount, 2, '.', ',') }}</td>
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