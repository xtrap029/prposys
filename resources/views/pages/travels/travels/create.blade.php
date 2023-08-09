@extends('layouts.app-travels')

@section('title', 'Create Travel')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Travel</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/travels" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="">Request Type</label>
                        <select id="travels_request_type_id" name="travels_request_type_id" class="form-control">
                            @foreach ($request_types as $item)
                                <option value="{{ $item->id }}" class="bg-gray-light">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('travels_request_type_id')])
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Project</label>
                        <select name="company_project_id" class="form-control">
                            @foreach ($companies as $item)
                                <optgroup label="{{ $item['name'] }}" class="bg-gray-light">
                                    @foreach ($item['projects'] as $item2)
                                        <option value="{{ $item2['id'] }}" class="bg-gray-light">{{ $item2['name'] }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('company_project_id')])
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Destination</label>
                        <input type="text" class="form-control @error('destination') is-invalid @enderror" name="destination" value="{{ old('destination') }}" required>
                        @include('errors.inline', ['message' => $errors->first('destination')])
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Date From</label>
                        <input type="date" class="form-control @error('date_from') is-invalid @enderror" name="date_from" value="{{ old('date_from') }}" required>
                        @include('errors.inline', ['message' => $errors->first('date_from')])
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Date To</label>
                        <input type="date" class="form-control @error('date_to') is-invalid @enderror" name="date_to" value="{{ old('date_to') }}" required>
                        @include('errors.inline', ['message' => $errors->first('date_to')])
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Purpose / Trip Agenda</label>
                        <input type="text" class="form-control @error('purpose') is-invalid @enderror" name="purpose" value="{{ old('purpose') }}" required>
                        @include('errors.inline', ['message' => $errors->first('purpose')])
                    </div>
                    <div class="form-group col-12">
                        <label for="">
                            Other Travelers / Remarks
                            <i class="align-middle material-icons small text-primary"
                                data-toggle="tooltip"
                                data-placement="top"
                                data-html="true"
                                title="
                                    e.g. Flight time request to accommodate a meeting / Baggage Request, etc.
                                ">help</i>
                        </label>
                        <textarea class="form-control" name="traveling_users_static" rows="2" required>{{ old('traveling_users_static') }}</textarea>
                        @include('errors.inline', ['message' => $errors->first('traveling_users_static')])
                    </div>
                    <div class="col-12 mt-4 mb-5">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="passenger-tab" data-toggle="tab" href="#passenger" role="tab" aria-controls="passenger" aria-selected="true"><i class="nav-icon material-icons icon--list mr-2">groups</i> Passenger Information</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="attachment-tab" data-toggle="tab" href="#attachment" role="tab" aria-controls="attachment" aria-selected="false"><i class="nav-icon material-icons icon--list mr-2">folder_open</i> Attachments</a>
                            </li>
                            @if ($perms['can_add_options'])
                                <li class="nav-item flightHotelOptions" role="presentation">
                                    <a class="nav-link" id="flight-tab" data-toggle="tab" href="#flight" role="tab" aria-controls="flight" aria-selected="false"><i class="nav-icon material-icons icon--list mr-2">flight</i> Flight Options</a>
                                </li>
                                <li class="nav-item flightHotelOptions" role="presentation">
                                    <a class="nav-link" id="hotel-tab" data-toggle="tab" href="#hotel" role="tab" aria-controls="hotel" aria-selected="false"><i class="nav-icon material-icons icon--list mr-2">bed</i> Hotel Options</a>
                                </li>
                            @endif
                        </ul>
                        <div class="tab-content bg-white border rounded border-top-0 py-4" id="myTabContent">
                            <div class="tab-pane fade show active" id="passenger" role="tabpanel" aria-labelledby="passenger-tab">
                                <div class="tab-pane-alert"></div>
                                <div class="form-group jsReplicate">
                                    <div class="table-responsive">
                                        <table class="table bg-white" style="min-width: 1000px">
                                            <thead>
                                                <tr>
                                                    <th class="w-25 border-top-0">Traveler</th>
                                                    <th class="w-75 border-top-0">Mabuhay Miles / GetGo Number</th>
                                                    <th class="border-top-0"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="jsReplicate_container">
                                                <tr class="jsReplicate_template_item">
                                                    <td>
                                                        <select name="passenger_id[]" class="form-control">
                                                            @foreach ($users as $item)
                                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                            @endforeach
                                                            <optgroup label="Inactive" class="bg-gray-light">
                                                                @foreach ($users_inactive as $item)
                                                                    <option value="{{ $item->id }}" class="bg-gray-light">{{ $item->name }}</option>
                                                                @endforeach
                                                            </optgroup>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" name="travel_no[]" class="form-control" placeholder="Leave 'n/a' if blank" required></td>
                                                    <td><button type="button" class="btn btn-danger jsReplicate_remove"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-center">
                                        <button type="button" class="btn btn-secondary jsReplicate_add"><i class="nav-icon material-icons icon--list">add_box</i> Add More</button>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="attachment" role="tabpanel" aria-labelledby="attachment-tab">
                                <div class="tab-pane-alert"></div>
                                <div class="form-group jsReplicate">
                                    <div class="table-responsive">
                                        <table class="table bg-white" style="min-width: 1000px">
                                            <thead>
                                                <tr>
                                                    <th class="w-25 border-top-0">File</th>
                                                    <th class="w-75 border-top-0">Description</th>
                                                    <th class="border-top-0"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="jsReplicate_container">
                                                <tr class="jsReplicate_template_item">
                                                    <td><input type="file" name="file[]" class="form-control overflow-hidden" required></td>
                                                    <td><input type="text" name="attachment_description[]" class="form-control" value="{{ old('attachment_description.0') }}" required></td>
                                                    <td><button type="button" class="btn btn-danger jsReplicate_remove"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                                                </tr>
                                                @if (old('attachment_description'))
                                                    @foreach (old('attachment_description') as $key => $item)
                                                        @if ($key > 0)
                                                            <tr class="jsReplicate_template_item">
                                                                <td><input type="file" name="file[]" class="form-control overflow-hidden" required></td>
                                                                <td><input type="text" name="attachment_description[]" class="form-control" value="{{ old('attachment_description.'.$key) }}" required></td>
                                                                <td><button type="button" class="btn btn-danger jsReplicate_remove"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-center">
                                        <button type="button" class="btn btn-secondary jsReplicate_add"><i class="nav-icon material-icons icon--list">add_box</i> Add More</button>
                                        <div class="mt-5">Attach receipts and documents here. Accepts .jpg, .png and .pdf file types, not more than 5mb each.</div>
                                    </div>
                                </div>
                            </div>
                            @if ($perms['can_add_options'])
                                <div class="tab-pane fade flightHotelOptionsItem" id="flight" role="tabpanel" aria-labelledby="flight-tab">
                                    <div class="tab-pane-alert"></div>
                                    <div class="form-group jsReplicate">
                                        <div class="table-responsive">
                                            <table class="table bg-white" style="min-width: 1000px">
                                                <thead>
                                                    <tr>
                                                        <th class="text-right border-top-0" style="width: 5%"></th>
                                                        <th class="border-top-0" style="width: 40%">Details</th>
                                                        <th class="border-top-0" style="width: 40%">Fees</th>
                                                        <th class="text-right border-top-0" style="width: 10%">Total</th>
                                                        <th class="border-top-0"></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="jsReplicate_container">
                                                    {{-- <tr class="jsReplicate_template_item jsSum">
                                                        <td class="font-weight-bold text-center jsReplicate_order">1</td>
                                                        <td>
                                                            <div class="form-row">
                                                                <div class="col-6 mb-2">
                                                                    <input type="text" name="f_airline[]" class="form-control" placeholder="Airline" required>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <input type="text" name="f_remarks[]" class="form-control" placeholder="Remarks" required>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <input type="datetime-local" name="f_in[]" class="form-control" placeholder="Time Flight In" required>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <input type="datetime-local" name="f_out[]" class="form-control" placeholder="Time Flight Out" required>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-row">
                                                                <div class="col-6 mb-2">
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="nav-icon material-icons icon--list">flight</i></span>
                                                                        </div>
                                                                        <input type="number" name="f_airfare[]" class="form-control jsSum_item" placeholder="Airfare" step="0.01" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="nav-icon material-icons icon--list">local_taxi</i></span>
                                                                        </div>
                                                                        <input type="number" name="f_car[]" class="form-control jsSum_item" placeholder="Car Rental" step="0.01" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="nav-icon material-icons icon--list">luggage</i></span>
                                                                        </div>
                                                                        <input type="number" name="f_baggage[]" class="form-control jsSum_item" placeholder="Baggage Fee" step="0.01" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="nav-icon material-icons icon--list">park</i></span>
                                                                        </div>
                                                                        <input type="number" name="f_land[]" class="form-control jsSum_item" placeholder="Land Expenses" step="0.01" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-right">
                                                            <h6 class="jsSum_total mt-2 font-weight-bold">0.00</h6>
                                                        </td>
                                                        <td><button type="button" class="btn btn-danger jsReplicate_remove"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                                                    </tr> --}}
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="text-center">
                                            <button type="button" class="btn btn-secondary jsReplicate_add"><i class="nav-icon material-icons icon--list">add_box</i> Add More</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade flightHotelOptionsItem" id="hotel" role="tabpanel" aria-labelledby="hotel-tab">
                                    <div class="tab-pane-alert"></div>
                                    <div class="form-group jsReplicate">
                                        <div class="table-responsive">
                                            <table class="table bg-white" style="min-width: 1000px">
                                                <thead>
                                                    <tr>
                                                        <th class="text-right border-top-0" style="width: 5%"></th>
                                                        <th class="border-top-0" style="width: 40%">Details</th>
                                                        <th class="border-top-0" style="width: 40%">Fees</th>
                                                        <th class="text-right border-top-0" style="width: 10%">Total</th>
                                                        <th class="border-top-0"></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="jsReplicate_container">
                                                    {{-- <tr class="jsReplicate_template_item jsSum">
                                                        <td class="font-weight-bold text-center jsReplicate_order">1</td>
                                                        <td>
                                                            <div class="form-row">
                                                                <div class="col-6 mb-2">
                                                                    <input type="text" name="h_name[]" class="form-control" placeholder="Name" required>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <input type="text" name="h_remarks[]" class="form-control" placeholder="Remarks" required>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-row">
                                                                <div class="col-6 mb-2">
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="nav-icon material-icons icon--list">bed</i></span>
                                                                        </div>
                                                                        <input type="number" name="h_rate[]" class="form-control jsSum_item" placeholder="Hotel Rate" step="0.01" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="nav-icon material-icons icon--list">local_taxi</i></span>
                                                                        </div>
                                                                        <input type="number" name="h_car[]" class="form-control jsSum_item" placeholder="Car Rental" step="0.01" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="nav-icon material-icons icon--list">park</i></span>
                                                                        </div>
                                                                        <input type="number" name="h_land[]" class="form-control jsSum_item" placeholder="Land Expenses" step="0.01" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-right">
                                                            <h6 class="jsSum_total mt-2 font-weight-bold">0.00</h6>
                                                        </td>
                                                        <td><button type="button" class="btn btn-danger jsReplicate_remove"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                                                    </tr> --}}
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="text-center">
                                            <button type="button" class="btn btn-secondary jsReplicate_add"><i class="nav-icon material-icons icon--list">add_box</i> Add More</button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <a href="/travels">Cancel</a>
                <input type="submit" id="submitButton" class="btn btn-primary float-right" value="Save">
            </form>
            <table class="d-none">
                <tbody class="jsReplicate_template">
                    <tr class="jsReplicate_template_item">
                        <td>
                            <select name="passenger_id[]" class="form-control">
                                @foreach ($users as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                                <optgroup label="Inactive" class="bg-gray-light">
                                    @foreach ($users_inactive as $item)
                                        <option value="{{ $item->id }}" class="bg-gray-light">{{ $item->name }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </td>
                        <td><input type="text" name="travel_no[]" class="form-control" placeholder="Leave 'n/a' if blank" required></td>
                        <td><button type="button" class="btn btn-danger jsReplicate_remove"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                    </tr>
                </tbody>
            </table>
            <table class="d-none">
                <tbody class="jsReplicate_template">
                    <tr class="jsReplicate_template_item">
                        <td><input type="file" name="file[]" class="form-control overflow-hidden" required></td>
                        <td><input type="text" name="attachment_description[]" class="form-control" required></td>
                        <td><button type="button" class="btn btn-danger jsReplicate_remove"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                    </tr>
                </tbody>
            </table>
            @if ($perms['can_add_options'])
                <table class="d-none">
                    <tbody class="jsReplicate_template">
                        <tr class="jsReplicate_template_item jsSum">
                            <td class="font-weight-bold text-center jsReplicate_order"></td>
                            <td>
                                <div class="form-row">
                                    <div class="col-6 mb-2">
                                        <input type="text" name="f_airline[]" class="form-control" placeholder="Airline" required>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <input type="text" name="f_remarks[]" class="form-control" placeholder="Remarks" required>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <input type="datetime-local" name="f_in[]" class="form-control" placeholder="Time Flight In" required>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <input type="datetime-local" name="f_out[]" class="form-control" placeholder="Time Flight Out" required>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-row">
                                    <div class="col-6 mb-2">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="nav-icon material-icons icon--list">flight</i></span>
                                            </div>
                                            <input type="number" name="f_airfare[]" class="form-control jsSum_item" placeholder="Airfare" step="0.01" required>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="nav-icon material-icons icon--list">local_taxi</i></span>
                                            </div>
                                            <input type="number" name="f_car[]" class="form-control jsSum_item" placeholder="Car Rental" step="0.01" required>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="nav-icon material-icons icon--list">luggage</i></span>
                                            </div>
                                            <input type="number" name="f_baggage[]" class="form-control jsSum_item" placeholder="Baggage Fee" step="0.01" required>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="nav-icon material-icons icon--list">park</i></span>
                                            </div>
                                            <input type="number" name="f_land[]" class="form-control jsSum_item" placeholder="Land Expenses" step="0.01" required>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-right">
                                <h6 class="jsSum_total mt-2 font-weight-bold">0.00</h6>
                            </td>
                            <td><button type="button" class="btn btn-danger jsReplicate_remove"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                        </tr>
                    </tbody>
                </table>
                <table class="d-none">
                    <tbody class="jsReplicate_template">
                        <tr class="jsReplicate_template_item jsSum">
                            <td class="font-weight-bold text-center jsReplicate_order">1</td>
                            <td>
                                <div class="form-row">
                                    <div class="col-6 mb-2">
                                        <input type="text" name="h_name[]" class="form-control" placeholder="Name" required>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <input type="text" name="h_remarks[]" class="form-control" placeholder="Remarks" required>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-row">
                                    <div class="col-6 mb-2">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="nav-icon material-icons icon--list">bed</i></span>
                                            </div>
                                            <input type="number" name="h_rate[]" class="form-control jsSum_item" placeholder="Hotel Rate" step="0.01" required>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="nav-icon material-icons icon--list">local_taxi</i></span>
                                            </div>
                                            <input type="number" name="h_car[]" class="form-control jsSum_item" placeholder="Car Rental" step="0.01" required>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="nav-icon material-icons icon--list">park</i></span>
                                            </div>
                                            <input type="number" name="h_land[]" class="form-control jsSum_item" placeholder="Land Expenses" step="0.01" required>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-right">
                                <h6 class="jsSum_total mt-2 font-weight-bold">0.00</h6>
                            </td>
                            <td><button type="button" class="btn btn-danger jsReplicate_remove"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                        </tr>
                    </tbody>
                </table>
            @endif
        </div>
    </section>
@endsection

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.css" integrity="sha512-0nkKORjFgcyxv3HbE4rzFUlENUMNqic/EzDIeYCgsKa/nwqr2B91Vu/tNAu4Q0cBuG4Xe/D1f/freEci/7GDRA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .nav.nav-tabs .nav-link.active {
            background-color: white !important;
        }
    </style>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(function() {
            if ($('.jsReplicate')[0]){
                cls = '.jsReplicate'
                
                $(cls+'_add').click(function() {
                    container = $(this).parents(cls).find(cls+'_container')
                    clsIndex = $(this).parents(cls).index(cls)
                    $(cls+'_template:eq('+clsIndex+')').children().clone().appendTo(container)

                    reorder(clsIndex)
                })
                
                $(document).on('click', cls+'_remove', function() {
                    clsIndex = $(this).closest(cls).index(cls)
                    $(this).parents(cls+'_template_item').remove()

                    reorder(clsIndex)
                })

                // reorder
                function reorder(index) {
                    $(cls+':eq('+index+')').find(cls+'_order').each(function(index) {
                        $(this).text(index+1)
                    })
                }
            }

            if ($('.jsSum')[0]){
                cls_2 = '.jsSum'
                
                $(document).on('keyup click', cls_2+'_item', function() {
                    sum = 0
                    parentClass = $(this).closest(cls_2)
                    parentClass.find(cls_2+'_item').each(function() {
                        sum += parseFloat($(this).val() != "" ? $(this).val() : 0)
                    })
                    parentClass.find(cls_2+'_total').text(sum.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","))
                })
            }

            $('#travels_request_type_id').on('change', function() {
                flightHotel = '.flightHotelOptions'

                $(flightHotel).removeClass('d-none')
                $(flightHotel+'Item').removeClass('d-none')

                switch ($(this).val()) {
                    case '1':
                        $(flightHotel+':eq(1)').addClass('d-none')
                        $(flightHotel+'Item:eq(1)').addClass('d-none')

                        $(flightHotel+'Item:eq(1) tbody').html('')
                        break;
                    case '2':
                        $(flightHotel+':eq(0)').addClass('d-none')
                        $(flightHotel+'Item:eq(0)').addClass('d-none')

                        $(flightHotel+'Item:eq(0) tbody').html('')
                        break;
                    default:
                        break;
                }
            })

            $('[data-toggle="tooltip"]').tooltip()

            $('#submitButton').click(function () {
                $('input:invalid').each(function () {
                    
                    var $closest = $(this).closest('.tab-pane');
                    var id = $closest.attr('id');

                    $('.nav a[href="#' + id + '"]').tab('show');

                    $(this).closest('.tab-pane').find('.tab-pane-alert').html('<div class="alert alert-default-danger mx-3 rounded">'
                        + '<button type="button" class="close" data-dismiss="alert">×</button>'
                        + 'Please fill in required fields.'
                        +'</div>')

                    return false;
                });
            });
        })
    </script>
@endsection