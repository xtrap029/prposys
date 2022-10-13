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
                    <div class="alert alert-default-warning rounded" role="alert">
                        <h5>
                            <span class="text-danger">
                                <i class="nav-icon material-icons icon--list mr-1">warning</i>
                                Changing status is irreversible!
                            </span>
                            Are you sure?
                        </h5>
                        Reverted transaction's attachments might not be recovered depending on selected status.
                        Transaction <code>{{ app('request')->input('trans') }}</code>'s
                        current status is <code>{{ $transaction->status->name }}</code>.
                    </div>
                    <div class="form-group">
                        <select name="status" class="form-control" required>
                            <option value="">- Select Status -</option>
                            @foreach ($status as $item)
                                @if (in_array($item->id, config('global.status_checkpoint')[$transaction->status_id]))
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"" placeholder="{{ __('messages.password_required') }}" required>
                        @include('errors.inline', ['message' => $errors->first('password')])
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Revert" class="btn btn-primary">
                        <a href="/control-panel/revert-status/" class="float-right btn btn-secondary">Cancel</a>
                    </div>
                </form>
            @else
            <form action="" method="get" class="col-md-7">
                    <div class="alert alert-default-warning rounded d-none" role="alert">
                        <i class="nav-icon material-icons icon--list mr-1">warning</i>
                        Only Regular transactions can choose a status to revert to. All other transaction types can only revert to its previous status.
                     </div>
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


            <div class="container mt-5">
                <h1>Test Directory</h1>
                <iframe src="https://drive.google.com/embeddedfolderview?id=1zu6gDpBz0SjCr_ZLiGd5YMhu0VviUbBe#list" style="width:100%; height:600px; border:0;"></iframe>
            </div>
        </div>
    </section>
@endsection