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
            </table>
            <div class="accordion">
                <div class="accordion-item mb-1">
                    <h2 class="accordion-header mb-1">
                        <button class="accordion-button btn btn-block text-left btn-default" type="button" data-bs-toggle="collapse" data-bs-target="#collapseall" aria-expanded="false" aria-controls="collapseall">
                            All
                        </button>
                    </h2>
                    <div id="collapseall" class="accordion-collapse collapse p-3 border bg-gray rounded">
                        <div class="accordion-body">
                            @forelse ($coanull as $coatagging)
                                <div class="py-2">
                                    {{ $coatagging->name }}                                    
                                    <div class="float-none float-sm-right">
                                        <a href="#_" class="btn btn-warning btn-sm mx-1" data-toggle="modal" data-target="#modal-coa-notes-{{ $coatagging->id }}">Notes</a>
                                        <a href="/coa-tagging/{{ $coatagging->id }}/edit" class="btn btn-primary btn-sm mx-1">Edit</a>
                                        <form action="/coa-tagging/{{ $coatagging->id }}" method="post" class="d-inline-block">
                                            @csrf
                                            @method('delete')
                                            <input type="submit" class="btn btn-danger btn-sm mx-1" value="Delete" onclick="return confirm('Are you sure?')">
                                        </form>
                                        <div class="modal fade" id="modal-coa-notes-{{ $coatagging->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-md" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title">Notes</h5>
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
                        </div>
                    </div>
                </div>
                @forelse ($companies as $item)
                    <div class="accordion-item mb-1">
                        <h2 class="accordion-header mb-1">
                            <button class="accordion-button btn btn-block text-left btn-default" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $item->id }}" aria-expanded="false" aria-controls="collapse{{ $item->id }}">
                                {{ $item->name }}
                            </button>
                        </h2>
                        <div id="collapse{{ $item->id }}" class="accordion-collapse collapse p-3 border bg-gray rounded">
                            <div class="accordion-body">
                                @forelse ($item->coataggings as $coatagging)
                                    <div class="py-2">
                                        {{ $coatagging->name }}                                        
                                        <div class="float-none float-sm-right">
                                            <a href="#_" class="btn btn-warning btn-sm mx-1" data-toggle="modal" data-target="#modal-coa-notes-{{ $coatagging->id }}">Notes</a>
                                            <a href="/coa-tagging/{{ $coatagging->id }}/edit" class="btn btn-primary btn-sm mx-1">Edit</a>
                                            <form action="/coa-tagging/{{ $coatagging->id }}" method="post" class="d-inline-block">
                                                @csrf
                                                @method('delete')
                                                <input type="submit" class="btn btn-danger btn-sm mx-1" value="Delete" onclick="return confirm('Are you sure?')">
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
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
@endsection