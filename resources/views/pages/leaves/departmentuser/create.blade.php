@extends('layouts.app-leaves')

@section('title', 'Create Department Member')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create {{ $department_sel->name }} Member</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/leaves-department-user" method="post">
                @csrf
                <div class="form-group">
                    <label for="">Member</label>
                    <select name="user_id" class="form-control">
                        @foreach ($users as $data)
                            <option value="{{ $data->id }}" {{ old('user_id') ? 'selected' : '' }}>{{ $data->name }}</option>
                        @endforeach
                    </select>
                    @include('errors.inline', ['message' => $errors->first('user_id')])
                </div>
                <input type="hidden" name="department_id" value="{{ $department_sel->id }}">
                <div class="form-group">
                    <label for="">Is Approver?</label>
                    <select name="is_approver" class="form-control">
                        <option value="0" selected>No</option>
                        <option value="1">Yes</option>
                    </select>    
                </div>
                <a href="/leaves-department-user">Cancel</a>
                <input type="submit" class="btn btn-primary float-right" value="Save">
            </form>
        </div>
    </section>
@endsection