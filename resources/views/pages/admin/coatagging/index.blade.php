@extends('layouts.app')

@section('title', 'COA Tagging')

@section('content')
    <div class="container">
        <h4 class="mb-4">COA Tagging</h4>

        <table class="table">
            <thead>
                <tr>
                    <th>List</th>
                    <th class="text-right"><a href="/coa-tagging/create">Create</a></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($coa_taggings as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td class="text-right">
                            <a href="/coa-tagging/{{ $item->id }}/edit" class="btn btn-link btn-sm">Edit</a>
                            <form action="/coa-tagging/{{ $item->id }}" method="post" class="d-inline-block">
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