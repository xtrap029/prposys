@extends('layouts.app-people')

@section('title', 'View User')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $user->name }}</h1>
                    <a href="/user">Back to users</a>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h5 class="mb-4 text-right">Employment</h5>
                    <div class="form-row">
                        <div class="form-group border p-2 mb-0 col-lg-4">
                            <label for="" class="text-gray">Employee Number</label>
                            <h6>{{ $user->e_emp_no }}</h6>
                        </div>
                        <div class="form-group border p-2 mb-0 col-lg-8">
                            <label for="" class="text-gray">Employee Name</label>
                            <h6>{{ $user->name }}</h6>
                        </div>
                        <div class="form-group border p-2 mb-0 col-lg-4">
                            <label for="" class="text-gray">Hire Date</label>
                            <h6>{{ $user->e_hire_date }}</h6>
                        </div>
                        <div class="form-group border p-2 mb-0 col-lg-4">
                            <label for="" class="text-gray">Employment Status</label>
                            <h6>{{ $user->e_emp_status }}</h6>
                        </div>
                        <div class="form-group border p-2 mb-0 col-lg-4">
                            <label for="">Regularization Date</label>
                            <h6>{{ $user->e_reg_date }}</h6>
                        </div>
                        <div class="form-group border p-2 mb-0 col-lg-6">
                            <label for="" class="text-gray">Position / Title</label>
                            <h6>{{ $user->e_position }}</h6>
                        </div>
                        <div class="form-group border p-2 mb-0 col-lg-6">
                            <label for="">Rank</label>
                            <h6>{{ $user->e_rank }}</h6>
                        </div>
                        <div class="form-group border p-2 mb-0 col-lg-6">
                            <label for="" class="text-gray">Department</label>
                            <h6>{{ $user->e_department }}</h6>
                        </div>
                        <div class="form-group border p-2 mb-0 col-lg-6">
                            <label for="" class="text-gray">Payroll Account Number</label>
                            <h6>{{ $user->e_payroll }}</h6>
                        </div>
                    </div>

                    <h5 class="my-4 text-right">Personal</h5>
                    <div class="form-row">
                        <div class="form-group border p-2 mb-0 col-lg-4">
                            <label for="" class="text-gray">Date of Birth</label>
                            <h6>{{ $user->e_dob }}</h6>
                        </div>
                        <div class="form-group border p-2 mb-0 col-lg-4">
                            <label for="" class="text-gray">Gender</label>
                            <h6>{{ $user->e_gender }}</h6>
                        </div>
                        <div class="form-group border p-2 mb-0 col-lg-4">
                            <label for="" class="text-gray">Civil Status</label>
                            <h6>{{ $user->e_civil }}</h6>
                        </div>
                        <div class="form-group border p-2 mb-0 col-12">
                            <label for="" class="text-gray">Mailing Address</label>
                            <h6>{{ $user->e_mail_address }}</h6>
                        </div>
                        <div class="form-group border p-2 mb-0 col-lg-6">
                            <label for="" class="text-gray">Contact Number</label>
                            <h6>{{ $user->e_contact }}</h6>
                        </div>
                        <div class="form-group border p-2 mb-0 col-lg-6">
                            <label for="" class="text-gray">Personal Email</label>
                            <h6>{{ $user->e_email }}</h6>
                        </div>
                        <div class="form-group border p-2 mb-0 col-lg-6">
                            <label for="" class="text-gray">Emergency Contact Person</label>
                            <h6>{{ $user->e_emergency_name }}</h6>
                        </div>
                        <div class="form-group border p-2 mb-0 col-lg-6">
                            <label for="" class="text-gray">Emergency Contact Number</label>
                            <h6>{{ $user->e_emergency_contact }}</h6>
                        </div>
                    </div>

                    <h5 class="my-4 text-right">Government</h5>
                    <div class="form-row">
                        <div class="form-group border p-2 mb-0 col-lg-3">
                            <label for="" class="text-gray">TIN</label>
                            <h6>{{ $user->e_tin }}</h6>
                        </div>
                        <div class="form-group border p-2 mb-0 col-lg-3">
                            <label for="" class="text-gray">SSS</label>
                            <h6>{{ $user->e_sss }}</h6>
                        </div>
                        <div class="form-group border p-2 mb-0 col-lg-3">
                            <label for="" class="text-gray">PHIC</label>
                            <h6>{{ $user->e_phic }}</h6>
                        </div>
                        <div class="form-group border p-2 mb-0 col-lg-3">
                            <label for="" class="text-gray">HDMF</label>
                            <h6>{{ $user->e_hmdf }}</h6>
                        </div>                            
                    </div>

                    <h5 class="my-4 text-right">Account</h5>
                    <div class="form-group border p-2 mb-0 col-12">
                        <label for="avatar" class="d-block text-gray">Avatar</label>
                        <img src="/storage/public/images/users/{{ $user->avatar }}" alt="" class="thumb thumb--sm img-thumbnail">
                    </div>
                    
                    <h5 class="my-4 text-right">Sequence</h5>
                    <div class="form-row">
                        <div class="form-group border p-2 mb-0 col-4">
                            <label for="" class="text-gray">Is Accounting?</label>
                            <h6>{{ $user->is_accounting ? 'Yes' : 'No' }}</h6>
                        </div>
                        <div class="form-group border p-2 mb-0 col-4">
                            <label for="" class="text-gray">Can Follow Up?</label>
                            <h6>{{ $user->is_accounting_head ? 'Yes' : 'No' }}</h6>
                        </div>
                        <div class="form-group border p-2 mb-0 col-4">
                            <label for="" class="text-gray">Is External?</label>
                            <h6>{{ $user->is_external ? 'Yes' : 'No' }}</h6>
                        </div>
                        <div class="form-group border p-2 mb-0 col-lg-6 d-none">
                            <label for="" class="text-gray">Role</label>
                            <h6>{{ $user->role->name }} {{ $user->is_smt ? ' - SMT' : '' }}</h6>
                        </div>
                        <div class="form-group border p-2 mb-0 col-lg-6 d-none">
                            <label for="" class="text-gray">Read Only Access?</label>
                            <h6>{{ $user->is_read_only ? 'Yes' : 'No' }}</h6>
                        </div>
                        <div class="form-group border p-2 mb-0 col-lg-6">
                            <label for="" class="text-gray">Default Company</label>
                            <h6>{{ $user->company->name }}</h6>
                        </div>
                        <div class="form-group border p-2 mb-0 col-6">
                            <label for="" class="text-gray">Accessible Companies</label>
                            <h6>
                                @foreach ($companies as $item)
                                    {{ $item->name }}<br>
                                @endforeach
                            </h6>
                        </div>
                        @if ($user->transactionlimit)
                            <div class="form-group border p-0 mb-0 col-12">
                                <table class="table table-bordered table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Company</th>
                                            <th class="text-center">Unliq. PR Amount Limit</th>
                                            <th class="text-center">Unliq. PR Transaction Limit</th>
                                        </tr>
                                    </thead>
                                    @foreach ($user->transactionlimit as $item)
                                        <tr>
                                            <td>{{ $item->company->name }}</td>
                                            <td class="text-center">{{ $item->amount_limit }}</td>
                                            <td class="text-center">{{ $item->transaction_limit }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        @endif
                    </div>

                    <h5 class="my-4 text-right">Travels</h5>
                    <div class="form-row">
                        <div class="form-group border p-2 mb-0 col-12">
                            <label for="" class="text-gray">Roles</label>
                            <h6>
                                <table class="table">
                                    @foreach ($travel_roles as $item)
                                        @if (in_array($item->id, explode(',',$user->travel_roles)))
                                            <tr>
                                                <td>{{ $item->name }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </table>
                            </h6>
                        </div>
                    </div>

                    <h5 class="my-4 text-right">Leaves</h5>
                    <div class="form-row">
                        <div class="form-group border p-2 mb-0 col-12">
                            <label for="" class="text-gray">Departments</label>
                            <h6>
                                <table class="table">
                                    @foreach ($user->departmentuserapprover as $item)
                                        <tr>
                                            <td><b>{{ $item->department->name }}</b></td>
                                            <td>Approver</td>
                                        </tr>
                                    @endforeach
                                    @foreach ($user->departmentusermember as $item)
                                        <tr>
                                            <td><b>{{ $item->department->name }}</b></td>
                                            <td>Member</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </h6>
                        </div>
                    </div>

                    <h5 class="my-4 text-right">Others</h5>
                    <div class="form-row">
                        <div class="form-group border p-0 mb-0 col-12">
                            <table class="table table-bordered table-striped mb-0">
                                @foreach ($user_attributes as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ array_key_exists($item->name, $attributes) ? $attributes[$item->name] : '-' }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection