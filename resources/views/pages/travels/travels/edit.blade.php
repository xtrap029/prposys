@extends('layouts.app-travels')

@section('title', 'Edit Travel')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Travel #{{ $travel->id }}</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/travels/{{ $travel->id }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="">Traveler</label>
                        <select name="name_id" class="form-control" required>
                            @foreach ($users as $item)
                                <option value="{{ $item->id }}" {{ $travel->name_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                            <optgroup label="Inactive" class="bg-gray-light">
                                @foreach ($users_inactive as $item)
                                    <option value="{{ $item->id }}" {{ $travel->name_id == $item->id ? 'selected' : '' }} class="bg-gray-light">{{ $item->name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                        @include('errors.inline', ['message' => $errors->first('name_id')])
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Project</label>
                        <select name="company_project_id" class="form-control">
                            @foreach ($companies as $item)
                                <optgroup label="{{ $item['name'] }}" class="bg-gray-light">
                                    @foreach ($item['projects'] as $item2)
                                        <option value="{{ $item2['id'] }}" class="bg-gray-light" {{ $travel->company_project_id == $item2['id'] ? 'selected' : '' }}>{{ $item2['name'] }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('company_project_id')])
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Destination</label>
                        <input type="text" class="form-control @error('destination') is-invalid @enderror" name="destination" value="{{ $travel->destination }}" required>
                        @include('errors.inline', ['message' => $errors->first('destination')])
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Date From</label>
                        <input type="datetime-local" class="form-control @error('date_from') is-invalid @enderror" name="date_from" value="{{ $travel->date_from }}" required>
                        @include('errors.inline', ['message' => $errors->first('date_from')])
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Date To</label>
                        <input type="datetime-local" class="form-control @error('date_to') is-invalid @enderror" name="date_to" value="{{ $travel->date_to }}" required>
                        @include('errors.inline', ['message' => $errors->first('date_to')])
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Traveling Users</label>
                        <select name="traveling_users[]" class="form-control chosen-select" multiple>
                            @foreach ($users as $item)
                                <option value="{{ $item->id }}" {{ in_array($item->id, $traveling_users) ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                            <optgroup label="Inactive" class="bg-gray-light">
                                @foreach ($users_inactive as $item)
                                    <option value="{{ $item->id }}" {{ in_array($item->id, $traveling_users) ? 'selected' : '' }} class="bg-gray-light">{{ $item->name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group col-12">
                        <label for="">Other Travelers / Remarks</label>
                        <textarea class="form-control" name="traveling_users_static" required>{{ $travel->traveling_users_static }}</textarea>
                        @include('errors.inline', ['message' => $errors->first('traveling_users_static')])
                    </div>
                    <div class="form-group col-md-12 jsReplicate mt-5">
                        <h4 class="text-center">Attachments</h4>
                        <div class="text-center mb-3">Attach receipts and documents here. Accepts .jpg, .png and .pdf file types, not more than 5mb each.</div>
                        <div class="table-responsive">
                            <table class="table bg-white" style="min-width: 1000px">
                                <thead>
                                    <tr>
                                        <th class="w-25">File</th>
                                        <th class="w-75">Description</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="jsReplicate_container">
                                    @if (isset($travel->attachments[0]))
                                        <tr class="jsReplicate_template_item">
                                            <td>
                                                <a href="/storage/public/attachments/travel_attachment/{{ $travel->attachments[0]->file }}" target="_blank">
                                                    <i class="material-icons mr-2 align-bottom align-text-bottom">attachment</i>
                                                </a>
                                                <input type="file" name="file_old[]" class="form-control w-75 d-inline-block overflow-hidden">
                                                <input type="hidden" name="attachment_id_old[]" value="{{ $travel->attachments[0]->id }}">
                                            </td>
                                            <td><input type="text" name="attachment_description_old[]" class="form-control" value="{{ $travel->attachments[0]->description }}" required></td>
                                            <td><button type="button" class="btn btn-danger jsReplicate_remove jsMath_trigger"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                                        </tr>
                                    @endif
                                    @foreach ($travel->attachments as $key => $item)
                                        @if ($key > 0)
                                            <tr class="jsReplicate_template_item">
                                                <td>
                                                    <a href="/storage/public/attachments/travel_attachment/{{ $item->file }}" target="_blank">
                                                        <i class="material-icons mr-2 align-bottom align-text-bottom">attachment</i>
                                                    </a>
                                                    <input type="file" name="file_old[]" class="form-control w-75 d-inline-block overflow-hidden">
                                                    <input type="hidden" name="attachment_id_old[]" value="{{ $item->id }}">
                                                </td>
                                                <td><input type="text" name="attachment_description_old[]" class="form-control" value="{{ $item->description }}" required></td>
                                                <td><button type="button" class="btn btn-danger jsReplicate_remove jsMath_trigger"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-secondary jsReplicate_add"><i class="nav-icon material-icons icon--list">add_box</i> Add More</button>
                        </div>
                    </div>
                </div>
                <a href="/travels">Cancel</a>
                <input type="submit" class="btn btn-primary float-right" value="Save">
            </form>
            <table class="d-none">
                <tbody class="jsReplicate_template">
                    <tr class="jsReplicate_template_item">
                        <td><input type="file" name="file[]" class="form-control overflow-hidden" required></td>
                        <td><input type="text" name="attachment_description[]" class="form-control" required></td>
                        <td><button type="button" class="btn btn-danger jsReplicate_remove jsMath_trigger"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
@endsection

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.css" integrity="sha512-0nkKORjFgcyxv3HbE4rzFUlENUMNqic/EzDIeYCgsKa/nwqr2B91Vu/tNAu4Q0cBuG4Xe/D1f/freEci/7GDRA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $('.chosen-select').chosen();

        $(function() {
            if ($('.jsReplicate')[0]){
                cls = '.jsReplicate'
                
                $(cls+'_add').click(function() {
                    container = $(this).parents(cls).find(cls+'_container')
                    clsIndex = $(this).parents(cls).index(cls)
                    $(cls+'_template:eq('+clsIndex+')').children().clone().appendTo(container)
                })

                $(document).on('click', cls+'_remove', function() {
                    $(this).parents(cls+'_template_item').remove()
                })
            }
        })
    </script>
@endsection