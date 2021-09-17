@extends('layouts.app-leaves')

@section('title', 'My Department Peak')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>My Department Peak</h1>
                </div>
            </div>
        </div>
    </section>
    
    <section class="content">
        <div class="container-fluid">
            <table class="table table-responsive-sm">
                @foreach (Auth::user()->departmentuser->sortBy('department.name') as $department_user)
                    <tbody>
                        <tr class="bg-gray-dark">
                            <td colspan="5"><b>{{ $department_user->department->name }}</b> - {{ $department_user->is_approver ? 'Approver' : 'Member' }}</td>
                            <td class="text-right">
                                @if ($department_user->is_approver)
                                    <a href="/leaves-department-peak/my/{{ $department_user->department_id }}/create" class="text-info mr-2">
                                        Create
                                    </a>
                                @endif                        
                            </td>
                        </tr>
                        <tr class="bg-gray">
                            <td>From</td>
                            <td>To</td>
                            <td>Remarks</td>
                            <td>Created by</td>
                            <td>Updated by</td>
                            <td></td>
                        </tr>
                        @forelse ($department_user->department->departmentspeaks as $item)
                            <tr>
                                <td class="text-nowrap">{{ $item->date_from }}</td>
                                <td class="text-nowrap">{{ $item->date_to }}</td>
                                <td>{{ $item->remarks }}</td>
                                <td class="text-nowrap">{{ $item->owner->name }}</td>
                                <td class="text-nowrap">{{ $item->updatedby->name }}</td>
                                <td class="text-right text-nowrap">
                                    @if ($department_user->is_approver)
                                        <a href="/leaves-department-peak/my/edit/{{ $item->id }}" class="vlign--top">Edit</a>
                                        <form action="/leaves-department-peak/my/{{ $item->id }}" method="post" class="d-inline-block">
                                            @csrf
                                            @method('delete')
                                            <input type="submit" class="btn btn-link mt-0 pt-0" value="Delete" onclick="return confirm('Are you sure?')">
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            @if ($department_user->department->departmentspeaksdue->count() == 0)
                                <tr>
                                    <td colspan="6" class="text-center">{{ __('messages.empty') }}</td>
                                </tr>                                
                            @endif
                        @endforelse
                        @foreach ($department_user->department->departmentspeaksdue as $item)
                            <tr>
                                <td class="text-nowrap text-gray text--line-through">{{ $item->date_from }}</td>
                                <td class="text-nowrap text-gray text--line-through">{{ $item->date_to }}</td>
                                <td class="text-gray text--line-through">{{ $item->remarks }}</td>
                                <td class="text-nowrap text-gray text--line-through">{{ $item->owner->name }}</td>
                                <td class="text-nowrap text-gray text--line-through">{{ $item->updatedby->name }}</td>
                                <td class="text-right text-nowrap">
                                    @if ($department_user->is_approver)
                                        <a href="/leaves-department-peak/my/edit/{{ $item->id }}" class="vlign--top">Edit</a>
                                        <form action="/leaves-department-peak/my/{{ $item->id }}" method="post" class="d-inline-block">
                                            @csrf
                                            @method('delete')
                                            <input type="submit" class="btn btn-link mt-0 pt-0" value="Delete" onclick="return confirm('Are you sure?')">
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tbody>
                        <tr>
                            <td class="py-3"></td>
                        </tr>
                    </tbody>
                @endforeach
            </table>
        </div>
    </section>
@endsection