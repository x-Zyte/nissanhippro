<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;;

class CommissionSpecial extends Model {

    protected $table = 'commission_specials';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['finacecompanyid', 'interestratetypeid', 'name', //'useforcustomertype',
        'carmodelid', 'carsubmodelid', 'frominstallment', 'toinstallment',
        'fromdownrate', 'todownrate', 'amount',
        'effectivefrom', 'effectiveto', 'active',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            $model->effectivefrom = date('Y-m-d', strtotime($model->effectivefrom));
            $model->effectiveto = date('Y-m-d', strtotime($model->effectiveto));

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
            $model->effectivefrom = date('Y-m-d', strtotime($model->effectivefrom));
            $model->effectiveto = date('Y-m-d', strtotime($model->effectiveto));

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

    public function interestRateType()
    {
        return $this->belongsTo('App\Models\InterestRateType', 'interestratetypeid', 'id');
    }

    public function carSubModel()
    {
        return $this->belongsTo('App\Models\CarSubModel', 'carsubmodelid', 'id');
    }
}
