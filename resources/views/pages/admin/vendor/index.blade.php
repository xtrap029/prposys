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
                <tbody>
                </thead>
                    @forelse ($vendors as $item)
                        <tr>
                            <td>
                                <b>{{ $item->name }}</b><br>
                                Address: {{ $item->address }}<br>
                                @if ($item->file)
                                    File: <a href="/storage/public/attachments/2303/{{ $item->file }}" target="_blank">2303</a>
                                @endif
                            </td>
                            <td>
                                Contact Person: {{ $item->contact_person }}<br>
                                Contact Details: {{ $item->contact_no }} / {{ $item->email }}<br>
                                TIN: {{ $item->tin }}
                            </td>
                            <td>
                                Bank: {{ $item->account_bank }}<br>
                                Name: {{ $item->account_name }}<br>
                                No.: {{ $item->account_number }}<br>
                            </td>
                            <td>
                                Product: {{ $item->product }}<br>
                                Description: {{ $item->description }}<br>
                            </td>
                            <td class="text-right">
                                <a href="/vendor/{{ $item->id }}/edit" class="btn btn-link btn-sm d-inline-block">Edit</a>
                                <form action="/vendor/{{ $item->id }}" method="post" class="d-inline-block">
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