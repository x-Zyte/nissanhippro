<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CarSubModel extends Model {

    protected $table = 'car_submodels';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['code','name','taxinvoicename', 'carmodelid', 'detail', 'active',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
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

    public function carmodel()
    {
        return $this->belongsTo('App\Models\CarModel', 'carmodelid', 'id');
    }

    public function cars()
    {
        return $this->hasMany('App\Models\Car', 'carsubmodelid', 'id');
    }

    public function carPreemptions()
    {
        return $this->hasMany('App\Models\CarPreemption', 'carsubmodelid', 'id');
    }

    public function commissionExtraCars()
    {
        return $this->hasMany('App\Models\CommissionExtraCar', 'carsubmodelid', 'id');
    }

    public function commissionFinaceCars()
    {
        return $this->hasMany('App\Models\CommissionFinaceCar', 'carsubmodelid', 'id');
    }

    public function commissionSpecials()
    {
        return $this->hasMany('App\Models\CommissionSpecial', 'carsubmodelid', 'id');
    }

    public function pricelists()
    {
        return $this->hasMany('App\Models\Pricelist', 'carsubmodelid', 'id');
    }
}
