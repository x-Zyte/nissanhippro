<?php namespace App\Models;

use Illuminate\Support\Facades\Auth;

class Employee extends User {

    protected $table = 'employees';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['title', 'firstname', 'lastname','code','workingstartdate','workingenddate', 'username',
        'password', 'email','loginstartdate','loginenddate', 'phone', 'isadmin', 'branchid',
        'departmentid', 'teamid', 'remarks', 'active', 'remember_token',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    protected $hidden = ['password', 'remember_token'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            if($model->isadmin){
                $model->branchid = null;
                $model->departmentid = null;
                $model->teamid = null;
            }
            else{
                if($model->branchid == '') $model->branchid = null;
                if($model->departmentid == '') $model->departmentid = null;
                if($model->teamid == '') $model->teamid = null;
            }

            if($model->workingstartdate != null && $model->workingstartdate != '')
                $model->workingstartdate = date('Y-m-d', strtotime($model->workingstartdate));
            else
                $model->workingstartdate = null;
            if($model->workingenddate != null && $model->workingenddate != '')
                $model->workingenddate = date('Y-m-d', strtotime($model->workingenddate));
            else
                $model->workingenddate = null;
            if($model->loginstartdate != null && $model->loginstartdate != '')
                $model->loginstartdate = date('Y-m-d', strtotime($model->loginstartdate));
            else
                $model->loginstartdate = null;
            if($model->loginenddate != null && $model->loginenddate != '')
                $model->loginenddate = date('Y-m-d', strtotime($model->loginenddate));
            else
                $model->loginenddate = null;

            if($model->username != null && $model->username != '')
                $model->password = bcrypt("nissanhippro");

            $model->createdby = Auth::user()->id;
            $model->createddate = date("Y-m-d H:i:s");
            $model->modifiedby = Auth::user()->id;
            $model->modifieddate = date("Y-m-d H:i:s");
        });

        static::created(function($model)
        {
            Log::create(['employeeid' => Auth::user()->id,'operation' => 'Add','date' => date("Y-m-d H:i:s"),'model' => class_basename(get_class($model)),'detail' => $model->toJson()]);
        });

        static::updating(function($model)
        {
            if($model->isadmin){
                $model->branchid = null;
                $model->departmentid = null;
                $model->teamid = null;
            }
            else{
                if($model->branchid == '') $model->branchid = null;
                if($model->departmentid == '') $model->departmentid = null;
                if($model->teamid == '') $model->teamid = null;
            }

            if($model->workingstartdate != null && $model->workingstartdate != '')
                $model->workingstartdate = date('Y-m-d', strtotime($model->workingstartdate));
            else
                $model->workingstartdate = null;
            if($model->workingenddate != null && $model->workingenddate != '')
                $model->workingenddate = date('Y-m-d', strtotime($model->workingenddate));
            else
                $model->workingenddate = null;
            if($model->loginstartdate != null && $model->loginstartdate != '')
                $model->loginstartdate = date('Y-m-d', strtotime($model->loginstartdate));
            else
                $model->loginstartdate = null;
            if($model->loginenddate != null && $model->loginenddate != '')
                $model->loginenddate = date('Y-m-d', strtotime($model->loginenddate));
            else
                $model->loginenddate = null;

            if($model->username != null && $model->username != '' && ($model->password == null || $model->password == ''))
                $model->password = bcrypt("nissanhippro");

            $model->modifiedby = Auth::user()->id;
            $model->modifieddate = date("Y-m-d H:i:s");
        });

        static::updated(function($model)
        {
            Log::create(['employeeid' => Auth::user()->id,'operation' => 'Update','date' => date("Y-m-d H:i:s"),'model' => class_basename(get_class($model)),'detail' => $model->toJson()]);
        });

        static::deleted(function($model)
        {
            Log::create(['employeeid' => Auth::user()->id,'operation' => 'Delete','date' => date("Y-m-d H:i:s"),'model' => class_basename(get_class($model)),'detail' => $model->toJson()]);
        });
    }

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch', 'branchid', 'id');
    }

    public function department()
    {
        return $this->belongsTo('App\Models\Department', 'departmentid', 'id');
    }

    public function team()
    {
        return $this->belongsTo('App\Models\Team', 'teamid', 'id');
    }

    public function employeePermissions()
    {
        return $this->hasMany('App\Models\EmployeePermission', 'employeeid', 'id');
    }
}
