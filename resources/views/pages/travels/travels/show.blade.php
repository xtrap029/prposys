@extends('layouts.app-travels')

@section('title', 'View Travel #'.strtoupper($travel->id))
@section('nav_class', 'navbar-dark')

@section('content')
    <section class="content-header bg-dark">
        <div class="container-fluid">
            <div class="row"> 
                <div class="col-lg-6">
                    <h1 class="mb-0">Travel #{{ $travel->id }}</h1>
                    <span class="text-white-50">{{ $travel->companyProject->project.' - '. $travel->companyProject->company->name }}</span>
                    <div class="mt-2">
                        <span class="badge badge-pill bg-warning p-2">{{ $travel->requestType ? $travel->requestType->name : '-' }}</span>                       
                    </div>
                </div>

                <div class="col-lg-6 text-right mt-4">
                    <div>
                        <a href="/travels" class="btn btn-sm btn-flat mb-2 btn-light col-12 col-lg-auto"><i class="align-middle font-weight-bolder material-icons text-md">arrow_back_ios</i> Back</a>
                        <a href="/travels/create" class="btn btn-sm btn-flat mb-2 btn-light col-12 col-lg-auto"><i class="align-middle font-weight-bolder material-icons text-md">add</i> Add New</a>
                    </div>
                    <div>
                        <a href="/travels/{{ $travel->id }}/edit" class="btn mb-2 btn-sm btn-flat btn-primary col-12 col-lg-auto {{ $perms['can_edit'] ? '' : 'd-none' }}"><i class="align-middle font-weight-bolder material-icons text-md">edit</i> Edit</a>
                        <a href="#_" class="btn mb-2 btn-sm btn-flat btn-danger col-12 col-lg-auto {{ $perms['can_cancel'] ? '' : 'd-none' }}" data-toggle="modal" data-target="#modal-cancel"><i class="align-middle font-weight-bolder material-icons text-md">delete</i> Cancel</a>
                        <a href="/travels/for-review/{{ $travel->id }}" class="btn mb-2 btn-sm btn-flat btn-success col-12 col-lg-auto {{ $perms['can_for_review'] ? '' : 'd-none' }}"><i class="align-middle font-weight-bolder material-icons text-md">grading</i> For Review</a>
                        <a href="/travels/for-approval/{{ $travel->id }}" class="btn mb-2 btn-sm btn-flat btn-success col-12 col-lg-auto {{ $perms['can_for_approval'] ? '' : 'd-none' }}"><i class="align-middle font-weight-bolder material-icons text-md">grading</i> For Approval</a>
                        <a href="#_" class="btn mb-2 btn-sm btn-flat btn-success col-12 col-lg-auto {{ $perms['can_for_booking'] ? '' : 'd-none' }}" data-toggle="modal" data-target="#modal-booking"><i class="align-middle font-weight-bolder material-icons text-md">grading</i> For Booking</a>
                        <a href="#_" class="btn mb-2 btn-sm btn-flat btn-success col-12 col-lg-auto {{ $perms['can_booked'] ? '' : 'd-none' }}" data-toggle="modal" data-target="#modal-booked"><i class="align-middle font-weight-bolder material-icons text-md">grading</i> Booked</a>
                    </div>
                </div>
            </div>
            <div class="text-dark">
                <div class="modal fade" id="modal-cancel" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title">{{ __('messages.cancel_prompt') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <form action="/travels/cancel/{{ $travel->id }}" method="post">
                                    @csrf
                                    @method('put')
                                    <textarea name="cancellation_reason" class="form-control @error('cancellation_reason') is-invalid @enderror" rows="3" placeholder="Cancellation Reason" required></textarea>
                                    @include('errors.inline', ['message' => $errors->first('cancellation_reason')])
                                    <input type="submit" class="btn btn-danger mt-2" value="Cancel Now">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="modal-booking" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title">{{ __('messages.hotel_flight_prompt') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <form action="/travels/for-booking/{{ $travel->id }}" method="post">
                                    @csrf
                                    @method('put')
                                    @if ($travel->travels_request_type_id != 2)
                                        <div class="form-group">
                                            <label for="">Flight</label>
                                            <select name="selected_flight" class="form-control @error('selected_flight') is-invalid @enderror" required>
                                                <option value="">- Select -</option>
                                                @foreach ($travel->flights as $key => $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    @if ($travel->travels_request_type_id != 1)
                                        <div class="form-group">
                                            <label for="">Hotel</label>
                                            <select name="selected_hotel" class="form-control @error('selected_hotel') is-invalid @enderror" required>
                                                <option value="">- Select -</option>
                                                @foreach ($travel->hotels as $key => $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    <input type="submit" class="btn btn-success mt-2" value="For Booking">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="modal-booked" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title">{{ __('messages.booked_prompt') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <p>Some fields...</p>
                                <form action="/travels/booked/{{ $travel->id }}" method="post">
                                    @csrf
                                    @method('put')
                                    <input type="submit" class="btn btn-success mt-2" value="Booked">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid pt-3">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="text-center my-4">
                                    <ul class="pagination d-inline-flex">
                                        @foreach ($status as $item)
                                            <li class="page-item {{ $item->id === $travel->status_id ? 'active' : 'disabled' }}"><a class="page-link" href="#">{{ ucwords(strtolower($item->name)) }}</a></li>                                
                                        @endforeach                            
                                        <li class="page-item ml-2 disabled"><a class="page-link {{ $status_cancelled->id === $travel->status_id ? 'bg-danger' : '' }}" href="#">{{ ucwords(strtolower($status_cancelled->name)) }}</a></li>   
                                    </ul>
                                </div>
                                <table class="table">
                                    <tr>
                                        <td class="font-weight-bold text-gray border-0">Created By</td>
                                        <td class="font-weight-bold border-0">
                                            <img src="/storage/public/images/users/{{ $travel->owner->avatar }}" class="img-circle img-size-32 mr-2">
                                            {{ $travel->owner->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-gray">Updated By</td>
                                        <td class="font-weight-bold">
                                            <img src="/storage/public/images/users/{{ $travel->updatedby->avatar }}" class="img-circle img-size-32 mr-2">
                                            {{ $travel->updatedby->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-gray">Date From</td>
                                        <td class="font-weight-bold">{{ $travel->date_from }}</td>
                                    </tr>  
                                    <tr>
                                        <td class="font-weight-bold text-gray">Date To</td>
                                        <td class="font-weight-bold">{{ $travel->date_to }}</td>
                                    </tr>  
                                    <tr>
                                        <td class="font-weight-bold text-gray">Destination</td>
                                        <td class="font-weight-bold">{{ $travel->destination }}</td>
                                    </tr>    
                                    <tr>
                                        <td class="font-weight-bold text-gray">Purpose</td>
                                        <td class="font-weight-bold">{{ $travel->purpose }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <p class="font-weight-bold text-gray mb-0">Other Travelers / Remarks</p>
                                            {{ $travel->traveling_users_static }}
                                        </td>
                                    </tr>                              
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($travel->status_id == $cancelled_id)
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <code class="float-right">{{ $travel->cancellation_number }}</code>
                                <h5>Cancellation Reason</h5>
                                <div class="pt-3">{{ $travel->cancellation_reason }}</div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body table-responsive">
                            <h5>History</h5>
                            <table class="table table-striped table-bordered table-sm small my-3">
                                <tbody>
                                    @foreach ($logs as $item)
                                        <tr>
                                            <td>
                                                <a href="#_" data-toggle="modal" data-target="#modal-{{ $item->id }}">
                                                    @if ($item->description == 'created')
                                                        <i class="align-middle font-weight-bolder material-icons text-md">add</i>
                                                    @else
                                                        <i class="align-middle font-weight-bolder material-icons text-md">edit</i>
                                                    @endif
                                                </a>
                                                <div class="modal fade" id="modal-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header border-0">
                                                                <h5 class="modal-title">
                                                                    {{ ucfirst($item->description) }} {{ Carbon::parse($item->created_at)->diffInDays(Carbon::now()) >= 1 ? $item->created_at->format('Y-m-d') : $item->created_at->diffForHumans() }}
                                                                </h5>
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
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $item->log_name }}</td>
                                            <td>{{ $item->causer->name }}</td>
                                            <td class="text-right">{{ Carbon::parse($item->created_at)->diffInDays(Carbon::now()) >= 1 ? $item->created_at->format('Y-m-d') : $item->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="text-center">
                                <div class="d-inline-block small">
                                    {{ $logs->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-4 mb-5">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="passenger-tab" data-toggle="tab" href="#passenger" role="tab" aria-controls="passenger" aria-selected="true"><i class="nav-icon material-icons icon--list mr-2">groups</i> Passenger Information</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="attachment-tab" data-toggle="tab" href="#attachment" role="tab" aria-controls="attachment" aria-selected="false"><i class="nav-icon material-icons icon--list mr-2">folder_open</i> Attachments</a>
                        </li>
                        @if ($travel->travels_request_type_id != 2)
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="flight-tab" data-toggle="tab" href="#flight" role="tab" aria-controls="flight" aria-selected="false"><i class="nav-icon material-icons icon--list mr-2">flight</i> Flight Options</a>
                            </li>
                        @endif
                        @if ($travel->travels_request_type_id != 1)
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="hotel-tab" data-toggle="tab" href="#hotel" role="tab" aria-controls="hotel" aria-selected="false"><i class="nav-icon material-icons icon--list mr-2">bed</i> Hotel Options</a>
                            </li>
                        @endif
                    </ul>
                    <div class="tab-content bg-white border rounded border-top-0 py-4" id="myTabContent">
                        <div class="tab-pane fade show active" id="passenger" role="tabpanel" aria-labelledby="passenger-tab">
                            <div class="form-group">
                                <div class="table-responsive">
                                    <table class="table bg-white" style="min-width: 1000px">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th class="text-center">Birthdate</th>
                                                <th class="text-right">Mabuhay Miles / GetGo Number</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($travel->passengers as $item)
                                                <tr>
                                                    <td>
                                                        <img src="/storage/public/images/users/{{ $item->user->avatar }}" class="img-circle img-size-32 mr-2">
                                                        {{ $item->user->name }}
                                                    </td>
                                                    <td class="text-center">{{ $item->user->e_dob }}</td>
                                                    <td class="text-right">{{ $item->travel_no }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="attachment" role="tabpanel" aria-labelledby="attachment-tab">
                            <div class="form-group">
                                <div class="table-responsive">
                                    <table class="table bg-white" style="min-width: 1000px">
                                        <thead>
                                            <tr>
                                                <th class="border-top-0">File</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($travel->attachments as $item)
                                                <tr>
                                                    <td>
                                                        <a class="btn" href="/storage/public/attachments/travel_attachment/{{ $item->file }}" target="_blank">
                                                            <i class="align-middle font-weight-bolder material-icons text-orange">folder</i>
                                                            <span class="vlign--middle ml-2">{{ $item->description }}</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="flight" role="tabpanel" aria-labelledby="flight-tab">
                            <div class="form-group">
                                <div class="table-responsive">
                                    <table class="table bg-white" style="min-width: 1000px">
                                        <thead>
                                            <tr>
                                                <th class="text-right border-top-0" style="width: 5%"></th>
                                                <th class="border-top-0" style="width: 40%">Details</th>
                                                <th class="border-top-0" style="width: 40%">Fees</th>
                                                <th class="text-right border-top-0" style="width: 10%">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($travel->flights as $key => $item)                                            
                                                <tr class="{{ $item->is_selected == 1 ? 'bg-success' : '' }}">
                                                    <td class="font-weight-bold text-center">{{ $key + 1 }}</td>
                                                    <td>
                                                        <div class="form-row">
                                                            <div class="col-6 mb-2"><span class="font-weight-bold">Airline</span> {{ $item->name }}</div>
                                                            <div class="col-6 mb-2"><span class="font-weight-bold">Remarks</span> {{ $item->remarks }}</div>
                                                            <div class="col-6 mb-2"><span class="font-weight-bold">In</span> {{ Carbon::parse($item->time_in)->format('Y-m-d H:i') }}</div>
                                                            <div class="col-6 mb-2"><span class="font-weight-bold">Out</span> {{ Carbon::parse($item->time_out)->format('Y-m-d H:i') }}</div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-row">
                                                            <div class="col-6 mb-2">
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="nav-icon material-icons icon--list">flight</i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control jsSum_item bg-white" value="{{ $item->fee }}" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-6 mb-2">
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="nav-icon material-icons icon--list">local_taxi</i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control jsSum_item bg-white" value="{{ $item->fee_car }}" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-6 mb-2">
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="nav-icon material-icons icon--list">luggage</i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control jsSum_item bg-white" value="{{ $item->fee_baggage }}" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-6 mb-2">
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="nav-icon material-icons icon--list">park</i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control jsSum_item bg-white" value="{{ $item->fee_land }}" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-right">
                                                        <h6 class="mt-2 font-weight-bold">{{ number_format($item->total, 2, '.', ',') }}</h6>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="hotel" role="tabpanel" aria-labelledby="hotel-tab">
                            <div class="form-group">
                                <div class="table-responsive">
                                    <table class="table bg-white" style="min-width: 1000px">
                                        <thead>
                                            <tr>
                                                <th class="text-right border-top-0" style="width: 5%"></th>
                                                <th class="border-top-0" style="width: 40%">Details</th>
                                                <th class="border-top-0" style="width: 40%">Fees</th>
                                                <th class="text-right border-top-0" style="width: 10%">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($travel->hotels as $key => $item)  
                                                <tr class="{{ $item->is_selected == 1 ? 'bg-success' : '' }}">
                                                    <td class="font-weight-bold text-center">{{ $key + 1 }}</td>
                                                    <td>
                                                        <div class="form-row">
                                                            <div class="col-6 mb-2"><span class="font-weight-bold">Airline</span> {{ $item->name }}</div>
                                                            <div class="col-6 mb-2"><span class="font-weight-bold">Remarks</span> {{ $item->remarks }}</div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-row">
                                                            <div class="col-6 mb-2">
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="nav-icon material-icons icon--list">bed</i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control jsSum_item bg-white" value="{{ $item->fee }}" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-6 mb-2">
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="nav-icon material-icons icon--list">local_taxi</i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control jsSum_item bg-white" value="{{ $item->fee_car }}" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-6 mb-2">
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="nav-icon material-icons icon--list">park</i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control jsSum_item bg-white" value="{{ $item->fee_land }}" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-right">
                                                        <h6 class="mt-2 font-weight-bold">{{ number_format($item->total, 2, '.', ',') }}</h6>
                                                    </td>
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

@section('style')
    <style>
        .nav.nav-tabs .nav-link.active {
            background-color: white !important;
        }
    </style>
@endsection