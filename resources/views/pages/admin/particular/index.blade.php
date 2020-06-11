@extends('layouts.app')

@section('title', 'Particulars')

@section('content')
    <div class="container">
        <h4 class="mb-4">Particulars</h4>

        <table class="table">
            <thead>
                <tr>
                    <th>PR List</th>
                    <th class="text-right"><a href="/particular/create?type=pr">Create</a></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($particulars_pr as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td class="text-right">
                            <a href="/particular/{{ $item->id }}/edit" class="btn btn-link btn-sm">Edit</a>
                            <form action="/particular/{{ $item->id }}" method="post" class="d-inline-block">
                                @csrf
                                @method('delete')
                                <input type="submit" class="btn btn-link btn-sm" value="Delete" onclick="return confirm('Are you sure?')">
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No results found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <table class="table mt-5">
            <thead>
                <tr>
                    <th>PO List</th>
                    <th class="text-right"><a href="/particular/create?type=po">Create</a></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($particulars_po as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td class="text-right">
                            <a href="/particular/{{ $item->id }}/edit" class="btn btn-link btn-sm">Edit</a>
                            <form action="/particular/{{ $item->id }}" method="post" class="d-inline-block">
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
@endsection