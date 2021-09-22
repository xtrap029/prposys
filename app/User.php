<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable {
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'apps',
        'companies',
        'avatar',
        'company_id',
        'is_smt',
        'LIMIT_UNLIQUIDATEDPR_AMOUNT',
        'LIMIT_UNLIQUIDATEDPR_COUNT',
        'e_emp_no',
        'e_hire_date',
        'e_emp_status',
        'e_reg_date',
        'e_position',
        'e_rank',
        'e_department',
        'e_payroll',
        'e_dob',
        'e_gender',
        'e_civil',
        'e_mail_address',
        'e_contact',
        'e_email',
        'e_emergency_name',
        'e_emergency_contact',
        'e_tin',
        'e_sss',
        'e_phic',
        'e_hmdf',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    use LogsActivity;
    protected static $logAttributes = [
        'name',
        'email',
        'avatar',
        'role.name',
        'apps',
        'company.name',
        'is_smt',
        'LIMIT_UNLIQUIDATEDPR_AMOUNT',
        'LIMIT_UNLIQUIDATEDPR_COUNT',
        'e_emp_no',
        'e_hire_date',
        'e_emp_status',
        'e_reg_date',
        'e_position',
        'e_rank',
        'e_department',
        'e_payroll',
        'e_dob',
        'e_gender',
        'e_civil',
        'e_mail_address',
        'e_contact',
        'e_email',
        'e_emergency_name',
        'e_emergency_contact',
        'e_tin',
        'e_sss',
        'e_phic',
        'e_hmdf',
    ];
    protected static $logName = 'User';
    protected static $logOnlyDirty = true;

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function departmentuser() {
        return $this->hasMany(DepartmentsUser::class);
    }

    public function departmentusermember() {
        return $this->hasMany(DepartmentsUser::class)->where('is_approver', '=', 0);
    }

    public function departmentuserapprover() {
        return $this->hasMany(DepartmentsUser::class)->where('is_approver', '=', 1);
    }
}
