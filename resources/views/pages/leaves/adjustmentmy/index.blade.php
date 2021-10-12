@extends('layouts.app-leaves')

@section('title', 'My Leave Adjustment')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-9 mb-2">
                    <h1>My Leave Adjustment</h1>
                </div>
                <div class="col-sm-3">
                    <table class="table table-sm">
                        <tr>
                            <td class="border-top-0">Current Year</td>
                            <td class="border-top-0 text-right">{{ Auth::user()->leavesadjustmenttotal->adjustment }}</td>
                        </tr>
                        <tr>
                            <td>Previous Year</td>
                            <td class="text-right">{{ Auth::user()->leavesadjustmenttotalpast->adjustment }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th class="text-center">Quantity</th>
                        <th>Remarks</th>
                        <th class="text-nowrap">Created By</th>
                        <th class="text-nowrap">Updated By</th>
                        <th class="text-nowrap">Date Created</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse (Auth::user()->leavesadjustment->sortByDesc('created_at') as $item)
                        <tr>
                            <td class="text-nowrap">{{ $item->user->name }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td>{{ $item->remarks }}</td>
                            <td class="text-nowrap">{{ $item->owner->name }}</td>
                            <td class="text-nowrap">{{ $item->updatedby->name }}</td>
                            <td class="text-nowrap">{{ Carbon::parse($item->created_at)->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('messages.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection