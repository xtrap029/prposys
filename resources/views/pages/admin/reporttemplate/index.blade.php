@extends('layouts.app')

@section('title', 'Report Template')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Report Template</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>
                            <a href="/report-column" target="_blank">Columns</a>
                        </th>
                        <th class="text-right"><a href="/report-template/create">Create</a></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($report_templates as $item)
                        <tr>
                            <td>{{ $item->name }} <span class="badge badge-primary ml-1 {{ $item->id != 1 ? 'd-none' : '' }}">Default</span></td>
                            <td>
                                @foreach ($item->templatecolumn as $item_2)
                                    <code>{{ $item_2->label ?: $item_2->column->label }}</code>
                                    {{ !$loop->last ? '/' : '' }}
                                @endforeach
                            </td>
                            <td class="text-right">
                                <a href="/report-template/{{ $item->id }}/edit" class="btn btn-link btn-sm">Edit</a>
                                <form action="/report-template/{{ $item->id }}" method="post" class="{{ $item->id == 1 ? 'd-none' : 'd-inline-block' }}">
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