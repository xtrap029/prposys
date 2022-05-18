@extends('layouts.app')

@section('title', 'Revert Status')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Revert Status</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid row">
            @if ($transaction)
                <form action="" method="post" class="col-md-7">
                    @csrf
                    <input type="hidden" name="id" value="{{ $transaction->id }}">
                    <div class="alert alert-dark rounded">
                        <h5><i class="nav-icon material-icons icon--list mr-1">warning</i> Are you sure?</h5>
                        Transaction <code>{{ app('request')->input('trans') }}</code>'s
                        status <code>{{ $transaction->status->name }}</code>
                        will be reverted to <code>{{ $transaction->status_prev->name }}</code>.
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="{{ __('messages.password_required') }}" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Revert" class="btn btn-primary">
                        <a href="/control-panel/revert-status/" class="float-right btn btn-secondary">Cancel</a>
                    </div>
                </form>
            @else
                <form action="" method="get" class="col-md-7">
                    <div class="form-group">
                        <label for="">Company</label>
                        <select name="company_id" class="form-control">
                            @foreach ($companies as $item)
                                <option value="{{ $item->id }}" {{ app('request')->input('company_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Transaction #</label>
                        <input type="text" name="trans" class="form-control" value="{{ app('request')->input('trans') }}">
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Search" class="btn btn-secondary">
                    </div>
                </form>
            @endif
        </div>
    </section>
@endsection