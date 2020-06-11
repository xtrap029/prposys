@extends('layouts.app')

@section('title', 'Company')

@section('content')
    <div class="container">
        <h4 class="mb-4">Company</h4>

        <table class="table">
            <thead>
                <tr>
                    <th colspan="3">List</th>
                    <th class="text-right"><a href="/company/create">Create</a></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($companies as $item)
                    <tr>
                        <td><img src="storage/public/images/companies/{{ $item->logo }}" alt="" class="thumb thumb--xs"></td>
                        <td class="align-middle">{{ $item->code }}</td>
                        <td class="align-middle">{{ $item->name }}</td>
                        <td class="align-middle text-right">
                            <a href="/company-project/{{ $item->id }}" class="btn btn-link btn-sm">Projects</a>
                            <a href="/company/{{ $item->id }}/edit" class="btn btn-link btn-sm">Edit</a>
                            <form action="/company/{{ $item->id }}" method="post" class="d-inline-block">
                                @csrf
                                @method('delete')
                                <input type="submit" class="btn btn-link btn-sm" value="Delete" onclick="return confirm('Are you sure?')">
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">{{ __('messages.empty') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection