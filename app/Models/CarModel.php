<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CarModel extends Model {

    protected $table = 'car_models';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['name', 'cartypeid','carbrandid','individualregistercost','implementingindividualregistercost',
        'companyregistercost','implementingcompanyregistercost', 'detail', 'active',
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

    public function cartype()
    {
        return $this->belongsTo('App\Models\CarType', 'cartypeid', 'id');
    }

    public function carbrand()
    {
        return $this->belongsTo('App\Models\CarBrand', 'carbrandid', 'id');
    }

    public function carSubModel()
    {
        return $this->hasMany('App\Models\CarSubModel', 'carmodelid', 'id');
    }
}
