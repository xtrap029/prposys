@extends('layouts.app-leaves')

@section('title', 'Department Peak')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Department Peak</h1>
                </div>
            </div>
        </div>
    </section>
    
    <section class="content">
        <div class="container-fluid">
            <div class="text-center mb-5">
                <h4>{{ $department->name }}</h4>
                <a href="/leaves-department">Back to Departments</a>
            </div>
            <table class="table table-striped table-responsive-sm">
                <thead>
                    <tr>
                        <th>From</th>
                        <th>To</th>
                        <th>Remarks</th>
                        <th>Created by</th>
                        <th>Updated by</th>
                        <th class="text-right"><a href="/leaves-department-peak/{{ $department->id }}/create">Create</a></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($department->departmentspeaks as $item)
                        <tr>
                            <td class="text-nowrap">{{ $item->date_from }}</td>
                            <td class="text-nowrap">{{ $item->date_to }}</td>
                            <td>{{ $item->remarks }}</td>
                            <td class="text-nowrap">{{ $item->owner->name }}</td>
                            <td class="text-nowrap">{{ $item->updatedby->name }}</td>
                            <td class="text-right text-nowrap">
                                <a href="/leaves-department-peak/edit/{{ $item->id }}">Edit</a>
                                <form action="/leaves-department-peak/{{ $item->id }}" method="post" class="d-inline-block">
                                    @csrf
                                    @method('delete')
                                    <input type="submit" class="btn btn-link btn-sm" value="Delete" onclick="return confirm('Are you sure?')">
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('messages.empty') }}</td>
                        </tr>
                    @endforelse
                    @foreach ($department->departmentspeaksdue as $item)
                        <tr>
                            <td class="text-nowrap text-gray text--line-through">{{ $item->date_from }}</td>
                            <td class="text-nowrap text-gray text--line-through">{{ $item->date_to }}</td>
                            <td class="text-gray text--line-through">{{ $item->remarks }}</td>
                            <td class="text-nowrap text-gray text--line-through">{{ $item->owner->name }}</td>
                            <td class="text-nowrap text-gray text--line-through">{{ $item->updatedby->name }}</td>
                            <td class="text-right text-nowrap">
                                <a href="/leaves-department-peak/edit/{{ $item->id }}">Edit</a>
                                <form action="/leaves-department-peak/{{ $item->id }}" method="post" class="d-inline-block">
                                    @csrf
                                    @method('delete')
                                    <input type="submit" class="btn btn-link btn-sm" value="Delete" onclick="return confirm('Are you sure?')">
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection