@extends('layouts.app')

@section('title', 'Company Projects')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Company Projects</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="text-center mb-5">
                <img src="/storage/public/images/companies/{{ $company->logo }}" alt="" class="thumb thumb--sm d-block m-auto">
                {{ $company->name }}<br>
                <a href="/company">Back to companies</a>
            </div>
    
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>List</th>
                        <th class="text-right"><a href="/company-project/{{ $company->id }}/create">Create</a></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($company_projects as $item)
                        <tr>
                            <td class="align-middle">{{ $item->project }}</td>
                            <td class="align-middle text-right">
                                <a href="/company-project/edit/{{ $item->id }}" class="btn btn-link btn-sm">Edit</a>
                                <form action="/company-project/{{ $item->id }}" method="post" class="d-inline-block">
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