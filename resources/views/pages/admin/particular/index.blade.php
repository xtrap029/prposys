@extends('layouts.app')

@section('title', 'Particulars')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Particulars</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pr-tab" data-toggle="tab" href="#pr" role="tab" aria-controls="pr" aria-selected="true">Payment Release</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="po-tab" data-toggle="tab" href="#po" role="tab" aria-controls="po" aria-selected="false">Purchase Order</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="pr" role="tabpanel" aria-labelledby="pr-tab">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>List</th>
                                <th class="text-right"><a href="/particular/create?type=pr">Create</a></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($particulars_pr as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td class="text-right">
                                        <a href="#_" class="btn btn-link btn-sm" data-toggle="modal" data-target="#modal-particulars-notes-{{ $item->id }}">Notes</a>
                                        <a href="/particular/{{ $item->id }}/edit" class="btn btn-link btn-sm">Edit</a>
                                        <form action="/particular/{{ $item->id }}" method="post" class="d-inline-block">
                                            @csrf
                                            @method('delete')
                                            <input type="submit" class="btn btn-link btn-sm" value="Delete" onclick="return confirm('Are you sure?')">
                                        </form>
                                        <div class="modal fade" id="modal-particulars-notes-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-md" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title">Particulars Notes</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="font-weight-light text-left">{{ $item->notes ?: __('messages.not_found') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No results found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="po" role="tabpanel" aria-labelledby="po-tab">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>List</th>
                                <th class="text-right"><a href="/particular/create?type=po">Create</a></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($particulars_po as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td class="text-right">
                                        <a href="#_" class="btn btn-link btn-sm" data-toggle="modal" data-target="#modal-particulars-notes-{{ $item->id }}">Notes</a>
                                        <a href="/particular/{{ $item->id }}/edit" class="btn btn-link btn-sm">Edit</a>
                                        <form action="/particular/{{ $item->id }}" method="post" class="d-inline-block">
                                            @csrf
                                            @method('delete')
                                            <input type="submit" class="btn btn-link btn-sm" value="Delete" onclick="return confirm('Are you sure?')">
                                        </form>
                                        <div class="modal fade" id="modal-particulars-notes-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-md" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title">Notes</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="font-weight-light text-left">{{ $item->notes ?: __('messages.not_found') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
            </div>
        </div>
    </section>
@endsection