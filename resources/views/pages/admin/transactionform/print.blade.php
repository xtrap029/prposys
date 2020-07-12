@extends('layouts.app')

@section('title', 'Print Form')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="float-right">Date Generated: <b>{{ Carbon\Carbon::now() }}</b></div>
            <h1>{{ $trans_page }} - Form</h1>
            <div class="text-center mt-3 mb-5">
                <img src="/storage/public/images/companies/{{ $transaction->project->company->logo }}" alt="" class="thumb--sm">
                <h2 class="mt-2">{{ $transaction->project->company->name }}</h2>
            </div>
            <div class="row">
                <div class="col-4">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Project</th>
                                <th>COA Tagging</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $transaction->project->project }}</td>
                                <td>{{ $transaction->coatagging->name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>          
                <div class="col-8">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Purpose</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $transaction->purpose }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div> 
                
                <div class="col-12 my-5">
                    <table class="table">
                        <tr>
                            <td class="font-weight-bold">Due Date</td>
                            <td>{{ $transaction->due_at }}</td>
                            <td class="font-weight-bold">Vendor</td>
                            <td class="font-weight-bold">{{ $transaction->payee }}</td>
                            <td class="font-weight-bold">Request No.</td>
                            <td class="font-weight-bold">{{ strtoupper($transaction->trans_type) }}-{{ $transaction->trans_year }}-{{ sprintf('%05d',$transaction->trans_seq) }}</td>
                        </tr>
                        <tr>
                            <td colspan="6" class="my-5 py-5"></td>
                        </tr>
                        <tr class="font-weight-bold">
                            <td>Item Number</td>
                            <td class="text-center">Qty</td>
                            <td>Description</td>
                            <td colspan="2">Particulars</td>
                            <td class="text-right">Total</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center">1</td>
                            <td>{{ $transaction->expensetype->name }}</td>
                            <td colspan="2">{{ $transaction->trans_type != 'pc' ? $transaction->particulars->name : $transaction->particulars_custom }}</td>
                            <td class="text-right">
                                <span class="float-left">{{ $transaction->currency }}</span>
                                {{ number_format($transaction->form_amount_unit, 2, '.', ',') }}
                            </td>
                        </tr>
                        <tr class="font-weight-bold">
                            <td colspan="2"></td>
                            <td>{{ $transaction->form_amount_vat + $transaction->form_amount_wht == 0 ? 'Total' : 'Subtotal' }}</td>
                            <td colspan="2"></td>
                            <td class="text-right"><span class="float-left">{{ $transaction->currency }}</span> {{ number_format($transaction->form_amount_subtotal, 2, '.', ',') }}</td>
                        </tr>
                        <tr>
                            <td colspan="6" class="my-5 py-5"></td>
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
                        <tr>
                            <td colspan="6" class="my-5 py-5"></td>
                        </tr>
                        <tr class="font-weight-bold border-top-2">
                            <td colspan="2"></td>
                            <td>Amount</td>
                            <td colspan="2"></td>
                            <td class="text-right font-weight-bold"><span class="float-left">{{ $transaction->currency }}</span> {{ number_format($transaction->form_amount_payable, 2, '.', ',') }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-3 text-center mt-5">
                    <div>{{ $transaction->owner->name }}</div>
                    <div class="mt-2 pt-2 border-top font-weight-bold">Prepared By</div>
                </div>
                <div class="col-3 text-center mt-5">
                    <div>{{ Carbon\Carbon::now()->toDateString() }}</div>
                    <div class="mt-2 pt-2 border-top font-weight-bold">Date</div>
                </div>
                <div class="col-3 text-center mt-5">
                    <div>{{ $transaction->formapprover->name }}</div>
                    <div class="mt-2 pt-2 border-top font-weight-bold">Authorized By</div>
                </div>
                <div class="col-3 text-center mt-5">
                    <div>{{ Carbon\Carbon::now()->toDateString() }}</div>
                    <div class="mt-2 pt-2 border-top font-weight-bold">Date</div>
                </div>
            </div>
        </div>
    </section>

    <script>
        window.print()
    </script>
@endsection