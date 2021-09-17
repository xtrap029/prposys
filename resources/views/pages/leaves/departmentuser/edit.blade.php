@extends('layouts.app-leaves')

@section('title', 'Edit Member')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit {{ $department_user->department->name }} Member</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/leaves-department-user/{{ $department_user->id }}" method="post">
                @csrf
                @method('put')
                <div class="form-group">
                    <label for="">Member</label>
                    <h5 class="font-weight-bold">{{ $department_user->user->name }}</h5>
                </div>
                <div class="form-group">
                    <label for="">Is Approver?</label>
                    <select name="is_approver" class="form-control">
                        <option value="0" {{ $department_user->is_approver == 0 ? 'selected' : '' }}>No</option>
                        <option value="1" {{ $department_user->is_approver == 1 ? 'selected' : '' }}>Yes</option>
                    </select>    
                </div>
                <a href="/leaves-department">Cancel</a>
                <input type="submit" class="btn btn-primary float-right ml-2" value="Save">
                <input type="submit" class="btn btn-danger float-right" value="Delete" onclick="return confirm('Are you sure?')" form="delete-member">
            </form>
            <form action="/leaves-department-user/{{ $department_user->id }}" method="post" class="d-inline-block" id="delete-member">
                @csrf
                @method('delete')
            </form>
        </div>
    </section>
@endsection