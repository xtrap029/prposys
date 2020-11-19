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
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>List</th>
                        <th class="text-right">
                            <a href="/bank/create" class="mr-4">Create Bank</a>
                            <a href="/bank-branch/create">Create Account</a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($banks as $item)
                        <tr>
                            <td>
                                <p class="font-weight-bold">{{ $item->name }}</p>
                                <div class="pl-5 py-1">
                                    @forelse ($item->bankbranches as $branch)
                                        <a href="/bank-branch/edit/{{ $branch->id }}" class="d-block">{{ $branch->name }}</a>
                                    @empty
                                        <i class="text-gray">{{ __('messages.empty') }}</i>
                                    @endforelse
                                </div>
                            </td>
                            <td class="text-right">
                                <a href="/bank/{{ $item->id }}/edit" class="btn btn-link btn-sm">Edit</a>
                                <form action="/bank/{{ $item->id }}" method="post" class="d-inline-block">
                                    @csrf
                                    @method('delete')
                                    <input type="submit" class="btn btn-link btn-sm" value="Delete" onclick="return confirm('Are you sure?')">
                                </form>
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