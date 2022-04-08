@extends('layouts.app-people')

@section('title', 'Create User')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create User</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/user" method="post" enctype="multipart/form-data">
                @csrf

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="home" aria-selected="true">Profile</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="account-tab" data-toggle="tab" href="#account" role="tab" aria-controls="profile" aria-selected="false">Account</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="sequence-tab" data-toggle="tab" href="#sequence" role="tab" aria-controls="contact" aria-selected="false">Sequence</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="leaves-tab" data-toggle="tab" href="#leaves" role="tab" aria-controls="contact" aria-selected="false">Leaves</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active m-4" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <h5 class="mb-4 text-right">Employment</h5>
                        <div class="form-row">
                            <div class="form-group col-lg-4">
                                <label for="">Employee Number</label>
                                <input type="text" class="form-control @error('e_emp_no') is-invalid @enderror" name="e_emp_no" value="{{ old('e_emp_no') }}">
                                @include('errors.inline', ['message' => $errors->first('e_emp_no')])
                            </div>
                            <div class="form-group col-lg-8">
                                <label for="">Employee Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                                @include('errors.inline', ['message' => $errors->first('name')])
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="">Hire Date</label>
                                <input type="date" class="form-control @error('e_hire_date') is-invalid @enderror" name="e_hire_date" value="{{ old('e_hire_date') }}">
                                @include('errors.inline', ['message' => $errors->first('e_hire_date')])
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="">Employment Status</label>
                                <select name="e_emp_status" class="form-control @error('e_emp_status') is-invalid @enderror">
                                    @foreach (config('global.employment_status') as $status)
                                        <option value="{{ $status }}" {{ old('e_emp_status') == $status ? 'selected' : '' }}>{{ $status }}</option>                                        
                                    @endforeach
                                </select>
                                @include('errors.inline', ['message' => $errors->first('e_emp_status')])
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="">Regularization Date</label>
                                <input type="date" class="form-control @error('e_reg_date') is-invalid @enderror" name="e_reg_date" value="{{ old('e_reg_date') }}">
                                @include('errors.inline', ['message' => $errors->first('e_reg_date')])
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="">Position / Title</label>
                                <input type="text" class="form-control @error('e_position') is-invalid @enderror" name="e_position" value="{{ old('e_position') }}">
                                @include('errors.inline', ['message' => $errors->first('e_position')])
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="">Rank</label>
                                <input type="text" class="form-control @error('e_rank') is-invalid @enderror" name="e_rank" value="{{ old('e_rank') }}">
                                @include('errors.inline', ['message' => $errors->first('e_rank')])
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="">Department</label>
                                <input type="text" class="form-control @error('e_department') is-invalid @enderror" name="e_department" value="{{ old('e_department') }}">
                                @include('errors.inline', ['message' => $errors->first('e_department')])
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="">Payroll Account Number</label>
                                <input type="text" class="form-control @error('e_payroll') is-invalid @enderror" name="e_payroll" value="{{ old('e_payroll') }}">
                                @include('errors.inline', ['message' => $errors->first('e_payroll')])
                            </div>
                        </div>
                        <hr class="mb-4">

                        <h5 class="mb-4 text-right">Personal</h5>
                        <div class="form-row">
                            <div class="form-group col-lg-4">
                                <label for="">Date of Birth</label>
                                <input type="date" class="form-control @error('e_dob') is-invalid @enderror" name="e_dob" value="{{ old('e_dob') }}">
                                @include('errors.inline', ['message' => $errors->first('e_dob')])
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="">Gender</label>
                                <select name="e_gender" class="form-control @error('e_gender') is-invalid @enderror">
                                    @foreach (config('global.gender') as $gender)
                                        <option value="{{ $gender }}" {{ old('e_gender') == $gender ? 'selected' : '' }}>{{ $gender }}</option>                                        
                                    @endforeach
                                </select>
                                @include('errors.inline', ['message' => $errors->first('e_gender')])
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="">Civil Status</label>
                                <select name="e_civil" class="form-control @error('e_civil') is-invalid @enderror">
                                    @foreach (config('global.civil_status') as $status)
                                        <option value="{{ $status }}" {{ old('e_civil') == $status ? 'selected' : '' }}>{{ $status }}</option>                                        
                                    @endforeach
                                </select>
                                @include('errors.inline', ['message' => $errors->first('e_civil')])
                            </div>
                            <div class="form-group col-12">
                                <label for="">Mailing Address</label>
                                <input type="text" class="form-control @error('e_mail_address') is-invalid @enderror" name="e_mail_address" value="{{ old('e_mail_address') }}">
                                @include('errors.inline', ['message' => $errors->first('e_mail_address')])
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="">Contact Number</label>
                                <input type="text" class="form-control @error('e_contact') is-invalid @enderror" name="e_contact" value="{{ old('e_contact') }}">
                                @include('errors.inline', ['message' => $errors->first('e_contact')])
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="">Personal Email</label>
                                <input type="email" class="form-control @error('e_email') is-invalid @enderror" name="e_email" value="{{ old('e_email') }}">
                                @include('errors.inline', ['message' => $errors->first('e_email')])
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="">Emergency Contact Person</label>
                                <input type="text" class="form-control @error('e_emergency_name') is-invalid @enderror" name="e_emergency_name" value="{{ old('e_emergency_name') }}">
                                @include('errors.inline', ['message' => $errors->first('e_emergency_name')])
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="">Emergency Contact Number</label>
                                <input type="text" class="form-control @error('e_emergency_contact') is-invalid @enderror" name="e_emergency_contact" value="{{ old('e_emergency_contact') }}">
                                @include('errors.inline', ['message' => $errors->first('e_emergency_contact')])
                            </div>
                        </div>

                        <hr class="mb-4">

                        <h5 class="mb-4 text-right">Government</h5>
                        <div class="form-row">
                            <div class="form-group col-lg-3">
                                <label for="">TIN</label>
                                <input type="text" class="form-control @error('e_tin') is-invalid @enderror" name="e_tin" value="{{ old('e_tin') }}">
                                @include('errors.inline', ['message' => $errors->first('e_tin')])
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="">SSS</label>
                                <input type="text" class="form-control @error('e_sss') is-invalid @enderror" name="e_sss" value="{{ old('e_sss') }}">
                                @include('errors.inline', ['message' => $errors->first('e_sss')])
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="">PHIC</label>
                                <input type="text" class="form-control @error('e_phic') is-invalid @enderror" name="e_phic" value="{{ old('e_phic') }}">
                                @include('errors.inline', ['message' => $errors->first('e_phic')])
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="">HDMF</label>
                                <input type="text" class="form-control @error('e_hmdf') is-invalid @enderror" name="e_hmdf" value="{{ old('e_hmdf') }}">
                                @include('errors.inline', ['message' => $errors->first('e_hmdf')])
                            </div>                            
                        </div>
                    </div>


                    <div class="tab-pane fade m-4" id="account" role="tabpanel" aria-labelledby="account-tab">
                        <div class="form-group">
                            <label for="">Account Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                            @include('errors.inline', ['message' => $errors->first('email')])
                        </div>
                        <div class="form-group">
                            <label for="avatar">Avatar</label>
                            <input type="file" name="avatar" class="form-control-file @error('avatar') is-invalid @enderror" required>
                            @include('errors.inline', ['message' => $errors->first('avatar')])
                        </div>
                        <div class="form-group">
                            <label for="">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                            @include('errors.inline', ['message' => $errors->first('password')])
                        </div>
                        <div class="form-group">
                            <label for="">Confirm Password</label>
                            <input type="password" class="form-control" name="password_confirmation" required>
                        </div>
                        <div class="form-group">
                            <label for="">User Level</label>
                            <select name="ua_level_id" class="form-control" required>
                                @foreach ($levels as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">User Level Options</label>
                            <select name="ua_level_control[]" class="form-control form-control-sm chosen-select" multiple>
                                @foreach ($levels as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="card col-md-12 mt-4 d-none">
                            <div class="card-header">
                                App Access
                                <span class="float-right">
                                    Select All
                                    <input type="checkbox" class="check vlign--middle ml-2" id="checkApp" checked>
                                </span>
                            </div>
                            <div class="card-body pb-1 row">
                                @foreach (config('global.apps') as $item)
                                    <div class="col-md-6 col-xl-4">
                                        <div class="callout py-1 mx-1 row">
                                            <div class="col-2">
                                                <input type="checkbox" name="app_control[]" value="{{ $item }}" class="check-app vlign--baseline-middle m-auto outline-0" checked>
                                            </div>
                                            <div class="col-10 mt-2">
                                                <h6>{{ ucwords($item) }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>



                    <div class="tab-pane fade m-4" id="sequence" role="tabpanel" aria-labelledby="sequence-tab">
                        <div class="form-row d-none">
                            <div class="form-group col-lg-8 mb-3">
                                <label for="">Role</label>
                                <select name="role_id" class="form-control @error('role_id') is-invalid @enderror">
                                    {{-- <option value="">Inactive</option> --}}
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>                                        
                                    @endforeach
                                </select>
                                @include('errors.inline', ['message' => $errors->first('role_id')])
                            </div>
                            <div class="form-group col-lg-4 mb-3">
                                <label for="">Read Only Access?</label>
                                <select name="is_read_only" class="form-control @error('is_read_only') is-invalid @enderror">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                <small>Not applicable for Manager role.</small>
                                @include('errors.inline', ['message' => $errors->first('is_read_only')])
                            </div>
                        </div>
                        <div class="card col-md-12 mt-4">
                            <div class="card-header">
                                Company Access
                                <span class="float-right">
                                    Select All
                                    <input type="checkbox" class="check vlign--middle ml-2" id="checkCompany" checked>
                                </span>
                            </div>
                            <div class="card-body pb-1 row">
                                @foreach ($companies as $item)
                                    <div class="col-md-6">
                                        <div class="callout py-1 mx-1 row">
                                            <div class="col-2">
                                                <input type="checkbox" name="company_control[]" value="{{ $item->id }}" class="check-company vlign--baseline-middle m-auto outline-0" checked>
                                            </div>
                                            <div class="col-10 mt-2">
                                                <h6>{{ ucwords($item->name) }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-md-6">
                                <label for="">Unliquidated PR amount limit</label>
                            </div>
                            <div class="col-md-6">
                                <input type="number" class="form-control @error('LIMIT_UNLIQUIDATEDPR_AMOUNT') is-invalid @enderror" name="LIMIT_UNLIQUIDATEDPR_AMOUNT" value="{{ old('LIMIT_UNLIQUIDATEDPR_AMOUNT') }}" placeholder="{{ __('messages.leave_blank') }}">
                                @include('errors.inline', ['message' => $errors->first('LIMIT_UNLIQUIDATEDPR_AMOUNT')])
                            </div>
                        </div>
                        <div class="form-row mb-3">
                            <div class="col-md-6">
                                <label for="">Unliquidated PR transactions limit</label>
                            </div>
                            <div class="col-md-6">
                                <input type="number" class="form-control @error('LIMIT_UNLIQUIDATEDPR_COUNT') is-invalid @enderror" name="LIMIT_UNLIQUIDATEDPR_COUNT" value="{{ old('LIMIT_UNLIQUIDATEDPR_COUNT') }}" placeholder="{{ __('messages.leave_blank') }}">
                                @include('errors.inline', ['message' => $errors->first('LIMIT_UNLIQUIDATEDPR_COUNT')])
                            </div>
                        </div>
                    </div>



                    <div class="tab-pane fade m-4" id="leaves" role="tabpanel" aria-labelledby="leaves-tab">...</div>
                </div>    
                <div class="m-4 pb-5">
                    <a href="/user">Cancel</a>
                    <input type="submit" class="btn btn-primary float-right" value="Save">
                </div>
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
        $(function() {
            $("#checkApp").click(function () {
                $(".check-app").prop('checked', $(this).prop('checked'));
            });
            $("#checkCompany").click(function () {
                $(".check-company").prop('checked', $(this).prop('checked'));
            });
        })
    </script>
@endsection