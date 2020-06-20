@extends('layouts.app')

@section('title', 'Transaction')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $page_label }}</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                            <th colspan="2" class="border-0">
                                <img src="/storage/public/images/companies/{{ $company->logo }}" alt="" class="thumb thumb--xxs mr-2">
                                <span>{{ $company->name }}</span>
                            </th>
                            <th colspan="8" class="border-0 text-right">
                                <a href="/transaction/create/pr/{{ $company->id }}" class="mx-3">Generate PR</a>
                                <a href="/transaction/create/po/{{ $company->id }}" class="mx-3">Generate PO</a>
                                <a href="#" class="mx-3">Reports</a>
                            </th>
                        </tr>
                        <tr class="small">
                            <th>Transaction</th>
                            <th>Payee</th>
                            <th class="text-right">Amount (PHP)</th>
                            <th>Particulars</th>
                            <th>Date Gen.</th>
                            <th>Check Number</th>
                            <th>Date Rel.</th>
                            <th>Req. By</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        @forelse ($transactions as $item)
                            <tr>
                                <td>{{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}</td>
                                <td>{{ $item->payee }}</td>
                                <td class="text-right">{{ number_format($item->amount, 2, '.', ',') }}</td>
                                <td>{{ $item->particulars->name }}</td>
                                <td>{{ Carbon::parse($item->created_at)->format('Y-m-d') }}</td>
                                <td>{{ $item->control_no }}</td>
                                <td>{{ $item->released_at }}</td>
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
                                        </div>
                                    </div>
                                </td>
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