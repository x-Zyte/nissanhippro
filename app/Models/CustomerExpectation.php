<?php namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CustomerExpectation extends Model {

    protected $table = 'customer_expectations';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['customerid','employeeid', 'date','carmodelid1','carmodelid2','carmodelid3', 'details',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            if($model->carmodelid1 == '') $model->carmodelid1 = null;
            if($model->carmodelid2 == '') $model->carmodelid2 = null;
            if($model->carmodelid3 == '') $model->carmodelid3 = null;

            $model->date = date('Y-m-d', strtotime($model->date));
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
            if($model->carmodelid1 == '') $model->carmodelid1 = null;
            if($model->carmodelid2 == '') $model->carmodelid2 = null;
            if($model->carmodelid3 == '') $model->carmodelid3 = null;

            $model->date = date('Y-m-d', strtotime($model->date));
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

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customerid', 'id');
    }
}
