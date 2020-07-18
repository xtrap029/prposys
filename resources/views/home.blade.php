@extends('layouts.app')

@section('content')
<section class="content-header pb-0">
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card card-widget widget-user">
                    <div class="widget-user-header bg-maroon">
                        <h3 class="widget-user-username">{{ $user->name }}</h3>
                        <h5 class="widget-user-desc">{{ $user->role->name }}</h5>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle elevation-2" src="/storage/public/images/users/{{ $user->avatar }}" alt="User Avatar">
                    </div>
                    <div class="card-footer bg-white">
                        <div class="row">
                            <div class="col-sm-6 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">Email</h5>
                                    <span>{{ $user->email }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="description-block">
                                    <h5 class="description-header">Joined</h5>
                                    <span>{{ Carbon::parse($user->created_at)->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 d-flex">
                <div class="card jsInspire w-100">
                    <div class="card-body h-100 d-table">
                        <div class="text-white d-table-cell vlign--middle text-center">
                            <h5 class="jsInspire_quote"></h5>
                            <h6 class="jsInspire_author"></h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-orange p-3">
                    <span class="info-box-icon"><i class="material-icons" style="font-size:45px">payments</i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Unliquidated Amount</span>
                        <span class="info-box-number">P{{ number_format($unliquidated_bal['used_amount'], 2, '.', ',') }}</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $unliquidated_bal['percentage_amount'] }}%"></div>
                        </div>
                        <span class="progress-description">
                            with P{{ number_format($unliquidated_bal['limit_amount'], 2, '.', ',') }} limit
                        </span>
                    </div>
                </div>
                <div class="info-box bg-warning p-3">
                    <span class="info-box-icon"><i class="material-icons" style="font-size:45px">format_list_numbered</i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Unliquidated Transactions</span>
                        <span class="info-box-number">{{ $unliquidated_bal['used_count'] }} transactions</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $unliquidated_bal['percentage_count'] }}%"></div>
                        </div>
                        <span class="progress-description">
                            with {{ $unliquidated_bal['limit_count'] }} transactions limit
                        </span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Unliquidated Transactions</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="material-icons">minimize</i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped small m-0">
                            <thead>
                                <tr>
                                    <th>Transaction</th>
                                    <th>Status</th>
                                    <th>Last Update</th>
                                </tr>
                            </thead>
                            @foreach ($unliquidated as $item)
                                <tr>
                                    <td>{{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}</td>
                                    <td>{{ $item->status->name }}</td>
                                    <td>{{ Carbon::parse($item->updated_at)->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-4 col-md-2 my-3 border-right">
                        <div class="description-block">
                            <h5 class="description-header text-danger">{{ $stats['cancelled'] }}</h5>
                            <span class="description-text">Cancelled</span>
                        </div>
                    </div>
                    <div class="col-4 col-md-2 my-3 border-right">
                        <div class="description-block">
                            <h5 class="description-header text-orange">{{ $stats['generated'] }}</h5>
                            <span class="description-text">Generated</span>
                        </div>
                    </div>
                    <div class="col-4 col-md-2 my-3 border-right">
                        <div class="description-block">
                            <h5 class="description-header text-orange">{{ $stats['forms'] }}</h5>
                            <span class="description-text">Forms</span>
                        </div>
                    </div>
                    <div class="col-4 col-md-2 my-3 border-right">
                        <div class="description-block">
                            <h5 class="description-header text-primary">{{ $stats['issued'] }}</h5>
                            <span class="description-text">Issued</span>
                        </div>
                    </div>
                    <div class="col-4 col-md-2 my-3 border-right">
                        <div class="description-block">
                            <h5 class="description-header text-primary">{{ $stats['liquidation'] }}</h5>
                            <span class="description-text">Liquidations</span>
                        </div>
                    </div>
                    <div class="col-4 col-md-2 my-3">
                        <div class="description-block">
                            <h5 class="description-header text-success">{{ $stats['cleared'] }}</h5>
                            <span class="description-text">Cleared</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Recent Transactions Requested</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="material-icons">minimize</i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped small m-0">
                                    <thead>
                                        <tr>
                                            <th>Transaction</th>
                                            <th>Status</th>
                                            <th>Last Update</th>
                                        </tr>
                                    </thead>
                                    @foreach ($requested as $item)
                                        <tr>
                                            <td>{{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}</td>
                                            <td>{{ $item->status->name }}</td>
                                            <td>{{ Carbon::parse($item->updated_at)->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Recent Transactions Prepared</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="material-icons">minimize</i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped small m-0">
                                    <thead>
                                        <tr>
                                            <th>Transaction</th>
                                            <th>Status</th>
                                            <th>Last Update</th>
                                        </tr>
                                    </thead>
                                    @foreach ($prepared as $item)
                                        <tr>
                                            <td>{{ strtoupper($item->trans_type) }}-{{ $item->trans_year }}-{{ sprintf('%05d',$item->trans_seq) }}</td>
                                            <td>{{ $item->status->name }}</td>
                                            <td>{{ Carbon::parse($item->updated_at)->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Recent Activities</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="material-icons">minimize</i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-reponsive table-striped small m-0">
                                    <tbody>
                                        @foreach ($logs as $item)
                                            <tr>
                                                <td>
                                                    <a href="#_" data-toggle="modal" data-target="#modal-{{ $item->id }}">{{ strtoupper($item->description) }}</a>
                                                    <div class="modal fade" id="modal-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header border-0">
                                                                    <h5 class="modal-title">{{ ucfirst($item->description) }} {{ Carbon::parse($item->created_at)->diffForHumans() }}</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @switch($item->description)
                                                                        @case('created')
                                                                            <table class="table table-sm table-bordered">
                                                                                <thead class="bg-gradient-gray">
                                                                                    <tr>
                                                                                        <th></th>
                                                                                        <th>Value</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach ($item->changes['attributes'] as $key => $attribute)
                                                                                        <tr>
                                                                                            <td class="font-weight-bold">{{ ucwords($key) }}</td>
                                                                                            <td>{{ $attribute }}</td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                            @break
                                                                        @case('updated')
                                                                            <table class="table table-sm table-bordered">
                                                                                <thead class="bg-gradient-gray">
                                                                                    <tr>
                                                                                        <th></th>
                                                                                        <th>From</th>
                                                                                        <th>To</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach ($item->changes['old'] as $key => $attribute)
                                                                                        <tr>
                                                                                            <td class="font-weight-bold">{{ ucwords($key) }}</td>
                                                                                            <td>{{ $attribute }}</td>
                                                                                            <td>{{ $item->changes['attributes'][$key] }}</td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                            @break
                                                                        @default                                                    
                                                                    @endswitch
                                                                </div>
                                                                <div class="modal-footer border-0">
                                                                    @if (in_array($item->description, ['created', 'updated']))
                                                                        @switch($item->subject_type)
                                                                            @case('App\Bank')                           <?php $view_url = '/bank/'.$item->subject_id.'/edit'; ?> @break
                                                                            @case('App\CoaTagging')                     <?php $view_url = '/coa-tagging/'.$item->subject_id.'/edit'; ?> @break
                                                                            @case('App\Company')                        <?php $view_url = '/company/'.$item->subject_id.'/edit'; ?> @break
                                                                            @case('App\CompanyProject')                 <?php $view_url = '/company-project/edit/'.$item->subject_id; ?> @break
                                                                            @case('App\ExpenseType')                    <?php $view_url = '/expense-type/'.$item->subject_id.'/edit'; ?> @break
                                                                            @case('App\Particular')                     <?php $view_url = '/particular/'.$item->subject_id.'/edit'; ?> @break
                                                                            @case('App\Role')                           <?php $view_url = '/role/'.$item->subject_id.'/edit'; ?> @break
                                                                            @case('App\Settings')                       <?php $view_url = '/settings'; ?> @break
                                                                            @case('App\item')                    <?php $view_url = '/transaction/view/'.$item->subject_id; ?> @break
                                                                            @case('App\TransactionsAttachment')         <?php $view_url = '/transaction-liquidation/finder-attachment/'.$item->subject_id; ?> @break
                                                                            @case('App\TransactionsLiquidation')        <?php $view_url = '/transaction-liquidation/finder-liquidation/'.$item->subject_id; ?> @break
                                                                            @case('App\User')                           <?php $view_url = '/user/'.$item->subject_id.'/edit'; ?> @break
                                                                            @case('App\VatType')                        <?php $view_url = '/vat-type/'.$item->subject_id.'/edit'; ?> @break
                                                                            @default                                                        
                                                                        @endswitch
                                                                    <a href="{{ !empty($view_url) ? $view_url : '#' }}" class="btn btn-primary" target="_blank">View Item</a>
                                                                    @endif
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $item->log_name }}</td>
                                                <td class="text-right">{{ Carbon::parse($item->created_at)->diffForHumans() }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
    <script type="text/javascript">
        $(function() {
            $.ajax({
                url: "https://quotes.rest/qod?language=en",
                context: document.body
            }).done(function(response) {
                quote = response.contents.quotes[0]
                item = '.jsInspire'

                $(item).css({
                    "background-image":"url("+quote.background+")",
                    "background-size":"cover",
                    "background-position":"center",
                })

                $(item+'_quote').text(quote.quote)
                $(item+'_author').text(quote.author)
            })
        })
    </script>
@endsection