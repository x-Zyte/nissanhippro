<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

;

class CommissionFinace extends Model {

    public $timestamps = false;
    protected $table = 'commission_finaces';
    protected $guarded = ['id'];

    protected $fillable = ['finacecompanyid', 'interestratetypeid', 'name', //'useforcustomertype',
        'effectivefrom', 'effectiveto', 'finaceminimumprofit', 'years', 'active',
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

    public function commissionFinaceCars()
    {
        return $this->hasMany('App\Models\CommissionFinaceCar', 'commissionfinaceid', 'id');
    }

    public function commissionFinaceComs()
    {
        return $this->hasMany('App\Models\CommissionFinaceCom', 'commissionfinaceid', 'id');
    }

    public function commissionFinaceInterests()
    {
        return $this->hasMany('App\Models\CommissionFinaceInterest', 'commissionfinaceid', 'id');
    }
}
