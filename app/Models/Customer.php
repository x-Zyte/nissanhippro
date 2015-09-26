<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Customer extends Model {

    protected $table = 'customers';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['title', 'firstname', 'lastname', 'phone1', 'phone2','occupationid','birthdate', 'address',
        'districtid', 'amphurid', 'addprovinceid', 'zipcode', 'provinceid',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            if($model->occupationid == '') $model->occupationid = null;
            if($model->districtid == '') $model->districtid = null;
            if($model->amphurid == '') $model->amphurid = null;
            if($model->addprovinceid == '') $model->addprovinceid = null;
            if($model->birthdate != null && $model->birthdate != '')
                $model->birthdate = date('Y-m-d', strtotime($model->birthdate));

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
            if($model->occupationid == '') $model->occupationid = null;
            if($model->districtid == '') $model->districtid = null;
            if($model->amphurid == '') $model->amphurid = null;
            if($model->addprovinceid == '') $model->addprovinceid = null;

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

    public function province()
    {
        return $this->belongsTo('App\Models\Province', 'branchprovinceid', 'id');
    }

    public function customerExpectations()
    {
        return $this->hasMany('App\Models\CustomerExpectation', 'customerid', 'id');
    }
}
