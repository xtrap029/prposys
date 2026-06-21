@extends('layouts.app-people')

@section('title', 'Manage Hierarchy')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1>Manage Hierarchy</h1>
                </div>
                <div class="col-sm-4 mt-2 mt-sm-0 text-right">
                    <a href="/people-hierarchy?company_id={{ $company_id }}" class="btn btn-primary">Preview</a>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <table class="table table-striped table-responsive-sm">
                <thead>
                    <tr>
                       <th>
                            @if ($hierarchy)
                                <a href="/people-hierarchy/manage?company_id={{ $company_id }}">
                                    <i class="nav-icon material-icons icon--list">home</i>
                                </a> &gt;

                                @php
                                    $layers = [];
                                    $current = $hierarchy;
                                    $layers[] = $current;

                                    while ($current->parent_id) {
                                        $current = $current->parent->hierarchy;
                                        $layers[] = $current;
                                    }

                                    $layers = array_reverse($layers);
                                @endphp

                                @foreach ($layers as $key => $layer)
                                    @if ($key < count($layers) - 1)
                                        <a href="/people-hierarchy/manage/{{ $layer->user_id }}?company_id={{ $company_id }}">
                                            {{ $layer->user->name }}
                                        </a>
                                        &gt;
                                    @else
                                        <span class="text-muted font-weight-bold">{{ $layer->user->name }}</span>
                                    @endif
                                @endforeach
                            @endif
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($hierarchies as $item)
                        <tr>
                            <td class="text-nowrap">
                                <form action="/people-hierarchy/manage/{{ $item->id }}/user" method="post" class="d-inline-flex align-items-center">
                                    @csrf
                                    @method('put')
                                    <input type="hidden" name="company_id" value="{{ $company_id }}">
                                    <img src="/storage/public/images/users/{{ $item->user->avatar }}" class="img-circle img-sm mr-2" alt="User Image">
                                    <div>
                                        <select name="user_id" class="form-control form-control-sm" style="width:200px" onchange="this.form.submit()">
                                            @if (!$users->contains('id', $item->user_id))
                                                <option value="{{ $item->user_id }}" selected>{{ $item->user->name }}</option>
                                            @endif
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" {{ (int)$item->user_id === (int)$user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">{{ $item->user->e_position }}</small>
                                    </div>
                                </form>
                            </td>
                            <td class="text-right text-nowrap">
                                <a href="/people-hierarchy/manage/{{ $item->user_id }}?company_id={{ $company_id }}" class="btn btn-link btn-sm d-inline-block">Open</a>
                                @if ($item->parent_id)
                                    <form action="/people-hierarchy/manage/{{ $item->id }}" method="post" class="d-inline-block">
                                        @csrf
                                        @method('delete')
                                        <input type="submit" class="btn btn-link btn-sm" value="Delete" onclick="return confirm('Are you sure?')">
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="4">{{ __('messages.empty') }}</td>
                        </tr>
                    @endforelse
                    <tr>
                        <td colspan="2">
                            @if ($hierarchy)
                                <form action="/people-hierarchy/manage/{{ $hierarchy->user_id }}" method="post">
                                    @csrf
                                    <input type="hidden" name="company_id" value="{{ $company_id }}">
                                    <div class="form-row justify-content-end">
                                        <select name="user_id" class="form-control w-auto mr-2">
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        <input type="submit" class="btn btn-primary" value="Add">
                                    </div>
                                </form>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
@endsection