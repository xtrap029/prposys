@extends('layouts.app')

@section('title', 'Bank')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Bank</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <table class="table table-striped table-responsive-sm">
                <thead>
                    <tr>
                        <th>List</th>
                        <th class="text-right text-nowrap">
                            <a href="/bank/create" class="mr-4">Create Bank</a>
                            <a href="/bank-branch/create">Create Account</a>
                        </th>
                    </tr>
                </thead>
            </table>
            <div class="accordion">
                @forelse ($banks as $item)
                    <div class="accordion-item mb-1">
                        <h2 class="accordion-header mb-1">
                            <div class="row">
                                <div class="col-md-10">
                                    <button class="accordion-button btn btn-block text-left btn-default" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $item->id }}" aria-expanded="false" aria-controls="collapse{{ $item->id }}">
                                        {{ $item->name }}
                                    </button>
                                </div>
                                <div class="col-md-1">
                                    <a href="/bank/{{ $item->id }}/edit" class="btn btn-primary btn-sm btn-block">Edit</a>
                                </div>
                                <div class="col-md-1">
                                    <form action="/bank/{{ $item->id }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <input type="submit" class="btn btn-danger btn-sm btn-block" value="Delete" onclick="return confirm('Are you sure?')">
                                    </form>
                                </div>
                            </div>
                        </h2>
                        <div id="collapse{{ $item->id }}" class="accordion-collapse collapse p-3 border bg-gray rounded">
                            <div class="accordion-body">
                                @forelse ($item->bankbranches as $branch)
                                    <a href="/bank-branch/edit/{{ $branch->id }}" class="d-block text-info">{{ $branch->name }}</a>
                                @empty
                                    <i class="text-white">{{ __('messages.empty') }}</i>
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