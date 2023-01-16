@extends('layouts.app-travels')

@section('content')
<section class="content-header pb-0">
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-lg-4">
                <div class="card card-widget widget-user">
                    <div class="widget-user-header bg--tecc">
                        <h3 class="widget-user-username">{{ $user->name }}</h3>
                        <h6 class="widget-user-desc">{{ $user->role->name }} {{ $user->is_smt ? ' - SMT' : '' }}</h6>
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
                                    <h5 class="description-header">Hired</h5>
                                    <span>  
                                        @if ($user->e_hire_date)
                                            {{ Carbon::parse($user->e_hire_date)->diffInDays(Carbon::now()) >= 1 ? Carbon::parse($user->e_hire_date)->format('Y-m-d') : Carbon::parse($user->e_hire_date)->diffForHumans() }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-8 d-flex">
                @include('layouts.sections.slick')
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card card-widget">
                    <div class="card-header pb-2 bg-dark">
                        <h3 class="card-title">
                            <i class="nav-icon material-icons icon--list mr-2 text-white">mouse</i>
                            Latest Created Travels
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped small m-0">
                            @foreach ($created_travels as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->date_from }}</td>
                                    <td>{{ $item->date_to }}</td>
                                    <td class="text-right">
                                        <a href="#_" class="btn btn-link btn-sm" data-toggle="modal" data-target="#modal-travel-tagged-view-{{ $item->id }}">View</a>

                                        <div class="modal fade" id="modal-travel-tagged-view-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
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
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card card-widget">
                    <div class="card-header pb-2 bg-dark">
                        <h3 class="card-title">
                            <i class="nav-icon material-icons icon--list mr-2 text-white">person</i>
                            Latest Personal Travels
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped small m-0">
                            @foreach ($my_travels as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->date_from }}</td>
                                    <td>{{ $item->date_to }}</td>
                                    <td class="text-right">
                                        <a href="#_" class="btn btn-link btn-sm" data-toggle="modal" data-target="#modal-travel-personal-view-{{ $item->id }}">View</a>

                                        <div class="modal fade" id="modal-travel-personal-view-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
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
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card card-widget">
                    <div class="card-header pb-2 bg-dark">
                        <h3 class="card-title">
                            <i class="nav-icon material-icons icon--list mr-2 text-white">person_add</i>
                            Latest Tagged Travels
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped small m-0">
                            @foreach ($tagged_travels as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->date_from }}</td>
                                    <td>{{ $item->date_to }}</td>
                                    <td class="text-right">
                                        <a href="#_" class="btn btn-link btn-sm" data-toggle="modal" data-target="#modal-travel-tagged-view-{{ $item->id }}">View</a>

                                        <div class="modal fade" id="modal-travel-tagged-view-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
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
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection