<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RedLabel extends Model {

    protected $table = 'redlabels';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['provinceid','no', 'customerid', 'carid','deposit', 'active',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            $model->customerid = null;
            $model->carid = null;
            $model->deposit = null;

            if(!strpos($model->no,"-"))
                $model->no = substr_replace($model->no,"-",strlen($model->no) - 4,0);

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
            if($model->customerid == '') $model->customerid = null;
            if($model->carid == '') $model->carid = null;
            if($model->deposit == '') $model->deposit = null;

            if(!strpos($model->no,"-"))
                $model->no = substr_replace($model->no,"-",strlen($model->no) - 4,0);

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

    public function car()
    {
        return $this->belongsTo('App\Models\Car', 'carid', 'id');
    }
}
