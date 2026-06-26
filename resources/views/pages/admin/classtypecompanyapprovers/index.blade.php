@extends('layouts.app')

@section('title', 'Class Approvers')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Class Approvers</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="text-center mb-5">
                <img src="/storage/public/images/companies/{{ $company->logo }}" alt="" class="thumb thumb--sm d-block m-auto pb-3">
                <h4>{{ $company->name }}</h4>
                <a href="/company">Back to companies</a>
            </div>

            @forelse ($classTypes as $classType)
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong>{{ $classType->name }}</strong>
                        <span class="badge badge-secondary">{{ $classType->code }}</span>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <tbody>
                                @forelse ($approvers->get($classType->id, collect()) as $item)
                                    <tr>
                                        <td class="align-middle pl-3">{{ $item->user->name }}</td>
                                        <td class="align-middle text-right pr-3">
                                            <form action="/company-class-approver/{{ $item->id }}" method="post" class="d-inline-block">
                                                @csrf
                                                @method('delete')
                                                <input type="submit" class="btn btn-link btn-sm text-danger" value="Remove" onclick="return confirm('Remove this approver?')">
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted pl-3">No approvers assigned.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="px-3 py-2">
                                        <form action="/company-class-approver/{{ $company->id }}" method="post" class="form-inline">
                                            @csrf
                                            <input type="hidden" name="class_type_id" value="{{ $classType->id }}">
                                            <select name="user_id" class="form-control form-control-sm mr-2" required>
                                                <option value="">— Add Approver —</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-primary btn-sm">Add</button>
                                        </form>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @empty
                <p class="text-center text-muted">No class types are linked to this company.</p>
            @endforelse
        </div>
    </section>
@endsection
