<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ReturnCashPledgeRedLabel extends Model
{

    public $timestamps = false;
    protected $table = 'redlabelhistories';
    protected $guarded = ['id'];

    protected $fillable = ['redlabelid', 'carpreemptionid', 'returncashpledgedate',
        'modifiedby', 'modifieddate'];

    public static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            if ($model->returncashpledgedate != null && $model->returncashpledgedate != '')
                $model->returncashpledgedate = date('Y-m-d', strtotime($model->returncashpledgedate));
            else
                $model->returncashpledgedate = null;

            $model->modifiedby = Auth::user()->id;
            $model->modifieddate = date("Y-m-d H:i:s");
        });

        static::updated(function ($model) {
            Log::create(['employeeid' => Auth::user()->id, 'operation' => 'Update', 'date' => date("Y-m-d H:i:s"), 'model' => class_basename(get_class($model)), 'detail' => $model->toJson()]);
        });
    }

    public function carPreemption()
    {
        return $this->belongsTo('App\Models\CarPreemption', 'carpreemptionid', 'id');
    }

    public function redlabel()
    {
        return $this->belongsTo('App\Models\RedLabel', 'redlabelid', 'id');
    }
}
