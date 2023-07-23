@extends('layouts.app')

@section('title', 'Print Cleared Forms')

@section('content')
    @foreach ($transactions as $key2 => $transaction)
        <?php $config_confidential = 0; ?>
        <?php 
            $config_confidential2 = false;
            // check levels
            if (Auth::user()->ualevel->code < $transaction->owner->ualevel->code) $config_confidential2 = true;
            // check level parallel confidential
            if (Auth::user()->ualevel->code == $transaction->owner->ualevel->code && $transaction->is_confidential && Auth::user()->id != $transaction->owner->id) $config_confidential2 = true;
            // check level own confidential
            if ($transaction->is_confidential_own && Auth::user()->id != $transaction->owner->id) $config_confidential2 = true;
        ?>

        @if (!$config_confidential2)   
            <section class="content" style="page-break-before: always">
                <div class="container-fluid">
                    <div class="float-right">Date Generated: <b>{{ Carbon\Carbon::now() }}</b></div>
                    <h2>{{ strtoupper($transaction->trans_type) }} Liquidation</h2>
                    <div class="row">
                        <div class="col-6">
                            <img src="/storage/public/images/companies/{{ $transaction->project->company->logo }}" alt="" class="thumb--sm">
                        </div>
                        <div class="col-6">
                            <h3 class="text-right mt-3">{{ $transaction->project->company->name }}</h3>
                        </div>
                        <div class="col-12">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Request No.</th>
                                        <th>Date Requested</th>
                                        <th>Staff</th>
                                        <th>Category / Class</th>
                                        @if ($transaction->is_deposit
                                            || $transaction->is_bills
                                            || $transaction->is_hr
                                            || $transaction->is_bank
                                            || in_array($transaction->status_id, config('global.cancelled')))
                                            <th>Status</th>
                                        @endif
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
                                        <td>{{ $transaction->coatagging->name }}</td>
                                        @if ($transaction->is_deposit
                                            || $transaction->is_bills
                                            || $transaction->is_hr
                                            || $transaction->is_bank
                                            || in_array($transaction->status_id, config('global.cancelled')))
                                            <td>{{ $transaction->status->name }}</td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <td class="font-weight-bold">Project</td>
                                            <td>{{ $transaction->project->project }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Vendor / Payee</td>
                                            <td>{{ $transaction->payee }}</td>
                                        </tr>
                                        @if (1==0)
                                            <tr>
                                                <td class="font-weight-bold">Particulars</td>
                                                <td>{{ $transaction->particulars_custom ?: $transaction->particulars->name }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td class="font-weight-bold">Purpose</td>
                                            <td>
                                                @if ($config_confidential)
                                                    -
                                                @else
                                                    {{ $transaction->purpose }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Due Date</td>
                                            <td>{{ $transaction->due_at }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Payor</td>
                                            <td>{{ $transaction->payor ?: 'n/a' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Transaction Category</td>
                                            <td>
                                                @if ($transaction->is_deposit)
                                                    {{ config('global.trans_category_label')[1] }}
                                                @elseif ($transaction->is_bills)    
                                                    {{ config('global.trans_category_label')[2] }}
                                                @elseif ($transaction->is_hr)    
                                                    {{ config('global.trans_category_label')[3] }}
                                                @elseif ($transaction->is_bank)    
                                                    {{ config('global.trans_category_label')[5] }}
                                                @else
                                                    {{ config('global.trans_category_label')[0] }}    
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Tax Type</td>
                                            <td>{{ $transaction->form_vat_name ? $transaction->form_vat_name : $transaction->vattype->name }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <td class="font-weight-bold">Cost Control No.</td>
                                            <td>{{ $transaction->cost_control_no ?: '-' }}</td>
                                        </tr>
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
                                        @if ($transaction->is_bank)
                                            <tr>
                                                <td class="font-weight-bold">Released By</td>
                                                <td>{{ $transaction->releasedby->name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Transferred To</td>
                                                <td>{{ $transaction->formcompany->name }}</td>
                                            </tr>
                                        @endif
                                        @if ($transaction->form_service_charge && $transaction->form_service_charge > 0)
                                            <tr>
                                                <td class="font-weight-bold">Service Charge</td>
                                                <td>
                                                    @if ($config_confidential)
                                                        -
                                                    @else
                                                        {{ number_format($transaction->form_service_charge, 2, '.', ',') }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($transaction->is_bank)
                                            <tr>
                                                <td class="font-weight-bold">Amount / FX Rate</td>
                                                <td>
                                                    @if ($config_confidential)
                                                        -
                                                    @else
                                                        {{ $transaction->currency }} {{ number_format($transaction->amount, 2, '.', ',') }}
                                                        <span class="small px-2 vlign--top">x</span>
                                                        {{ number_format($transaction->currency_2_rate, 2, '.', ',') }}
                                                        ({{ $transaction->currency_2 }})
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td class="font-weight-bold">{{ $transaction->is_bank ? 'Transferred' : 'Released' }} Amount</td>
                                            <td>
                                                @if ($config_confidential)
                                                    -
                                                @else
                                                    {{ $transaction->currency_2 ?: $transaction->currency }} {{ number_format($transaction->amount_issued, 2, '.', ',') }}
                                                @endif
                                            </td>
                                        </tr>                                    
                                    </table>
                                </div>
                            </div>
                        </div>  
                    </div>
                    <div class="row row--print">
                        @if (!$transaction->is_deposit && !$transaction->is_hr && !$transaction->is_bank)
                            <div class="col-12 {{ $config_confidential ? 'd-none' : '' }}">
                                <table class="table table-sm mb-0">
                                    <tr class="font-weight-bold">
                                        <td>Pos.</td>
                                        <td>Date</td>
                                        <td>Project</td>
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
                                            <td>{{ $item->project_id ? $item->project->project : 'n/a' }}</td>
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
                                        </tr>
                                    </thead> 
                                    <tbody>
                                        <tr>
                                            <td>{{ $transaction->depo_date }}</td>
                                            <td>{{ $transaction->depo_type }}</td>
                                            <td>{{ $transaction->bankbranch ? $transaction->bankbranch->bank->name.' ('.$transaction->bankbranch->name.')' : '' }}</td>
                                            <td>{{ $transaction->depo_ref }}</td>
                                        </tr>
                                    </tbody>   
                                </table>
                            </div>
                        @endif
                    </div>
                    <div class="row row--print {{ $config_confidential ? 'd-none' : '' }}">
                        <div class="col-12">
                            <table class="table table-sm">
                                <tr><td colspan="7" class="py-3"></td></tr>
                                @foreach ($transactions_summary[$key2] as $index => $item)
                                    <tr>
                                        <td colspan="2"class="{{ $index == 0 ? 'font-weight-bold text-right pr-5' : '' }}">{{ $index == 0 ? 'Type' : '' }}</td>
                                        <td colspan="5">
                                            {{ $item->name }}
                                            <span class="float-right">{{ number_format($item->amount, 2, '.', ',') }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="{{ count($transactions_summary_proj[$key2]) == 0 ? 'd-none' : '' }}"><td colspan="7" class="py-3"></td></tr>
                                @foreach ($transactions_summary_proj[$key2] as $index => $item)
                                    <tr>
                                        <td colspan="2"class="{{ $index == 0 ? 'font-weight-bold text-right pr-5' : '' }}">{{ $index == 0 ? 'Project' : '' }}</td>
                                        <td colspan="5">
                                            {{ $item->project }}
                                            <span class="float-right">{{ number_format($item->amount, 2, '.', ',') }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr><td colspan="7" class="py-3"></td></tr>
                                @if ($transaction->liq_before_vat)
                                    <tr>
                                        <td colspan="4" class="font-weight-bold small text-right">Before VAT</td>
                                        <td></td>
                                        <td colspan="2" class="bg-white text-right">
                                            <span class="float-left">{{ $transaction->currency_2 ?: $transaction->currency }}</span>
                                            {{ number_format($transaction->liq_before_vat, 2, '.', ',') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="font-weight-bold small text-right">VAT (12%)</td>
                                        <td></td>
                                        <td colspan="2" class="bg-white text-right font-italic">
                                            <span class="float-left">{{ $transaction->currency_2 ?: $transaction->currency }}</span>
                                            {{ number_format($transaction->liq_vat, 2, '.', ',') }}
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="4" class="font-weight-bold small text-right">Subtotal</td>
                                    <td></td>
                                    <td colspan="2" class="bg-white text-right font-weight-bold">
                                        <span class="float-left">{{ $transaction->currency_2 ?: $transaction->currency }}</span>
                                        {{ number_format($transaction->liq_subtotal, 2, '.', ',') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="font-weight-bold small text-right">Less: Deposit/Payment</td>
                                    <td></td>
                                    <td colspan="2" class="bg-white text-right text-danger">
                                        <span class="float-left">{{ $transaction->currency_2 ?: $transaction->currency }}</span>
                                        {{ number_format($transaction->amount_issued, 2, '.', ',') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="small font-weight-bold text-right">Balance</td>
                                    <td></td>
                                    <td colspan="2" class="bg-white text-right font-weight-bold">
                                        <span class="float-left">{{ $transaction->currency_2 ?: $transaction->currency }}</span>
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
                            @if (!$transaction->is_bills && !$transaction->is_hr)
                                @if ($transaction->liq_balance != 0)
                                    <table class="table table-sm">
                                        <tr>
                                            <td>Amount {{ $transaction->liq_balance >= 0 ? 'Reimbursed' : 'Returned' }}</td>
                                            <td class="font-weight-bold">{{ $transaction->currency_2 ?: $transaction->currency }} {{ $transaction->liq_balance < 0 ? number_format($transaction->liq_balance*-1, 2, '.', ',') : $transaction->liq_balance }}</td>
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
                                            <td>Received By</td>
                                            <td class="font-weight-bold">{{ $transaction->depo_received_by }}</td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td class="font-weight-bold">{{ $transaction->status->name }}</td>
                                        </tr>
                                    </table>
                                @endif
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
                        @if (!$transaction->is_deposit && !$transaction->is_bills && !$transaction->is_hr)
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
        @endif
    @endforeach

    <script>
        window.print()
    </script>
@endsection