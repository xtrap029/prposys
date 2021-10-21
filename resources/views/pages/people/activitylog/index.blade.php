@extends('layouts.app-people')

@section('title', 'Activity Log')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1>Activity Log</h1>
                </div>
                <div class="col-sm-4 mt-2 mt-sm-0">
                    <form method="get">
                        <select name="log_name" class="form-control" onchange="this.form.submit()">
                            <option value="">All</option>
                            @foreach ($log_name as $item)
                                <option value="{{ $item->log_name }}" {{ app('request')->input('log_name') == $item->log_name ? 'selected' : '' }}>{{ $item->log_name }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <table class="table table-striped table-responsive-sm">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Page</th>
                        <th>User</th>
                        <th>Date</th>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activity_logs as $item)
                        <tr>
                            <td class="text-nowrap">{{ ucfirst($item->description) }}</td>
                            <td class="text-nowrap">{{ $item->log_name }}</td>
                            <td class="text-nowrap">{{ $item->causer->name }}</td>
                            <td class="text-nowrap">{{ Carbon::parse($item->created_at)->diffInDays(Carbon::now()) >= 1 ? $item->created_at->format('Y-m-d') : $item->created_at->diffForHumans() }}</td>
                            <td class="text-right text-nowrap">
                                @if ($item->is_confidential && !Auth::user()->is_smt)
                                @else                                
                                    <a href="#_" data-toggle="modal" data-target="#modal-{{ $item->id }}"><small>More info</small></a>
                                    <div class="modal fade" id="modal-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title">{{ ucfirst($item->description) }} - {{ $item->log_name }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="table-responsive">
                                                        @switch($item->description)
                                                            @case('created')
                                                            @case('deleted')
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
                                                </div>
                                                <div class="modal-footer border-0">
                                                    @if (in_array($item->description, ['created', 'updated']))
                                                        @switch($item->subject_type)
                                                            @case('App\Bank')                           <?php $view_url = '/bank/'.$item->subject_id.'/edit'; ?> @break
                                                            @case('App\BankBranch')                     <?php $view_url = '/bank-branch/edit/'.$item->subject_id; ?> @break
                                                            @case('App\CoaTagging')                     <?php $view_url = '/coa-tagging/'.$item->subject_id.'/edit'; ?> @break
                                                            @case('App\Company')                        <?php $view_url = '/company/'.$item->subject_id.'/edit'; ?> @break
                                                            @case('App\CompanyProject')                 <?php $view_url = '/company-project/edit/'.$item->subject_id; ?> @break
                                                            @case('App\ExpenseType')                    <?php $view_url = '/expense-type/'.$item->subject_id.'/edit'; ?> @break
                                                            @case('App\Particular')                     <?php $view_url = '/particular/'.$item->subject_id.'/edit'; ?> @break
                                                            @case('App\ReleasedBy')                     <?php $view_url = '/released-by/'.$item->subject_id.'/edit'; ?> @break
                                                            @case('App\Role')                           <?php $view_url = '/role/'.$item->subject_id.'/edit'; ?> @break
                                                            @case('App\Transaction')                    <?php $view_url = '/transaction/view/'.$item->subject_id; ?> @break
                                                            @case('App\TransactionsAttachment')         <?php $view_url = '/transaction-liquidation/finder-attachment/'.$item->subject_id; ?> @break
                                                            @case('App\TransactionsDescription')        <?php $view_url = '#'; ?> @break
                                                            @case('App\TransactionsLiquidation')        <?php $view_url = '/transaction-liquidation/finder-liquidation/'.$item->subject_id; ?> @break
                                                            @case('App\VatType')                        <?php $view_url = '/vat-type/'.$item->subject_id.'/edit'; ?> @break
                                                            @case('App\User')                           <?php $view_url = '/user/'.$item->subject_id.'/edit'; ?> @break
                                                            @case('App\Settings')                       <?php $view_url = '/people-settings'; ?> @break
                                                            @case('App\LeaveReason')                    <?php $view_url = '/leaves-reason'; ?> @break
                                                            @case('App\Department')                     <?php $view_url = '/leaves-department'; ?> @break
                                                            @case('App\UaLevelRoute')                   <?php $view_url = '/ua-level-route'; ?> @break
                                                            @case('App\UaLevel')                        <?php $view_url = '/ua-level'; ?> @break
                                                            @case('App\UaRoute')                        <?php $view_url = '/ua-route'; ?> @break
                                                            @default                                                        
                                                        @endswitch
                                                    <a href="{{ !empty($view_url) ? $view_url : '#' }}" class="btn btn-primary" target="_blank">View Item</a>
                                                    @endif
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center">{{ __('messages.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="overflow-auto position-relative text-center">
                <div class="d-inline-block">
                    {{ $activity_logs->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection