@extends('layouts.app-leaves')

@section('title', 'Leave Adjustment')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Leave Adjustment</h1>
                </div>
                <form method="get" class="col-md-6 mt-3 mt-md-0">
                    <select name="user_id" class="form-control w-auto border-0 bg-transparent font-weight-bold outline-0 float-right" onchange="this.form.submit()">
                        <option value="">All Users</option>
                        @foreach ($users as $item)
                            <option value="{{ $item->id }}" {{ app('request')->input('user_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </form>
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
                        <th class="text-right"><a href="/leaves-adjustment/create">Create</a></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($adjustments as $item)
                        <tr>
                            <td class="text-nowrap">{{ $item->user->name }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td>{{ $item->remarks }}</td>
                            <td class="text-nowrap">{{ $item->owner->name }}</td>
                            <td class="text-nowrap">{{ $item->updatedby->name }}</td>
                            <td class="text-nowrap">{{ Carbon::parse($item->created_at)->format('Y-m-d') }}</td>
                            <td class="text-right text-nowrap">
                                <a href="/leaves-adjustment/{{ $item->id }}/edit" class="btn btn-link btn-sm">Edit</a>
                                <form action="/leaves-adjustment/{{ $item->id }}" method="post" class="d-inline-block">
                                    @csrf
                                    @method('delete')
                                    <input type="submit" class="btn btn-link btn-sm" value="Delete" onclick="return confirm('Are you sure?')">
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">{{ __('messages.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="overflow-auto position-relative text-center">
                <div class="d-inline-block">
                    {{ $adjustments->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection