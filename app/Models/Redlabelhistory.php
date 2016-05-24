<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Redlabelhistory extends Model {

    protected $table = 'redlabelhistories';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['redlabelid', 'issuedate', 'carpreemptionid', 'returndate', 'remarks',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            $model->issuedate = date('Y-m-d', strtotime($model->issuedate));
            $model->returndate = null;

            $model->createdby = Auth::user()->id;
            $model->createddate = date("Y-m-d H:i:s");
            $model->modifiedby = Auth::user()->id;
            $model->modifieddate = date("Y-m-d H:i:s");
        });

        static::created(function($model)
        {
            $carpreemption = CarPreemption::find($model->carpreemptionid);
            $redlabel = Redlabel::find($model->redlabelid);
            $redlabel->customerid = $carpreemption->buyercustomerid;
            $redlabel->deposit = $carpreemption->cashpledgeredlabel;
            $redlabel->save();

            Log::create(['employeeid' => Auth::user()->id,'operation' => 'Add','date' => date("Y-m-d H:i:s"),'model' => class_basename(get_class($model)),'detail' => $model->toJson()]);
        });

        static::updating(function($model)
        {
            $model->issuedate = date('Y-m-d', strtotime($model->issuedate));
            if($model->returndate != null && $model->returndate != '')
                $model->returndate = date('Y-m-d', strtotime($model->returndate));
            else
                $model->returndate = null;

            $model->modifiedby = Auth::user()->id;
            $model->modifieddate = date("Y-m-d H:i:s");
        });

        static::updated(function($model)
        {
            $maxid = Redlabelhistory::where('redlabelid',$model->redlabelid)->max('id');
            if($model->returndate != null && $model->returndate != ''){
                if($model->id == $maxid){
                    $redlabel = Redlabel::find($model->redlabelid);
                    $redlabel->customerid = null;
                    $redlabel->carid = null;
                    $redlabel->deposit = null;
                    $redlabel->save();
                }
            }
            else{
                if($model->id == $maxid){
                    $carpreemption = CarPreemption::find($model->carpreemptionid);
                    $redlabel = Redlabel::find($model->redlabelid);
                    $redlabel->customerid = $carpreemption->buyercustomerid;
                    $redlabel->deposit = $carpreemption->cashpledgeredlabel;
                    $redlabel->save();
                }
            }

            Log::create(['employeeid' => Auth::user()->id,'operation' => 'Update','date' => date("Y-m-d H:i:s"),'model' => class_basename(get_class($model)),'detail' => $model->toJson()]);
        });

        static::deleted(function($model)
        {
            Log::create(['employeeid' => Auth::user()->id,'operation' => 'Delete','date' => date("Y-m-d H:i:s"),'model' => class_basename(get_class($model)),'detail' => $model->toJson()]);

            $maxid = Redlabelhistory::where('redlabelid',$model->redlabelid)->max('id');
            if($maxid == null || $maxid == '' || $model->id > $maxid){
                $redlabel = Redlabel::find($model->redlabelid);
                $redlabel->customerid = null;
                $redlabel->carid = null;
                $redlabel->deposit = null;
                $redlabel->save();
            }
        });
    }

    public function carPreemption()
    {
        return $this->belongsTo('App\Models\CarPreemption', 'carpreemptionid', 'id');
    }
}
