@extends('layouts.app-center')

@section('content')
<div class="row flex-column flex-lg-row vh-100 m-0">
    <div class="col-lg-4 offset-lg-4 bg-white">
        <div class="p-5 m-lg-5 text-center">
            <h4 class="text-center mb-4 text-bold">Enter Access Key</h4>
            <p>This attachment is protected. Please enter the access key that was sent to you to view it.</p>

            @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-block">
                    {{ $message }}
                </div>
            @endif

            @error('key')
                <div class="alert alert-danger alert-block">
                    {{ $message }}
                </div>
            @enderror

            <form method="POST" action="{{ route('attachments.verify', ['type' => $type, 'filename' => $filename]) }}" class="p-3">
                @csrf

                <div class="form-group text-left">
                    <label for="key">Access Key</label>
                    <input id="key" type="text" class="form-control" name="key" required autofocus>
                </div>

                <div class="form-group pt-3 mb-0">
                    <button type="submit" class="btn btn-primary btn-block">
                        View Attachment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
