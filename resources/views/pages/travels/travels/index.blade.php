@extends('layouts.app-travels')

@section('title', 'Travels')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Travels</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/travels" method="GET" class="row">
                <div class="mb-2 col-6 col-md-2 col-xl-1">
                    <a href="/travels/create" class="btn btn-success btn-block p-0"><i class="material-icons mt-1 text-white">add</i></a>
                </div> 
                <div class="mb-2 col-xl-1">
                    <input type="number" class="form-control" step="1" name="id" value="{{ app('request')->input('id') }}" placeholder="ID">
                </div>
                <div class="mb-2 col-md-6 col-xl-2">
                    <select name="company_project_id" class="form-control">
                        <option value="">Project</option>
                        @foreach ($projects->sortBy('company.name') as $item)
                            <option value="{{ $item->id }}" {{ app('request')->input('company_project_id') == $item->id ? 'selected' : '' }}>{{ strtoupper($item->company->name).' - '.$item->project }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2 col-md-4 col-xl-2">
                    <input type="text" class="form-control" name="destination" value="{{ app('request')->input('destination') }}" placeholder="Destination">
                </div>
                <div class="mb-2 col-md-6 col-xl-4">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">From/To</span>
                        </div>
                        <input type="date" name="date_from" class="form-control" value="{{ app('request')->input('date_from') }}">
                        <input type="date" name="date_to" class="form-control" value="{{ app('request')->input('date_to') }}">
                    </div>
                </div>
                <div class="mb-2 col-6 col-md-2 col-xl-1">
                    <button class="btn btn-primary btn-block p-0" type="submit"><i class="material-icons mt-1">search</i></button>
                </div>
                <div class="mb-2 col-6 col-md-2 col-xl-1">
                    <a href="/travels" class="btn btn-secondary btn-block p-0" type="submit"><i class="material-icons mt-1 text-white">clear</i></a>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-striped table-sticky-first">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Request Type</th>
                            <th>Project</th>
                            <th>Destination</th>
                            <th>Travel From</th>
                            <th>Travel To</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($travels as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->requestType ? $item->requestType->name : '-' }}</td>
                                <td>{{ $item->companyProject->project }}</td>
                                <td>{{ $item->destination }}</td>
                                <td>{{ $item->date_from }}</td>
                                <td>{{ $item->date_to }}</td>
                                <td>{{ $item->status->name }}</td>
                                <td class="text-right">
                                    <a href="/travels/view/{{ $item->id }}" class="btn btn-link btn-sm d-inline-block">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">{{ __('messages.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection