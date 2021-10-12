@extends('layouts.app-leaves')

@section('title', 'Edit Leave Adjustment')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Leave Adjustment</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/leaves-adjustment/{{ $adjustment->id }}" method="post">
                @csrf
                @method('put')
                <div class="form-row">
                    <div class="form-group col-md-8">
                        <label for="">Name</label>
                        <select name="user_id" class="form-control @error('user_id') is-invalid @enderror">
                            @foreach ($users as $item)
                                <option value="{{ $item->id }}" {{ $adjustment->user_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>                                        
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('user_id')])
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Quantity</label>
                        <input type="number" class="form-control @error('quantity') is-invalid @enderror" name="quantity" step="0.01" value="{{ $adjustment->quantity }}" required>
                        @include('errors.inline', ['message' => $errors->first('quantity')])
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Remarks</label>
                    <textarea name="remarks" rows="3" class="form-control @error('remarks') is-invalid @enderror" required>{{ $adjustment->remarks }}</textarea>
                    @include('errors.inline', ['message' => $errors->first('remarks')])
                </div>
                <a href="/leaves-adjustment">Cancel</a>
                <input type="submit" class="btn btn-primary float-right" value="Save">
            </form>
        </div>
    </section>
@endsection