<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Car extends Model {

    protected $table = 'cars';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['provinceid', 'carmodelid', 'carsubmodelid', 'no', 'dodate', 'receiveddate','dealername', 'engineno', 'chassisno', 'keyno',
        'colorid', 'objective', 'receivetype', 'receivecarfilepath', 'issold', 'isregistered', 'isdelivered','parklocation',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            $model->issold = false;
            $model->isregistered = false;
            $model->isdelivered = false;

            if($model->receivetype == 0) $model->dealername = null;

            $model->dodate = date('Y-m-d', strtotime($model->dodate));
            $model->receiveddate = date('Y-m-d', strtotime($model->receiveddate));
            $model->createdby = Auth::user()->id;
            $model->createddate = date("Y-m-d H:i:s");
            $model->modifiedby = Auth::user()->id;
            $model->modifieddate = date("Y-m-d H:i:s");
        });

        static::created(function($model)
        {
            Log::create(['employeeid' => Auth::user()->id,'operation' => 'Add','date' => date("Y-m-d H:i:s"),'model' => class_basename(get_class($model)),'detail' => $model->toJson()]);
            //$rs = DB::select('call running_number("'.$model->provinceid.date("Y").'","'.$model->receivetype.'")');
            //$model->no = $rs[0]->no;
            if($model->objective == 0){
            //$min = KeySlot::where('provinceid', $model->provinceid)->where('active',true)->min('no');
            //if($min == null){
            //    $branch = Branch::where('provinceid', $model->provinceid)->where('isheadquarter', true)->first();
            //    $branch->keyslot = $branch->keyslot+1;
            //    $branch->save();
            //    $model->keyno = $branch->keyslot;
            //}
            //else{
            //    $model->keyno = $min;
            //}
            //$model->save();
            KeySlot::where('provinceid', $model->provinceid)->where('no',$model->keyno)->update(['carid' => $model->id ,'active' => false]);
            }
        });

        static::updating(function($model)
        {
            if($model->receivetype == 0) $model->dealername = null;

            $model->dodate = date('Y-m-d', strtotime($model->dodate));
            $model->receiveddate = date('Y-m-d', strtotime($model->receiveddate));
            $model->modifiedby = Auth::user()->id;
            $model->modifieddate = date("Y-m-d H:i:s");
        });

        static::updated(function($model)
        {
            Log::create(['employeeid' => Auth::user()->id,'operation' => 'Update','date' => date("Y-m-d H:i:s"),'model' => class_basename(get_class($model)),'detail' => $model->toJson()]);

            if($model->objective == 0) {
                KeySlot::where('provinceid', $model->provinceid)->where('no',$model->keyno)->where('carid',$model->id)->update(['carid' => null ,'active' => true]);
                KeySlot::where('provinceid', $model->provinceid)->where('no',$model->keyno)->whereNull('carid')->update(['carid' => $model->id ,'active' => false]);
            }
            else{
                KeySlot::where('provinceid', $model->provinceid)->where('no',$model->keyno)->where('carid',$model->id)->update(['carid' => null ,'active' => true]);
            }
        });

        static::deleted(function($model)
        {
            Log::create(['employeeid' => Auth::user()->id,'operation' => 'Delete','date' => date("Y-m-d H:i:s"),'model' => class_basename(get_class($model)),'detail' => $model->toJson()]);

            if($model->receivecarfilepath != '')
                File::delete(public_path().$model->receivecarfilepath);

            KeySlot::where('provinceid', $model->provinceid)->where('no',$model->keyno)->update(['carid' => null ,'active' => true]);
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

    public function color()
    {
        return $this->belongsTo('App\Models\Color', 'colorid', 'id');
    }

    public function province()
    {
        return $this->belongsTo('App\Models\systemdatas\Province', 'provinceid', 'id');
    }

    public function carPayment()
    {
        return $this->hasOne('App\Models\CarPayment', 'carid', 'id');
    }

    public function redLabel()
    {
        return $this->hasOne('App\Models\RedLabel', 'carid', 'id');
    }
}
