@extends('layouts.app')

@section('title', 'Print Cancelled')

@section('content')
    <?php $config_confidential = 0; ?>
    <section class="content">
        <div class="container-fluid">
            <div class="float-right">Date Generated: <b>{{ Carbon\Carbon::now() }}</b></div>
            <h2>
                Print Cancelled
            </h2>
            <div class="row row--print">
                <div class="col-10">
                    <h3 class="mt-4">{{ $transaction->project->company->name }}</h3>
                </div>
                <div class="col-2 text-right">
                    <img src="/storage/public/images/companies/{{ $transaction->project->company->logo }}" alt="" class="thumb--sm">
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
                                <th>Category / Class</th>
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
                                    <td>
                                        @if ($config_confidential)
                                            -
                                        @else
                                            {{ $transaction->purpose }}
                                        @endif
                                    </td>
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
                                <tr>
                                    <td class="font-weight-bold">Cancellation Reason</td>
                                    <td>{{ $transaction->cancellation_reason }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
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
                    <h5>{{ Carbon\Carbon::now()->toDateString() }}</h5>
                    <div class="mt-2 pt-2 border-top font-weight-bold">Date</div>
                </div>
            </div>
        </div>
    </section>
    <script>
        window.print()
    </script>
@endsection

@section('footer')
    {{-- <div class="watermark {{ !in_array($transaction->status_id, config('global.cancelled')) ? 'd-none' : '' }}">CANCELLED</div> --}}
@endsection 