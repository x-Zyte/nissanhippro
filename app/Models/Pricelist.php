<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Pricelist extends Model {

    public $timestamps = false;
    protected $table = 'pricelists';
    protected $guarded = ['id'];

    protected $fillable = ['carmodelid', 'carsubmodelid', 'effectivefrom','effectiveto','sellingprice',
        'accessoriesprice', 'sellingpricewithaccessories', 'margin', 'ws50', 'dms', 'wholesale', 'execusiveinternal', 'execusivecampaing',
        'execusivetotalmargincampaing','internal','campaing','totalmargincampaing','promotion',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            if($model->effectiveto != null && $model->effectiveto != '')
                $model->effectiveto = date('Y-m-d', strtotime($model->effectiveto));
            $model->effectivefrom = date('Y-m-d', strtotime($model->effectivefrom));
            $model->sellingpricewithaccessories = $model->sellingprice + $model->margin;
            $model->execusivetotalmargincampaing = $model->margin + $model->execusiveinternal + $model->execusivecampaing;
            $model->totalmargincampaing = $model->margin + $model->internal + $model->campaing;

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
            if($model->effectiveto != null && $model->effectiveto != '')
                $model->effectiveto = date('Y-m-d', strtotime($model->effectiveto));
            $model->effectivefrom = date('Y-m-d', strtotime($model->effectivefrom));
            $model->sellingpricewithaccessories = $model->sellingprice + $model->margin;
            $model->execusivetotalmargincampaing = $model->margin + $model->execusiveinternal + $model->execusivecampaing;
            $model->totalmargincampaing = $model->margin + $model->internal + $model->campaing;

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

    public function carModel()
    {
        return $this->belongsTo('App\Models\CarModel', 'carmodelid', 'id');
    }

    public function carSubModel()
    {
        return $this->belongsTo('App\Models\CarSubModel', 'carsubmodelid', 'id');
    }

    public function carPreemptions()
    {
        return $this->hasMany('App\Models\CarPreemption', 'pricelistid', 'id');
    }
}
