<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CancelCarPreemption extends Model {

    protected $table = 'cancel_car_preemptions';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['provinceid','branchid','carpreemptionid','toemployeeid', 'cancelreasontype',
        'cancelreasondetails', 'remark','approvaltype','amountapproved',
        'salesmanemployeedate', 'accountemployeeid', 'accountemployeedate',
        'financeemployeeid', 'financeemployeedate', 'approversemployeeid', 'approversemployeedate',

        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            $carpreemption = CarPreemption::find($model->carpreemptionid);
            $model->provinceid = $carpreemption->provinceid;
            $model->branchid = $carpreemption->branchid;

            if($model->approvaltype == 1) $model->amountapproved = null;

            $model->createdby = Auth::user()->id;
            $model->createddate = date("Y-m-d H:i:s");
            $model->modifiedby = Auth::user()->id;
            $model->modifieddate = date("Y-m-d H:i:s");
        });

        static::created(function($model)
        {
            Log::create(['employeeid' => Auth::user()->id,'operation' => 'Add','date' => date("Y-m-d H:i:s"),'model' => class_basename(get_class($model)),'detail' => $model->toJson()]);

            $carpreemption = CarPreemption::find($model->carpreemptionid);
            $carpreemption->status = 2;
            $carpreemption->save();
        });

        static::updating(function($model)
        {
            $carpreemption = CarPreemption::find($model->carpreemptionid);
            $model->provinceid = $carpreemption->provinceid;
            $model->branchid = $carpreemption->branchid;

            if($model->approvaltype == 1) $model->amountapproved = null;

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

            $carpreemption = CarPreemption::find($model->carpreemptionid);
            $carpreemption->status = 0;
            $carpreemption->save();
        });
    }

    public function carpreemption()
    {
        return $this->belongsTo('App\Models\CarPreemption', 'carpreemptionid', 'id');
    }

    public function approversEmployee()
    {
        return $this->belongsTo('App\Models\Employee', 'approversemployeeid', 'id');
    }
}
