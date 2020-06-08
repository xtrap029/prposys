@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="container">
        <h4 class="mb-4">Users</h4>

        <table class="table">
            <thead>
                <tr>
                    <th colspan="4">List</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td class="text-right">
                            <form action="/user/{{ $item->id }}" method="post" class="d-inline-block">
                                @csrf
                                @method('put')
                                <select name="role_id" class="form-control {{ $errors->has('role_id') ? 'is-invalid' : '' }}" onchange="this.form.submit()">
                                    <option value="">Inactive</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" {{ $item->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>                                        
                                    @endforeach
                                </select>
                                @include('errors.inline', ['message' => $errors->first('role_id')])
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection