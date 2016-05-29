<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CarPreemption extends Model {

    protected $table = 'car_preemptions';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['provinceid','branchid','bookno', 'no', 'date', 'bookingcustomerid',
        'carobjectivetype', 'carmodelid', 'carsubmodelid','colorid',
        'pricelistid', 'colorprice','totalcarprice', 'discount', 'subdown', 'accessories',

        'oldcarbrandid', 'oldcarmodelid', 'oldcargear', 'oldcarcolor', 'oldcarenginesize', 'oldcarlicenseplate', 'oldcaryear',
        'oldcarprice', 'oldcarbuyername', 'oldcarother',

        'cashpledge', 'purchasetype', 'finacecompanyid', 'interest', 'down', 'installments', 'cashpledgeredlabel',
        'registerprovinceid','registrationtype', 'registrationfee', 'insurancefee', 'compulsorymotorinsurancefee', 'accessoriesfee', 'otherfee',
        'datewantgetcar','giveawayadditionalcharges','financingfee', 'transferfee', 'transferoperationfee',

        'buyercustomerid', 'salesmanemployeeid', 'salesmanteamid', 'salesmanageremployeeid', 'approversemployeeid', 'approvaldate',

        'place', 'showroom', 'booth', 'leaflet', 'businesscard', 'invitationcard', 'phone', 'signshowroom', 'spotradiowalkin',
        'recommendedby', 'recommendedbyname', 'recommendedbytype', 'customertype', 'remark','status','documentstatus',

        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            $employee = Employee::find($model->salesmanemployeeid);
            $model->salesmanteamid = $employee->teamid;
            $model->provinceid = $employee->branch->provinceid;
            $model->branchid = $employee->branchid;

            $model->status = 0;

            if($model->carobjectivetype == 0){
                $model->financingfee = null;
                $model->transferfee = null;
                $model->transferoperationfee = null;
            }
            else if($model->carobjectivetype == 1){
                $model->cashpledgeredlabel = null;
                $model->registerprovinceid = null;
                $model->registrationtype = null;
                $model->registrationfee = null;
            }

            $model->createdby = Auth::user()->id;
            $model->createddate = date("Y-m-d H:i:s");
            $model->modifiedby = Auth::user()->id;
            $model->modifieddate = date("Y-m-d H:i:s");
        });

        static::created(function($model)
        {
            Log::create(['employeeid' => Auth::user()->id,'operation' => 'Add','date' => date("Y-m-d H:i:s"),'model' => class_basename(get_class($model)),'detail' => $model->toJson()]);

            Customer::where('id',$model->bookingcustomerid)->orWhere('id',$model->buyercustomerid)->update(['statusexpect' => 0]);

            $bookingcustomerid = $model->bookingcustomerid;
            $buyercustomerid = $model->buyercustomerid;

            CustomerExpectation::where('active', true)
                ->where(function ($query) use ($bookingcustomerid,$buyercustomerid) {
                    $query->where('customerid', $bookingcustomerid)
                        ->orWhere('customerid', $buyercustomerid);
                })->update(['active' => false]);
        });

        static::updating(function($model)
        {
            if($model->carobjectivetype == 0){
                $model->financingfee = null;
                $model->transferfee = null;
                $model->transferoperationfee = null;
            }
            else if($model->carobjectivetype == 1){
                $model->cashpledgeredlabel = null;
                $model->registerprovinceid = null;
                $model->registrationtype = null;
                $model->registrationfee = null;
            }

            $model->carPreemptionGiveaways()->delete();

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

    public function carPayment()
    {
        return $this->hasOne('App\Models\CarPayment', 'carpreemptionid', 'id');
    }

    public function cancelCarPreemption()
    {
        return $this->hasOne('App\Models\CancelCarPreemption', 'carpreemptionid', 'id');
    }

    public function redlabelhistories()
    {
        return $this->hasMany('App\Models\Redlabelhistory', 'carpreemptionid', 'id');
    }

    public function pricelist()
    {
        return $this->belongsTo('App\Models\Pricelist', 'pricelistid', 'id');
    }
}
