<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Branch extends Model {

    protected $table = 'branchs';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['name','taxinvoicename','taxpayerno', 'taxaddress', 'taxdistrictid', 'taxamphurid', 'taxprovinceid', 'taxzipcode',
        'address', 'districtid', 'amphurid', 'provinceid', 'zipcode','isheadquarter','keyslot', 'active',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            if(!$model->isheadquarter) $model->keyslot = 0;

            $model->createdby = Auth::user()->id;
            $model->createddate = date("Y-m-d H:i:s");
            $model->modifiedby = Auth::user()->id;
            $model->modifieddate = date("Y-m-d H:i:s");
        });

        static::created(function($model)
        {
            Log::create(['employeeid' => Auth::user()->id,'operation' => 'Add','date' => date("Y-m-d H:i:s"),'model' => class_basename(get_class($model)),'detail' => $model->toJson()]);
            if($model->isheadquarter) {
                $max = KeySlot::where('provinceid', $model->provinceid)->max('no');
                if($max == null){
                    for ($i = 1; $i <= $model->keyslot; $i++) {
                        $m = new KeySlot;
                        $m->provinceid = $model->provinceid;
                        $m->no = $i;
                        $m->active = true;
                        $m->save();
                    }
                }
                else{
                    if($model->keyslot > $max){
                        for ($i = $max+1; $i <= $model->keyslot; $i++) {
                            $m = new KeySlot;
                            $m->provinceid = $model->provinceid;
                            $m->no = $i;
                            $m->active = true;
                            $m->save();
                        }
                    }
                    elseif($model->keyslot < $max){
                        KeySlot::where('provinceid', $model->provinceid)->where('no','>',$model->keyslot)->delete();
                    }
                }
            }
        });

        static::updating(function($model)
        {
            if(!$model->isheadquarter) $model->keyslot = 0;

            $model->modifiedby = Auth::user()->id;
            $model->modifieddate = date("Y-m-d H:i:s");
        });

        static::updated(function($model)
        {
            Log::create(['employeeid' => Auth::user()->id,'operation' => 'Update','date' => date("Y-m-d H:i:s"),'model' => class_basename(get_class($model)),'detail' => $model->toJson()]);
            if($model->isheadquarter) {
                $max = KeySlot::where('provinceid', $model->provinceid)->max('no');
                if($max == null) $max = 0;

                if($model->keyslot > $max){
                    for ($i = $max+1; $i <= $model->keyslot; $i++) {
                        $m = new KeySlot;
                        $m->provinceid = $model->provinceid;
                        $m->no = $i;
                        $m->active = true;
                        $m->save();
                    }
                }
                elseif($model->keyslot < $max){
                    KeySlot::where('provinceid', $model->provinceid)->where('no','>',$model->keyslot)->delete();
                }
            }
        });

        static::deleted(function($model)
        {
            Log::create(['employeeid' => Auth::user()->id,'operation' => 'Delete','date' => date("Y-m-d H:i:s"),'model' => class_basename(get_class($model)),'detail' => $model->toJson()]);
            if($model->isheadquarter) {
                KeySlot::where('provinceid', $model->provinceid)->delete();
            }
        });
    }

    public function province()
    {
        return $this->belongsTo('App\Models\SystemDatas\Province', 'provinceid', 'id');
    }

    public function amphur()
    {
        return $this->belongsTo('App\Models\SystemDatas\Amphur', 'amphurid', 'id');
    }

    public function district()
    {
        return $this->belongsTo('App\Models\SystemDatas\District', 'districtid', 'id');
    }

    public function taxProvince()
    {
        return $this->belongsTo('App\Models\SystemDatas\Province', 'taxprovinceid', 'id');
    }

    public function taxDistrict()
    {
        return $this->belongsTo('App\Models\SystemDatas\District', 'taxdistrictid', 'id');
    }

    public function taxAmphur()
    {
        return $this->belongsTo('App\Models\SystemDatas\Amphur', 'taxamphurid', 'id');
    }

    public function employees()
    {
        return $this->hasMany('App\Models\Employee', 'branchid', 'id');
    }

    public function customers()
    {
        return $this->hasMany('App\Models\Customer', 'branchid', 'id');
    }

    public function cars()
    {
        return $this->hasMany('App\Models\Car', 'branchid', 'id');
    }
}
