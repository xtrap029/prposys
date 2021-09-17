@extends('layouts.app-leaves')

@section('title', 'Edit Leave Reason')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Leave Reason</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/leaves-reason/{{ $reason->id }}" method="post">
                @csrf
                @method('put')
                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $reason->name }}" required>
                    @include('errors.inline', ['message' => $errors->first('name')])
                </div>
                <div class="form-group">
                    <label for="">Color</label>
                    <select name="color" class="form-control">
                        @foreach (config('global.site_colors') as $item2)
                            <option class="option--{{ $item2 }}" {{ $reason->color == $item2 ? 'selected' : '' }} value="{{ $item2 }}">{{ $item2 }}</option>                        
                        @endforeach
                    </select>
                    @include('errors.inline', ['message' => $errors->first('color')])
                </div>
                <a href="/leaves-reason">Cancel</a>
                <input type="submit" class="btn btn-primary float-right" value="Save">
            </form>
        </div>
    </section>
@endsection