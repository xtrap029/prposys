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
                    <a href="/travels/create" class="vlign--baseline-middle btn btn-success btn-block "><span class="font-weight-bold">+</span> New</a>
                </div> 
                <div class="mb-2 col-xl-1">
                    <input type="number" class="form-control" step="1" name="id" value="{{ app('request')->input('id') }}" placeholder="ID">
                </div>
                <div class="mb-2 col-md-4 col-xl-2">
                    <select name="name_id" class="form-control">
                        <option value="">Traveler</option>
                        @foreach ($users as $item)
                            <option value="{{ $item->id }}" {{ app('request')->input('name_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                        @endforeach
                        <optgroup label="Inactive" class="bg-gray-light">
                            @foreach ($users_inactive as $item)
                                <option value="{{ $item->id }}" {{ app('request')->input('name_id') == $item->id ? 'selected' : '' }} class="bg-gray-light">{{ $item->name }}</option>
                            @endforeach
                        </optgroup>
                    </select>
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
                <div class="mb-2 col-md-6 col-xl-3">
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
            </form>
            <div class="table-responsive">
                <table class="table table-striped table-sticky-first">
                    <thead>
                        <tr>
                            <th>Travel ID</th>
                            <th>Traveler</th>
                            <th>Project</th>
                            <th>Destination</th>
                            <th>Travel From</th>
                            <th>Travel To</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($travels as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name->name }}</td>
                                <td>{{ $item->companyProject->project.' - '. $item->companyProject->company->name }}</td>
                                <td>{{ $item->destination }}</td>
                                <td>{{ $item->date_from }}</td>
                                <td>{{ $item->date_to }}</td>
                                <td class="text-right">
                                    <a href="#_" class="btn btn-link btn-sm" data-toggle="modal" data-target="#modal-travel-view-{{ $item->id }}">View</a>

                                    <div class="modal fade" id="modal-travel-view-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title">View Travel</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <table class="table text-left">
                                                        <tr>
                                                            <td>Travele ID</td>
                                                            <td>{{ $item->id }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Traveler</td>
                                                            <td>{{ $item->name->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Project</td>
                                                            <td>{{ $item->companyProject->project.' - '. $item->companyProject->company->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Destination</td>
                                                            <td>{{ $item->destination }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Date From</td>
                                                            <td>{{ $item->date_from }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Date To</td>
                                                            <td>{{ $item->date_to }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Created / Updated</td>
                                                            <td>{{ $item->owner->name }} / {{ $item->updatedby->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Traveling Users</td>
                                                            <td>
                                                                @forelse ($item->travelers as $travelers)
                                                                    {{ $travelers->name }}<br>
                                                                @empty
                                                                    -
                                                                @endforelse
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Other Travelers / Remarks</td>
                                                            <td>{{ $item->traveling_users_static }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Attachments</td>
                                                            <td>
                                                                @forelse ($item->attachments as $item2)
                                                                    <a class="btn btn-block text-left border p-3" href="/storage/public/attachments/travel_attachment/{{ $item2->file }}" target="_blank">
                                                                        <i class="align-middle font-weight-bolder material-icons text-orange">
                                                                            @if (pathinfo($item2->file, PATHINFO_EXTENSION) == 'pdf')
                                                                                picture_as_pdf
                                                                            @else
                                                                                insert_photo  
                                                                            @endif
                                                                        </i>
                                                                        <span class="text-dark pl-2">{{ $item2->description }}</span>
                                                                    </a>
                                                                @empty
                                                                    No attachment found.
                                                                @endforelse
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <a href="/travels/{{ $item->id }}/edit" class="btn btn-link btn-sm d-inline-block">Edit</a>
                                    <form action="/travels/{{ $item->id }}" method="post" class="d-inline-block">
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
            </div>
        </div>
    </section>
@endsection