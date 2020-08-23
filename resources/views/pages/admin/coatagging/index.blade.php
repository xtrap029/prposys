@extends('layouts.app')

@section('title', 'COA Tagging')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>COA Tagging</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th colspan="2">List</th>
                        <th class="text-right"><a href="/coa-tagging/create">Create</a></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($coa_taggings as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->company->name }}</td>
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
    </section>
@endsection