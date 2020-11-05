@extends('layouts.app')

@section('title', 'Print Liquidation')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="float-right">Date Generated: <b>{{ Carbon\Carbon::now() }}</b></div>
            <h2>{{ strtoupper($transaction->trans_type) }} Liquidation</h2>
            <div class="row">
                <div class="col-10">
                    <h3 class="mt-4">{{ $transaction->project->company->name }}</h3>
                </div>
                <div class="col-2 text-right">
                    <img src="/storage/public/images/companies/{{ $transaction->project->company->logo }}" alt="" class="thumb--sm">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Request No.</th>
                                <th>Date Requested</th>
                                <th>Staff</th>
                                <th>COA Tagging</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <h5 class="font-weight-bold pb-0 mb-0">
                                        {{ strtoupper($transaction->trans_type) }}-{{ $transaction->trans_year }}-{{ sprintf('%05d',$transaction->trans_seq) }}
                                    </h5>
                                </td>
                                <td>{{ Carbon::parse($transaction->created_at)->format('Y-m-d') }}</td>
                                <td>{{ $transaction->requested->name }}</td>
                                <td>{{ $transaction->coatagging->name }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-7">
                            <table class="table table-sm">
                                <tr>
                                    <td class="font-weight-bold">Project</td>
                                    <td>{{ $transaction->project->project }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Vendor / Payee</td>
                                    <td>{{ $transaction->payee }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Particulars</td>
                                    <td>{{ $transaction->particulars_custom ?: $transaction->particulars->name }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Purpose</td>
                                    <td>{{ $transaction->purpose }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Due Date</td>
                                    <td>{{ $transaction->due_at }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Tax Type</td>
                                    <td>{{ $transaction->form_vat_name ? $transaction->form_vat_name : $transaction->vattype->name }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-5">
                            <table class="table table-sm">
                                <tr>
                                    <td class="font-weight-bold">Issue Type</td>
                                    <td>{{ $transaction->control_type }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Issue No</td>
                                    <td>{{ $transaction->control_no }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Released Date</td>
                                    <td>{{ $transaction->released_at }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Released Amount</td>
                                    <td>{{ $transaction->currency }} {{ number_format($transaction->amount_issued, 2, '.', ',') }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">For Deposit?</td>
                                    <td>{{ $transaction->is_deposit ? 'Yes' : 'No' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Bills Payment?</td>
                                    <td>{{ $transaction->is_bills ? 'Yes' : 'No' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row--print">
                @if (!$transaction->is_deposit && !$transaction->is_bills)
                    <div class="col-12">
                        <table class="table table-sm mb-0">
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
                    <div class="col-12">
                        <table class="table table-sm mb-0">
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
                <div class="col-12">
                    <table class="table table-sm">
                        @foreach ($transaction_summary as $item)
                            <tr>
                                <td colspan="2"></td>
                                <td colspan="5">
                                    {{ $item->name }}
                                    <span class="float-right">{{ number_format($item->amount, 2, '.', ',') }}</span>
                                </td>
                            </tr>
                        @endforeach
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
                            <td></td>
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
                <div class="col-3 text-center pt-3 my-4">
                    <h6 class="font-weight-bold">{{ $transaction->requested->name }}</h6>
                    <div class="mt-2 border-top">Requested By</div>
                </div>                
                <div class="col-3 text-center pt-3 my-4 {{ $transaction->liquidation_approver_id ? '' : 'd-none' }}">
                    <h6 class="font-weight-bold">{{ $transaction->liquidation_approver_id ? $transaction->liquidationapprover->name : '' }}</h6>
                    <div class="mt-2 border-top">{{ !$transaction->is_deposit ? 'Approver' : 'Deposited By' }}</div>
                </div>
                @if (!$transaction->is_deposit && !$transaction->is_bills)
                    <div class="col-3 text-center pt-3 my-4">
                        <h6 class="font-weight-bold">{{ $final_approver }}</h6>
                        <div class="mt-2 border-top">Authorized By</div>
                    </div>
                @endif
                <div class="col-3 text-center pt-3 my-4">
                    <h6 class="font-weight-bold">{{ Carbon\Carbon::now()->toDateString() }}</h6>
                    <div class="mt-2 border-top">Date</div>
                </div>
            </div>
        </div>
    </section>

    <script>
        window.print()
    </script>
@endsection