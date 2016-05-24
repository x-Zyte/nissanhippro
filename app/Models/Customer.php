<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Customer extends Model {

    protected $table = 'customers';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['isreal','statusexpect','title', 'firstname', 'lastname', 'phone1', 'phone2','occupationid','birthdate', 'address',
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
            else
                $model->birthdate = null;

            $model->isreal = false;
            $model->statusexpect = 0;

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
            if($model->birthdate != null && $model->birthdate != '')
                $model->birthdate = date('Y-m-d', strtotime($model->birthdate));
            else
                $model->birthdate = null;

            $model->modifiedby = Auth::user()->id;
            $model->modifieddate = date("Y-m-d H:i:s");
        });

        static::updated(function($model)
        {
            Log::create(['employeeid' => Auth::user()->id,'operation' => 'Update','date' => date("Y-m-d H:i:s"),'model' => class_basename(get_class($model)),'detail' => $model->toJson()]);

            if($model->statusexpect != 1){
                CustomerExpectation::where('customerid', $model->id)->where('active',true)->update(['active' => false]);
            }
        });

        static::deleted(function($model)
        {
            Log::create(['employeeid' => Auth::user()->id,'operation' => 'Delete','date' => date("Y-m-d H:i:s"),'model' => class_basename(get_class($model)),'detail' => $model->toJson()]);
        });
    }

    public function province()
    {
        return $this->belongsTo('App\Models\SystemDatas\Province', 'provinceid', 'id');
    }

    public function addProvince()
    {
        return $this->belongsTo('App\Models\SystemDatas\Province', 'addprovinceid', 'id');
    }

    public function amphur()
    {
        return $this->belongsTo('App\Models\SystemDatas\Amphur', 'amphurid', 'id');
    }

    public function district()
    {
        return $this->belongsTo('App\Models\SystemDatas\District', 'districtid', 'id');
    }

    public function customerExpectations()
    {
        return $this->hasMany('App\Models\CustomerExpectation', 'customerid', 'id');
    }

    public function bookingCarPreemptions()
    {
        return $this->hasMany('App\Models\CarPreemption', 'bookingcustomerid', 'id');
    }

    public function buyerCarPreemptions()
    {
        return $this->hasMany('App\Models\CarPreemption', 'buyercustomerid', 'id');
    }

    public function redLabels()
    {
        return $this->hasMany('App\Models\RedLabel', 'customerid', 'id');
    }
}
