@extends('layouts.app')

@section('title', 'Expense Type')

@section('content')
    <div class="container">
        <h4 class="mb-4">Expense Type</h4>

        <table class="table">
            <thead>
                <tr>
                    <th>List</th>
                    <th class="text-right"><a href="/expense-type/create">Create</a></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($expense_types as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td class="text-right">
                            <a href="/expense-type/{{ $item->id }}/edit" class="btn btn-link btn-sm">Edit</a>
                            <form action="/expense-type/{{ $item->id }}" method="post" class="d-inline-block">
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