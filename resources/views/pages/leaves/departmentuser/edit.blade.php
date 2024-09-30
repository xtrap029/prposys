@extends('layouts.app-people')

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
                <div class="form-group d-none">
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

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.css" integrity="sha512-0nkKORjFgcyxv3HbE4rzFUlENUMNqic/EzDIeYCgsKa/nwqr2B91Vu/tNAu4Q0cBuG4Xe/D1f/freEci/7GDRA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript">
        $('.chosen-select').chosen();
    </script>
@endsection