@extends('layouts.app')

@section('title', 'Print Issued Forms')

@section('content')
    @foreach ($transactions as $transaction)
        <section class="content" style="page-break-before: always">
            <div class="container-fluid">
                <div class="float-right">Date Generated: <b>{{ Carbon\Carbon::now() }}</b></div>
                {{-- <h1>{{ $transaction->is_deposit ? 'For Deposit' : $trans_page }} - Form</h1> --}}
                <div class="row row--print w-100">
                    <div class="col-6">
                        <img src="/storage/public/images/companies/{{ $transaction->project->company->logo }}" alt="" class="thumb--sm">
                    </div>
                    <div class="col-6">
                        <h3 class="text-right mt-3">{{ $transaction->project->company->name }}</h3>
                    </div>
                </div>
                <div class="row row--print">
                    <div class="col-12">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Request No.</th>
                                    <th>Date Requested</th>
                                    <th>Project</th>
                                    <th>COA Tagging</th>
                                    <th>Status</th>
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
                                    <td>{{ $transaction->project->project }}</td>
                                    <td>{{ $transaction->coatagging->name }}</td>
                                    <td>{{ $transaction->status->name }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td class="font-weight-bold">Purpose</td>
                                        <td>{{ $transaction->purpose }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Vendor</td>
                                        <td>{{ $transaction->payee }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Payor</td>
                                        <td>{{ $transaction->payor ?: 'n/a' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-6">
                                <table class="table table-sm">
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
                        </div>
                        @if ($transaction->is_bank && $transaction->form_company_id)
                            <div class="row">
                                <div class="col-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <td class="font-weight-bold">Transfer to</td>
                                            <td>{{ $transaction->formcompany->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Type</td>
                                            <td>{{ $transaction->control_type }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">No.</td>
                                            <td>{{ $transaction->control_no }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <td class="font-weight-bold">Service Charge</td>
                                            <td>{{ number_format($transaction->form_service_charge, 2, '.', ',') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Amount / FX Rate</td>
                                            <td>
                                                {{ $transaction->currency }} {{ number_format($transaction->amount, 2, '.', ',') }}
                                                <span class="small px-2 vlign--top">x</span>
                                                {{ number_format($transaction->currency_2_rate, 2, '.', ',') }}
                                                ({{ $transaction->currency_2 }})
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Transferred Amount</td>
                                            <td>{{ $transaction->currency_2 ?: $transaction->currency }} {{ number_format($transaction->amount_issued, 2, '.', ',') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>        
                </div>
                <div class="row row--print">                
                    <div class="col-12">
                        <table class="table table-sm">
                            <tr>
                                <td colspan="6" class="py-3"></td>
                            </tr>
                            <tr class="font-weight-bold">
                                <td>Item No.</td>                            
                                <th>Qty</th>
                                <th>Description</th>
                                <th>Particulars</th>
                                <th class="text-right">Unit Price</th>
                                <th class="text-right">Total</th>
                            </tr>
                            @foreach ($transaction->transaction_description as $key => $item_desc)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item_desc->qty }}</td>
                                    <td>{{ $item_desc->description }}</td>
                                    <td>{{ $item_desc->particulars->name }}</td>
                                    <td class="text-right">{{ number_format($item_desc->amount, 2, '.', ',') }}</td>
                                    <td class="text-right">{{ number_format($item_desc->amount * $item_desc->qty, 2, '.', ',') }}</td>
                                </tr>
                            @endforeach
                            <tr class="font-weight-bold">
                                <td colspan="2"></td>
                                <td>{{ $transaction->form_amount_vat + $transaction->form_amount_wht == 0 ? 'Total' : 'Subtotal' }}</td>
                                <td colspan="2"></td>
                                <td class="text-right"><span class="float-left">{{ $transaction->currency }}</span> {{ number_format($transaction->form_amount_subtotal, 2, '.', ',') }}</td>
                            </tr>
                            @if ($transaction->form_amount_vat)
                            <tr>
                                <td colspan="2"></td>
                                <td>VAT</td>
                                <td colspan="2"></td>
                                <td class="text-right"><span class="float-left">{{ $transaction->currency }}</span> {{ number_format($transaction->form_amount_vat, 2, '.', ',') }}</td>
                            </tr>
                            @endif
                            @if ($transaction->form_vat_wht)
                            <tr>
                                <td colspan="2"></td>
                                <td>Less Withholding Tax</td>
                                <td>{{ $transaction->form_vat_name }}</td>
                                <td>{{ $transaction->form_vat_wht }}%</td>
                                <td class="text-right"><span class="float-left">{{ $transaction->currency }}</span> ({{ number_format($transaction->form_amount_wht, 2, '.', ',') }})</td>
                            </tr>
                            @endif
                            @if ($transaction->form_amount_payable)
                            <tr class="font-weight-bold">
                                <td colspan="2"></td>
                                <td class="font-weight-bold">Total Payable</td>
                                <td colspan="2"></td>
                                <td class="text-right"><span class="float-left">{{ $transaction->currency }}</span> {{ number_format($transaction->form_amount_payable, 2, '.', ',') }}</td>
                            </tr>
                            @endif
                            <tr class="font-weight-bold border-top-2">
                                <td colspan="2"></td>
                                <td>Amount</td>
                                <td colspan="2"></td>
                                <td class="text-right font-weight-bold"><span class="float-left">{{ $transaction->currency }}</span> {{ number_format($transaction->form_amount_payable, 2, '.', ',') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row row--print">
                    <div class="col-6">
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
                        </table>
                    </div>
                    <div class="col-6">
                        <table class="table table-sm">
                            <tr>
                                <td>{{ $transaction->is_deposit ? 'Issued By' : 'Released By' }}</td>
                                <td>{{ $transaction->released_by_id ? $transaction->releasedby->name : '' }}</td>
                            </tr>
                            <tr>
                                <td>Bank/Check#</td>
                                <td>{{ $transaction->control_no }}</td>
                            </tr>
                            <tr>
                                <td>{{ $transaction->is_deposit ? 'Date Deposited' : 'Date Released' }}</td>
                                <td>{{ $transaction->released_at }}</td>
                            </tr>
                            <tr>
                                <td>For Liquidation</td>
                                <td>Yes</td>
                            </tr>
                            <tr>
                                <td>OR Required</td>
                                <td>Yes</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row row--print mt-3">
                    <div class="col-3 small text-center my-4">
                        <h5>{{ $transaction->owner->name }}</h5>
                        <div class="mt-2 pt-2 border-top font-weight-bold">Prepared By</div>
                    </div>
                    <div class="col-3 small text-center my-4">
                        <h5>{{ $transaction->requested->name }}</h5>
                        <div class="mt-2 pt-2 border-top font-weight-bold">Requested By</div>
                    </div>
                    <div class="col-3 small text-center my-4 {{ $transaction->form_approver_id ? '' : 'd-none' }}">
                        <h5>{{ $transaction->form_approver_id ? $transaction->formapprover->name : '' }}</h5>
                        <div class="mt-2 pt-2 border-top font-weight-bold">Approver</div>
                    </div>
                    <div class="col-3 small text-center my-4">
                        <h5>{{ $final_approver }}</h5>
                        <div class="mt-2 pt-2 border-top font-weight-bold">Authorized By</div>
                    </div>

                    <div class="col-3 small text-center my-4">
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