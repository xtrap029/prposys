@extends('layouts.app')

@section('title', 'Vendor')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Vendor</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th colspan="4">List</th>
                        <th class="text-right"><a href="/vendor/create">Create</a></th>
                    </tr>
                </thead>
            </table>
            <div class="accordion">
                @forelse ($vendors as $item)
                    <div class="accordion-item mb-1">
                        <h2 class="accordion-header mb-1">
                            <button class="accordion-button btn btn-block text-left btn-default" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $item->id }}" aria-expanded="false" aria-controls="collapse{{ $item->id }}">
                                {{ $item->name }}
                            </button>
                        </h2>
                        <div id="collapse{{ $item->id }}" class="accordion-collapse collapse p-3 border bg-gray rounded">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md-4">
                                                Address: {{ $item->address }}<br>
                                                Product: {{ $item->product }}<br>
                                                Description: {{ $item->description }}<br>
                                                @if ($item->file)
                                                    File: <a href="/storage/public/attachments/2303/{{ $item->file }}" class="text-info" target="_blank">2303</a>
                                                @endif
                                            </div>
                                            <div class="col-md-4">
                                                Contact Person: {{ $item->contact_person }}<br>
                                                Contact Details: {{ $item->contact_no }} / {{ $item->email }}<br>
                                                TIN: {{ $item->tin }}
                                            </div>
                                            <div class="col-md-4">
                                                Bank: {{ $item->account_bank }}<br>
                                                Name: {{ $item->account_name }}<br>
                                                No.: {{ $item->account_number }}<br>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <a href="/vendor/{{ $item->id }}/edit" class="btn btn-warning btn-sm d-inline-block">Edit</a>
                                        <form action="/vendor/{{ $item->id }}" method="post" class="d-inline-block">
                                            @csrf
                                            @method('delete')
                                            <input type="submit" class="btn btn-danger btn-sm" value="Delete" onclick="return confirm('Are you sure?')">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
            <div class="overflow-auto position-relative text-center mt-3">
                <div class="d-inline-block">
                    {{ $vendors->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
@endsection