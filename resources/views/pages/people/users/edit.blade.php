@extends('layouts.app-people')

@section('title', 'Edit User')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit User</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/user/{{ $user->id }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')

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
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="others-tab" data-toggle="tab" href="#others" role="tab" aria-controls="contact" aria-selected="false">Others</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active m-4" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <h5 class="mb-4 text-right">Employment</h5>
                        <div class="form-row">
                            <div class="form-group col-lg-4">
                                <label for="">Employee Number</label>
                                <input type="text" class="form-control @error('e_emp_no') is-invalid @enderror" name="e_emp_no" value="{{ $user->e_emp_no }}">
                                @include('errors.inline', ['message' => $errors->first('e_emp_no')])
                            </div>
                            <div class="form-group col-lg-8">
                                <label for="">Employee Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name }}" required>
                                @include('errors.inline', ['message' => $errors->first('name')])
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="">Hire Date</label>
                                <input type="date" class="form-control @error('e_hire_date') is-invalid @enderror" name="e_hire_date" value="{{ $user->e_hire_date }}">
                                @include('errors.inline', ['message' => $errors->first('e_hire_date')])
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="">Employment Status</label>
                                <select name="e_emp_status" class="form-control @error('e_emp_status') is-invalid @enderror">
                                    @foreach (config('global.employment_status') as $status)
                                        <option value="{{ $status }}" {{ $user->e_emp_status == $status ? 'selected' : '' }}>{{ $status }}</option>                                        
                                    @endforeach
                                </select>
                                @include('errors.inline', ['message' => $errors->first('e_emp_status')])
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="">Regularization Date</label>
                                <input type="date" class="form-control @error('e_reg_date') is-invalid @enderror" name="e_reg_date" value="{{ $user->e_reg_date }}">
                                @include('errors.inline', ['message' => $errors->first('e_reg_date')])
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="">Position / Title</label>
                                <input type="text" class="form-control @error('e_position') is-invalid @enderror" name="e_position" value="{{ $user->e_position }}">
                                @include('errors.inline', ['message' => $errors->first('e_position')])
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="">Rank</label>
                                <input type="text" class="form-control @error('e_rank') is-invalid @enderror" name="e_rank" value="{{ $user->e_rank }}">
                                @include('errors.inline', ['message' => $errors->first('e_rank')])
                            </div>
                            <div class="form-group col-lg-6 d-none">
                                <label for="">Department</label>
                                <input type="text" class="form-control @error('e_department') is-invalid @enderror" name="e_department" value="{{ $user->e_department }}">
                                @include('errors.inline', ['message' => $errors->first('e_department')])
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="">Payroll Account Number</label>
                                <input type="text" class="form-control @error('e_payroll') is-invalid @enderror" name="e_payroll" value="{{ $user->e_payroll }}">
                                @include('errors.inline', ['message' => $errors->first('e_payroll')])
                            </div>
                        </div>
                        <hr class="mb-4">

                        <h5 class="mb-4 text-right">Personal</h5>
                        <div class="form-row">
                            <div class="form-group col-lg-4">
                                <label for="">Date of Birth</label>
                                <input type="date" class="form-control @error('e_dob') is-invalid @enderror" name="e_dob" value="{{ $user->e_dob }}">
                                @include('errors.inline', ['message' => $errors->first('e_dob')])
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="">Gender</label>
                                <select name="e_gender" class="form-control @error('e_gender') is-invalid @enderror">
                                    @foreach (config('global.gender') as $gender)
                                        <option value="{{ $gender }}" {{ $user->e_gender == $gender ? 'selected' : '' }}>{{ $gender }}</option>                                        
                                    @endforeach
                                </select>
                                @include('errors.inline', ['message' => $errors->first('e_gender')])
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="">Civil Status</label>
                                <select name="e_civil" class="form-control @error('e_civil') is-invalid @enderror">
                                    @foreach (config('global.civil_status') as $status)
                                        <option value="{{ $status }}" {{ $user->e_civil == $status ? 'selected' : '' }}>{{ $status }}</option>                                        
                                    @endforeach
                                </select>
                                @include('errors.inline', ['message' => $errors->first('e_civil')])
                            </div>
                            <div class="form-group col-12">
                                <label for="">Mailing Address</label>
                                <input type="text" class="form-control @error('e_mail_address') is-invalid @enderror" name="e_mail_address" value="{{ $user->e_mail_address }}">
                                @include('errors.inline', ['message' => $errors->first('e_mail_address')])
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="">Contact Number</label>
                                <input type="text" class="form-control @error('e_contact') is-invalid @enderror" name="e_contact" value="{{ $user->e_contact }}">
                                @include('errors.inline', ['message' => $errors->first('e_contact')])
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="">Personal Email</label>
                                <input type="email" class="form-control @error('e_email') is-invalid @enderror" name="e_email" value="{{ $user->e_email }}">
                                @include('errors.inline', ['message' => $errors->first('e_email')])
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="">Emergency Contact Person</label>
                                <input type="text" class="form-control @error('e_emergency_name') is-invalid @enderror" name="e_emergency_name" value="{{ $user->e_emergency_name }}">
                                @include('errors.inline', ['message' => $errors->first('e_emergency_name')])
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="">Emergency Contact Number</label>
                                <input type="text" class="form-control @error('e_emergency_contact') is-invalid @enderror" name="e_emergency_contact" value="{{ $user->e_emergency_contact }}">
                                @include('errors.inline', ['message' => $errors->first('e_emergency_contact')])
                            </div>
                        </div>

                        <hr class="mb-4">

                        <h5 class="mb-4 text-right">Government</h5>
                        <div class="form-row">
                            <div class="form-group col-lg-3">
                                <label for="">TIN</label>
                                <input type="text" class="form-control @error('e_tin') is-invalid @enderror" name="e_tin" value="{{ $user->e_tin }}">
                                @include('errors.inline', ['message' => $errors->first('e_tin')])
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="">SSS</label>
                                <input type="text" class="form-control @error('e_sss') is-invalid @enderror" name="e_sss" value="{{ $user->e_sss }}">
                                @include('errors.inline', ['message' => $errors->first('e_sss')])
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="">PHIC</label>
                                <input type="text" class="form-control @error('e_phic') is-invalid @enderror" name="e_phic" value="{{ $user->e_phic }}">
                                @include('errors.inline', ['message' => $errors->first('e_phic')])
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="">HDMF</label>
                                <input type="text" class="form-control @error('e_hmdf') is-invalid @enderror" name="e_hmdf" value="{{ $user->e_hmdf }}">
                                @include('errors.inline', ['message' => $errors->first('e_hmdf')])
                            </div>                            
                        </div>
                    </div>
                    <div class="tab-pane fade m-4" id="account" role="tabpanel" aria-labelledby="account-tab">
                        <div class="form-group">
                            <label for="">Email Address</label>
                            <span class="font-weight-bold ml-3">{{ $user->email }}</span>
                        </div>
                        <div class="form-group">
                            <label for="avatar" class="d-block">Avatar</label>
                            <img src="/storage/public/images/users/{{ $user->avatar }}" alt="" class="thumb thumb--sm img-thumbnail">
                            <input type="file" name="avatar" class="form-control-file @error('avatar') is-invalid @enderror d-inline-block w-auto ml-3">
                            @include('errors.inline', ['message' => $errors->first('avatar')])
                        </div>
                        <div class="form-group">
                            <label for="">Change Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                            @include('errors.inline', ['message' => $errors->first('password')])
                        </div>
                        <div class="form-group">
                            <label for="">Confirm Change Password</label>
                            <input type="password" class="form-control" name="password_confirmation">
                        </div>
                        <div class="form-group">
                            <label for="">User Level</label>
                            <select name="ua_level_id" class="form-control" required>
                                @foreach ($levels as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == $user->ua_level_id ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">User Level Options</label>
                            <select name="ua_level_control[]" class="form-control form-control-sm chosen-select" multiple>
                                @foreach ($levels as $item)
                                    <option value="{{ $item->id }}" {{ in_array($item->id, explode(',',$user->ua_levels)) ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">User Travel Roles</label>
                            <select name="travel_role_control[]" class="form-control form-control-sm chosen-select" multiple>
                                @foreach ($travel_roles as $item)
                                    <option value="{{ $item->id }}" {{ in_array($item->id, explode(',',$user->travel_roles)) ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="card col-md-12 mt-4">
                            <div class="card-header">
                                App Access
                                <span class="float-right">
                                    Select All
                                    <input type="checkbox" class="check vlign--middle ml-2" id="checkApp">
                                </span>
                            </div>
                            <div class="card-body pb-1 row">
                                @foreach (config('global.apps') as $item)
                                    <div class="col-md-6 col-xl-4">
                                        <div class="callout py-1 mx-1 row">
                                            <div class="col-2">
                                                <input type="checkbox" name="app_control[]" value="{{ $item }}" class="check-app vlign--baseline-middle m-auto outline-0" {{ in_array($item, explode(',',$user->apps)) ? 'checked' : '' }}>
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
                                @if ($user->is_smt)
                                    <span class="font-weight-bold ml-3">{{ $user->role->name }} - SMT</span>
                                @endif
                                <select name="role_id" class="form-control @error('role_id') is-invalid @enderror {{ $user->is_smt ? 'd-none' : '' }}">
                                    <option value="">Inactive</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>                                        
                                    @endforeach
                                </select>
                                @include('errors.inline', ['message' => $errors->first('role_id')])
                            </div>
                            <div class="form-group col-lg-4 mb-3">
                                <label for="">Read Only Access?</label>
                                <select name="is_read_only" class="form-control @error('is_read_only') is-invalid @enderror">
                                    <option value="0" {{ !$user->is_read_only ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ $user->is_read_only ? 'selected' : '' }}>Yes</option>
                                </select>
                                <small>Not applicable for Manager role.</small>
                                @include('errors.inline', ['message' => $errors->first('is_read_only')])
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4 mb-3">
                                <label class="d-block">Is Accounting?</label>
                                <select name="is_accounting" class="form-control @error('is_accounting') is-invalid @enderror">
                                    <option value="0" {{ !$user->is_accounting ? 'selected' : '' }}>No</option>  
                                    <option value="1" {{ $user->is_accounting ? 'selected' : '' }}>Yes</option>  
                                </select>
                                @include('errors.inline', ['message' => $errors->first('is_accounting')])
                            </div>
                            <div class="form-group col-md-4 mb-3">
                                <label class="d-block">Can Follow Up?</label>
                                <select name="is_accounting_head" class="form-control @error('is_accounting_head') is-invalid @enderror">
                                    <option value="0" {{ !$user->is_accounting_head ? 'selected' : '' }}>No</option>  
                                    <option value="1" {{ $user->is_accounting_head ? 'selected' : '' }}>Yes</option>  
                                </select>
                                @include('errors.inline', ['message' => $errors->first('is_accounting_head')])
                            </div>
                            <div class="form-group col-md-4 mb-3">
                                <label class="d-block">Is External?</label>
                                <select name="is_external" class="form-control @error('is_external') is-invalid @enderror">
                                    <option value="0" {{ !$user->is_external ? 'selected' : '' }}>No</option>  
                                    <option value="1" {{ $user->is_external ? 'selected' : '' }}>Yes</option>  
                                </select>
                                @include('errors.inline', ['message' => $errors->first('is_external')])
                            </div>
                        </div>
                        <div class="card col-md-12 mt-4">
                            <div class="card-header">
                                Company Access
                                <span class="float-right">
                                    Select All
                                    <input type="checkbox" class="check vlign--middle ml-2" id="checkCompany">
                                </span>
                            </div>
                            <div class="card-body pb-1 row">
                                @foreach ($companies as $item)
                                    <div class="col-md-6">
                                        <div class="callout py-1 mx-1 row">
                                            <div class="col-2">
                                                <input type="checkbox" name="company_control[]" value="{{ $item->id }}" class="check-company vlign--baseline-middle m-auto outline-0" {{ in_array($item->id, explode(',',$user->companies)) ? 'checked' : '' }}>
                                            </div>
                                            <div class="col-10 mt-2">
                                                <h6>{{ ucwords($item->name) }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        {{-- <div class="form-row mb-3">
                            <div class="col-md-6">
                                <label for="">Unliquidated PR amount limit</label>
                            </div>
                            <div class="col-md-6">
                                <input type="number" class="form-control @error('LIMIT_UNLIQUIDATEDPR_AMOUNT') is-invalid @enderror" name="LIMIT_UNLIQUIDATEDPR_AMOUNT" value="{{ $user->LIMIT_UNLIQUIDATEDPR_AMOUNT }}" placeholder="{{ __('messages.leave_blank') }}">
                                @include('errors.inline', ['message' => $errors->first('LIMIT_UNLIQUIDATEDPR_AMOUNT')])
                            </div>
                        </div>
                        <div class="form-row mb-3">
                            <div class="col-md-6">
                                <label for="">Unliquidated PR transactions limit</label>
                            </div>
                            <div class="col-md-6">
                                <input type="number" class="form-control @error('LIMIT_UNLIQUIDATEDPR_COUNT') is-invalid @enderror" name="LIMIT_UNLIQUIDATEDPR_COUNT" value="{{ $user->LIMIT_UNLIQUIDATEDPR_COUNT }}" placeholder="{{ __('messages.leave_blank') }}">
                                @include('errors.inline', ['message' => $errors->first('LIMIT_UNLIQUIDATEDPR_COUNT')])
                            </div>
                        </div> --}}
                        <div class="py-5">
                            <div class="form-row mb-3">
                                <div class="col-md-5 font-weight-bold">Company</div>
                                <div class="col-md-4 font-weight-bold">Unliquidated PR amount limit</div>
                                <div class="col-md-3 font-weight-bold">Unliquidated PR transactions limit</div>
                            </div>
                            @foreach ($companies as $key => $item)
                                <?php
                                    $unliq_amt = null;
                                    $unliq_trans = null;
                                    foreach ($user->transactionlimit as $value) {
                                        if ($value->company_id == $item->id) {
                                            $unliq_amt = $value->amount_limit;
                                            $unliq_trans = $value->transaction_limit;
                                        }
                                    }
                                ?>
                                <div class="form-row mb-3">
                                    <div class="col-md-5">
                                        {{ $item->name }}
                                        <input type="hidden" class="form-control" name="LIMIT_UNLIQUIDATEDPR_COMPANY_ID[]" value="{{ $item->id }}">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" class="form-control" name="LIMIT_UNLIQUIDATEDPR_AMOUNT[]" placeholder="{{ __('messages.leave_blank') }}" value="{{ $unliq_amt }}" min="0">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control" name="LIMIT_UNLIQUIDATEDPR_COUNT[]" placeholder="{{ __('messages.leave_blank') }}" value="{{ $unliq_trans }}" min="0" step="1">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane fade m-4" id="leaves" role="tabpanel" aria-labelledby="leaves-tab">...</div>
                    <div class="tab-pane fade m-4" id="others" role="tabpanel" aria-labelledby="others-tab">
                        @foreach ($user_attributes as $item)
                            <div class="form-group">
                                <label for="">{{ $item->name }}</label>
                                <input type="text" class="form-control" name="user_attr[{{ $item->id }}]" value="{{ array_key_exists($item->name, $attributes) ? $attributes[$item->name] : '' }}">
                            </div>
                        @endforeach
                    </div>
                </div>    
                <div class="m-4">
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