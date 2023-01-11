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
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Traveler</th>
                        <th>Project</th>
                        <th>Destination</th>
                        <th>Travel From</th>
                        <th>Travel To</th>
                        <th class="text-right"><a href="/travels/create">Create</a></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($travels as $item)
                        <tr>
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
                            <td colspan="3" class="text-center">{{ __('messages.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection