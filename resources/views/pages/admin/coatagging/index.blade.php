@extends('layouts.app')

@section('title', 'Category/Class')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Category/Class</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>List</th>
                        <th class="text-right"><a href="/coa-tagging/create">Create</a></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2">
                            <p class="font-weight-bold">All</p>
                            @forelse ($coanull as $coatagging)
                                <div class="pl-5 py-1">
                                    {{ $coatagging->name }}
                                    
                                    <div class="float-none float-sm-right">
                                        <a href="#_" class="btn btn-link btn-sm" data-toggle="modal" data-target="#modal-coa-notes-{{ $coatagging->id }}">Notes</a>
                                        <a href="/coa-tagging/{{ $coatagging->id }}/edit" class="btn btn-link btn-sm">Edit</a>
                                        <form action="/coa-tagging/{{ $coatagging->id }}" method="post" class="d-inline-block">
                                            @csrf
                                            @method('delete')
                                            <input type="submit" class="btn btn-link btn-sm" value="Delete" onclick="return confirm('Are you sure?')">
                                        </form>
                                        <div class="modal fade" id="modal-coa-notes-{{ $coatagging->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-md" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title">Category / Class Notes</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="font-weight-light">{{ $coatagging->notes ?: __('messages.not_found') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="pl-5 py-1">{{ __('messages.empty') }}</div>
                            @endforelse
                        </td>
                    </tr>
                    @forelse ($companies as $item)
                        <tr>
                            <td colspan="2">
                                <p class="font-weight-bold">{{ $item->name }}</p>
                                @forelse ($item->coataggings as $coatagging)
                                    <div class="pl-5 py-1">
                                        {{ $coatagging->name }}
                                        
                                        <div class="float-none float-sm-right">
                                            <a href="#_" class="btn btn-link btn-sm" data-toggle="modal" data-target="#modal-coa-notes-{{ $coatagging->id }}">Notes</a>
                                            <a href="/coa-tagging/{{ $coatagging->id }}/edit" class="btn btn-link btn-sm">Edit</a>
                                            <form action="/coa-tagging/{{ $coatagging->id }}" method="post" class="d-inline-block">
                                                @csrf
                                                @method('delete')
                                                <input type="submit" class="btn btn-link btn-sm" value="Delete" onclick="return confirm('Are you sure?')">
                                            </form>

                                            <div class="modal fade" id="modal-coa-notes-{{ $coatagging->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-md" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header border-0">
                                                            <h5 class="modal-title">Category / Class Notes</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p class="font-weight-light">{{ $coatagging->notes ?: __('messages.not_found') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="pl-5 py-1">{{ __('messages.empty') }}</div>
                                @endforelse
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">{{ __('messages.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection