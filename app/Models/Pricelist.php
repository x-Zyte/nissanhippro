<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Pricelist extends Model {

    protected $table = 'pricelists';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['carmodelid', 'carsubmodelid', 'effectivefrom','effectiveto','sellingprice',
        'accessoriesprice','sellingpricewithaccessories','margin','execusiveinternal','execusivecampaing', 'execusivetotalcampaing',
        'execusivetotalmargincampaing','internal','campaing','totalcampaing','totalmargincampaing','promotion',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            if($model->effectiveto != null && $model->effectiveto != '')
                $model->effectiveto = date('Y-m-d', strtotime($model->effectiveto));
            $model->effectivefrom = date('Y-m-d', strtotime($model->effectivefrom));
            $model->sellingpricewithaccessories = $model->sellingprice + $model->accessoriesprice;
            $model->execusivetotalcampaing = $model->execusiveinternal + $model->execusivecampaing;
            $model->execusivetotalmargincampaing = $model->margin + $model->execusivetotalcampaing;
            $model->totalcampaing = $model->internal + $model->campaing;
            $model->totalmargincampaing = $model->margin + $model->totalcampaing;

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
            $model->sellingpricewithaccessories = $model->sellingprice + $model->accessoriesprice;
            $model->execusivetotalcampaing = $model->execusiveinternal + $model->execusivecampaing;
            $model->execusivetotalmargincampaing = $model->margin + $model->execusivetotalcampaing;
            $model->totalcampaing = $model->internal + $model->campaing;
            $model->totalmargincampaing = $model->margin + $model->totalcampaing;

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
}
