<?php namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CustomerExpectation extends Model {

    protected $table = 'customer_expectations';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['customerid','employeeid', 'date','carmodelid1','carmodelid2','carmodelid3',
        'colorid1','colorid2','colorid3','buyingtrends','newcarthingsrequired','otherconsideration','oldcarspecifications',
        'budgetpermonth','conditionproposed','conditionfinancedown','conditionfinanceinterest','conditionfinanceperiod',
        'nextappointmentdate','remarks',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            if($model->carmodelid1 == '') $model->carmodelid1 = null;
            if($model->carmodelid2 == '') $model->carmodelid2 = null;
            if($model->carmodelid3 == '') $model->carmodelid3 = null;

            if($model->colorid1 == '') $model->colorid1 = null;
            if($model->colorid2 == '') $model->colorid2 = null;
            if($model->colorid3 == '') $model->colorid3 = null;

            if($model->nextappointmentdate != null && $model->nextappointmentdate != '')
                $model->nextappointmentdate = date('Y-m-d', strtotime($model->nextappointmentdate));

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
