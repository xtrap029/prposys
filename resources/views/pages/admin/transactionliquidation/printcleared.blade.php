@extends('layouts.app')

@section('title', 'Print Cleared Forms')

@section('content')
    @foreach ($transactions as $key2 => $transaction)
        <section class="content" style="page-break-before: always">
            <div class="container-fluid">
                <div class="float-right">Date Generated: <b>{{ Carbon\Carbon::now() }}</b></div>
                <h1>{{ strtoupper($transaction->trans_type) }} Liquidation</h1>
                <div class="row my-3">
                    <div class="col-6">
                        <img src="/storage/public/images/companies/{{ $transaction->project->company->logo }}" alt="" class="thumb--sm">
                    </div>
                    <div class="col-6">
                        <h2 class="text-right mt-3">{{ $transaction->project->company->name }}</h2>
                    </div>
                    <div class="col-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Request No.</th>
                                    <th>Date Requested</th>
                                    <th>Staff</th>
                                    <th>Project</th>
                                    <th>COA Tagging</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <h3 class="font-weight-bold pb-0 mb-0">
                                            {{ strtoupper($transaction->trans_type) }}-{{ $transaction->trans_year }}-{{ sprintf('%05d',$transaction->trans_seq) }}
                                        </h3>
                                    </td>
                                    <td>{{ Carbon::parse($transaction->created_at)->format('Y-m-d') }}</td>
                                    <td>{{ $transaction->requested->name }}</td>
                                    <td>{{ $transaction->project->project }}</td>
                                    <td>{{ $transaction->coatagging->name }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>  
                </div>
                <div class="row row--print">
                    @if (!$transaction->is_deposit && !$transaction->is_bills)
                        <div class="col-12 my-3">
                            <table class="table table-sm">
                                <tr class="font-weight-bold">
                                    <td>Pos.</td>
                                    <td>Date</td>
                                    <td>Type</td>
                                    <td>Description</td>
                                    <td>Location/Route</td>
                                    <td class="text-center">Receipt</td>
                                    <td class="text-right">Amount</td>
                                </tr>
                                @foreach ($transaction->liquidation as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->date }}</td>
                                        <td>{{ $item->expensetype->name }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>{{ $item->location }}</td>
                                        <td class="text-center">{{ $item->receipt == 1 ? 'Y' : 'N' }}</td>
                                        <td class="text-right">{{ number_format($item->amount, 2, '.', ',') }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    @else
                        <div class="col-12 my-3">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date Deposited</th>
                                        <th>Type</th>
                                        <th>Bank</th>
                                        <th>Reference Code</th>
                                        <th>Status</th>
                                    </tr>
                                </thead> 
                                <tbody>
                                    <tr>
                                        <td>{{ $transaction->depo_date }}</td>
                                        <td>{{ $transaction->depo_type }}</td>
                                        <td>{{ $transaction->bankbranch ? $transaction->bankbranch->bank->name.' ('.$transaction->bankbranch->name.')' : '' }}</td>
                                        <td>{{ $transaction->depo_ref }}</td>
                                        <td>{{ $transaction->status->name }}</td>
                                    </tr>
                                </tbody>   
                            </table>
                        </div>
                    @endif
                </div>
                <div class="row row--print">
                    <div class="col-12 my-3">
                        <table class="table table-sm">
                            @foreach ($transactions_summary[$key2] as $item)
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="5">
                                        {{ $item->name }}
                                        <span class="float-right">{{ number_format($item->amount, 2, '.', ',') }}</span>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="7" class="py-4"></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="font-weight-bold small text-right">Before VAT</td>
                                <td></td>
                                <td colspan="2" class="bg-white text-right">
                                    <span class="float-left">{{ $transaction->currency }}</span>
                                    {{ number_format($transaction->liq_before_vat, 2, '.', ',') }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="font-weight-bold small text-right">VAT (12%)</td>
                                <td></td>
                                <td colspan="2" class="bg-white text-right font-italic">
                                    <span class="float-left">{{ $transaction->currency }}</span>
                                    {{ number_format($transaction->liq_vat, 2, '.', ',') }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="font-weight-bold small text-right">Subtotal</td>
                                <td class="pb-3"><div class="pb-5"></div></td>
                                <td colspan="2" class="bg-white text-right font-weight-bold">
                                    <span class="float-left">{{ $transaction->currency }}</span>
                                    {{ number_format($transaction->liq_subtotal, 2, '.', ',') }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="font-weight-bold small text-right">Less: Deposit/Payment</td>
                                <td></td>
                                <td colspan="2" class="bg-white text-right text-danger">
                                    <span class="float-left">{{ $transaction->currency }}</span>
                                    {{ number_format($transaction->amount_issued, 2, '.', ',') }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="small font-weight-bold text-right">Balance</td>
                                <td></td>
                                <td colspan="2" class="bg-white text-right font-weight-bold">
                                    <span class="float-left">{{ $transaction->currency }}</span>
                                    {{ number_format($transaction->liq_balance, 2, '.', ',') }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="7" class="small text-right">
                                    <span>(+) For Reimbursement / (-) Return Money</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row row--print">
                    <div class="col-7">
                        @if (!$transaction->is_deposit && !$transaction->is_bills)
                            <table class="table table-sm">
                                <tr>
                                    <td>Amount {{ $transaction->liq_balance >= 0 ? 'Reimbursed' : 'Returned' }}</td>
                                    <td class="font-weight-bold">{{ $transaction->currency }} {{ $transaction->liq_balance < 0 ? number_format($transaction->liq_balance*-1, 2, '.', ',') : $transaction->liq_balance }}</td>
                                </tr>                            
                                <tr>
                                    <td>Date Deposited</td>
                                    <td class="font-weight-bold">{{ $transaction->depo_date }}</td>
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <td class="font-weight-bold">{{ $transaction->depo_type }}</td>
                                </tr>
                                <tr>
                                    <td>Bank</td>
                                    <td class="font-weight-bold">{{ $transaction->bankbranch ? $transaction->bankbranch->bank->name.' ('.$transaction->bankbranch->name.')' : '' }}</td>
                                </tr>
                                <tr>
                                    <td>Reference Code</td>
                                    <td class="font-weight-bold">{{ $transaction->depo_ref }}</td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td class="font-weight-bold">{{ $transaction->status->name }}</td>
                                </tr>
                            </table>
                        @endif
                    </div>

                    <div class="col-5">
                        <table class="table table-sm">
                            <tr>
                                <td><span class="mr-5">Entered into Quickbooks</span> <input type="checkbox" class="ml-5"></td>
                            </tr>
                            <tr>
                                <td>Entered By</td>
                            </tr>
                            <tr>
                                <td>Date Entered</td>
                            </tr>
                            <tr>
                                <td><span class="mr-5">Files Uploaded</span> <span class="ml-5 pl-5">{{ count($transaction->attachments) }}</span></td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-4 text-center my-4 small">
                        <h5>{{ $transaction->requested->name }}</h5>
                        <div class="mt-2 pt-2 border-top font-weight-bold">Requested By</div>
                    </div>                
                    <div class="col-4 text-center my-4 small {{ $transaction->liquidation_approver_id ? '' : 'd-none' }}">
                        <h5>{{ $transaction->liquidation_approver_id ? $transaction->liquidationapprover->name : '' }}</h5>
                        <div class="mt-2 pt-2 border-top font-weight-bold">{{ !$transaction->is_deposit ? 'Approver' : 'Deposited By' }}</div>
                    </div>
                    @if (!$transaction->is_deposit && !$transaction->is_bills)
                        <div class="col-4 text-center my-4 small">
                            <h5>{{ $final_approver }}</h5>
                            <div class="mt-2 pt-2 border-top font-weight-bold">Authorized By</div>
                        </div>
                    @endif
                    <div class="col-4 text-center my-4 small">
                        <h5>{{ Carbon\Carbon::now()->toDateString() }}</h5>
                        <div class="mt-2 pt-2 border-top font-weight-bold">Date</div>
                    </div>
                </div>
            </div>
        </section>
    @endforeach

    <script>
        window.print()
    </script>
@endsection