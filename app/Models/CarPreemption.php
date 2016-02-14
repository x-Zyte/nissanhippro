<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CarPreemption extends Model {

    protected $table = 'car_preemptions';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['bookno', 'no', 'date', 'bookingcustomerid', 'carmodelid', 'carsubmodelid','colorid',
        'pricelistid', 'discount', 'subdown', 'accessories',

        'oldcarbrandid', 'oldcarmodelid', 'oldcargear', 'oldcarcolor', 'oldcarenginesize', 'oldcarlicenseplate', 'oldcaryear',
        'oldcarprice', 'oldcarbuyername', 'oldcarother',

        'cashpledge', 'purchasetype', 'finacecompanyid', 'interest', 'down', 'installments', 'cashpledgeredlabel',
        'registrationtype', 'registrationfee', 'insurancefee', 'compulsorymotorinsurancefee', 'accessoriesfee', 'otherfee',
        'datewantgetcar','giveawayadditionalcharges',

        'buyercustomerid', 'salesmanemployeeid', 'salesmanteamid', 'salesmanageremployeeid', 'approversemployeeid', 'approvaldate',

        'place', 'showroom', 'booth', 'leaflet', 'businesscard', 'invitationcard', 'phone', 'signshowroom', 'spotradiowalkin',
        'recommendedby', 'recommendedbyname', 'recommendedbytype', 'customertype', 'remark',

        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            if($model->oldcarbrandid == '') $model->oldcarbrandid = null;
            if($model->oldcarmodelid == '') $model->oldcarmodelid = null;
            if($model->oldcargear == '') $model->oldcargear = null;
            if($model->oldcarcolor == '') $model->oldcarcolor = null;
            if($model->oldcarenginesize == '') $model->oldcarenginesize = null;
            if($model->oldcarlicenseplate == '') $model->oldcarlicenseplate = null;
            if($model->oldcaryear == '') $model->oldcaryear = null;
            if($model->oldcarprice == '') $model->oldcarprice = null;
            if($model->oldcarbuyername == '') $model->oldcarbuyername = null;
            if($model->oldcarother == '') $model->oldcarother = null;

            if($model->purchasetype == 0){
                $model->finacecompanyid = null;
                $model->interest = null;
                $model->down = null;
                $model->installments = null;
            }
            if($model->recommendedby == false){
                $model->recommendedbyname = null;
                $model->recommendedbytype = null;
            }

            $model->date = date('Y-m-d', strtotime($model->date));
            $model->approvaldate = date('Y-m-d', strtotime($model->approvaldate));

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
            if($model->oldcarbrandid == '') $model->oldcarbrandid = null;
            if($model->oldcarmodelid == '') $model->oldcarmodelid = null;
            if($model->oldcargear == '') $model->oldcargear = null;
            if($model->oldcarbrandid == '') $model->oldcarbrandid = null;
            if($model->oldcarmodelid == '') $model->oldcarmodelid = null;
            if($model->oldcargear == '') $model->oldcargear = null;
            if($model->oldcarcolor == '') $model->oldcarcolor = null;
            if($model->oldcarenginesize == '') $model->oldcarenginesize = null;
            if($model->oldcarlicenseplate == '') $model->oldcarlicenseplate = null;
            if($model->oldcaryear == '') $model->oldcaryear = null;
            if($model->oldcarprice == '') $model->oldcarprice = null;
            if($model->oldcarbuyername == '') $model->oldcarbuyername = null;
            if($model->oldcarother == '') $model->oldcarother = null;

            if($model->purchasetype == 0){
                $model->finacecompanyid = null;
                $model->interest = null;
                $model->down = null;
                $model->installments = null;
            }
            if($model->recommendedby == false){
                $model->recommendedbyname = null;
                $model->recommendedbytype = null;
            }

            $model->carPreemptionGiveaways()->delete();

            $model->date = date('Y-m-d', strtotime($model->date));
            $model->approvaldate = date('Y-m-d', strtotime($model->approvaldate));
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

    public function bookingCustomer()
    {
        return $this->belongsTo('App\Models\Customer', 'bookingcustomerid', 'id');
    }

    public function buyerCustomer()
    {
        return $this->belongsTo('App\Models\Customer', 'buyercustomerid', 'id');
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

    public function oldCarBrand()
    {
        return $this->belongsTo('App\Models\CarBrand', 'oldcarbrandid', 'id');
    }

    public function oldCarModel()
    {
        return $this->belongsTo('App\Models\CarModel', 'oldcarmodelid', 'id');
    }

    public function salesmanEmployee()
    {
        return $this->belongsTo('App\Models\Employee', 'salesmanemployeeid', 'id');
    }

    public function salesmanTeam()
    {
        return $this->belongsTo('App\Models\Team', 'salesmanteamid', 'id');
    }

    public function salesmanagerEmployee()
    {
        return $this->belongsTo('App\Models\Employee', 'salesmanageremployeeid', 'id');
    }

    public function approversEmployee()
    {
        return $this->belongsTo('App\Models\Employee', 'approversemployeeid', 'id');
    }

    public function carPreemptionGiveaways()
    {
        return $this->hasMany('App\Models\CarPreemptionGiveaway', 'carpreemptionid', 'id');
    }

    public function finaceCompany()
    {
        return $this->belongsTo('App\Models\FinaceCompany', 'finacecompanyid', 'id');
    }
}
