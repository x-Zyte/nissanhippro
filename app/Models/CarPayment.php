<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CarPayment extends Model {

    public $timestamps = false;
    protected $table = 'car_payments';
    protected $guarded = ['id'];

    protected $fillable = ['provinceid','branchid','carpreemptionid', 'date', 'carid', 'amountperinstallment', 'insurancepremium',
        'overrideopenbill','firstinstallmentpay','installmentsinadvance',
        'accessoriesfeeactuallypaid','accessoriesfeeincludeinyodjud',
        'insurancecompanyid', 'capitalinsurance', 'compulsorymotorinsurancecompanyid',
        'buyerpay', 'overdue', 'overdueinterest', 'totaloverdue', 'paybytype', 'paybyotherdetails',
        'overdueinstallments', 'overdueinstallmentdate1', 'overdueinstallmentamount1',
        'overdueinstallmentdate2', 'overdueinstallmentamount2','overdueinstallmentdate3', 'overdueinstallmentamount3',
        'overdueinstallmentdate4', 'overdueinstallmentamount4','overdueinstallmentdate5', 'overdueinstallmentamount5',
        'overdueinstallmentdate6', 'overdueinstallmentamount6', 'oldcarbuyername', 'oldcarpayamount', 'oldcarpaytype',
        'oldcarpaydate', 'payeeemployeeid',
        'deliverycarbookno','deliverycarno','deliverycardate','deliverycarfilepath','isdraft',

        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            $carpreemption = CarPreemption::find($model->carpreemptionid);
            $model->provinceid = $carpreemption->provinceid;
            $model->branchid = $carpreemption->branchid;

            if($model->	insurancecompanyid == '') $model->	insurancecompanyid = null;
            if($model->capitalinsurance == '') $model->capitalinsurance = null;
            if($model->buyerpay == '') $model->buyerpay = null;
            if($model->overdue == '') $model->overdue = null;
            if($model->overdueinterest == '') $model->overdueinterest = null;
            if($model->totaloverdue == '') $model->totaloverdue = null;
            if($model->paybytype == '') $model->paybytype = null;
            if($model->paybyotherdetails == '') $model->paybyotherdetails = null;
            if($model->overdueinstallments == '') $model->overdueinstallments = null;
            if($model->overdueinstallmentdate1 == '') $model->overdueinstallmentdate1 = null;
            if($model->overdueinstallmentamount1 == '') $model->overdueinstallmentamount1 = null;
            if($model->overdueinstallmentdate2 == '') $model->overdueinstallmentdate2 = null;
            if($model->overdueinstallmentamount2 == '') $model->overdueinstallmentamount2 = null;
            if($model->overdueinstallmentdate3 == '') $model->overdueinstallmentdate3 = null;
            if($model->overdueinstallmentamount3 == '') $model->overdueinstallmentamount3 = null;
            if($model->overdueinstallmentdate4 == '') $model->overdueinstallmentdate4 = null;
            if($model->overdueinstallmentamount4 == '') $model->overdueinstallmentamount4 = null;
            if($model->overdueinstallmentdate5 == '') $model->overdueinstallmentdate5 = null;
            if($model->overdueinstallmentamount5 == '') $model->overdueinstallmentamount5 = null;
            if($model->overdueinstallmentdate6 == '') $model->overdueinstallmentdate6 = null;
            if($model->overdueinstallmentamount6 == '') $model->overdueinstallmentamount6 = null;
            if($model->oldcarbuyername == '') $model->oldcarbuyername = null;
            if($model->oldcarpayamount == '') $model->oldcarpayamount = null;
            if($model->oldcarpaytype == '') $model->oldcarpaytype = null;
            if($model->oldcarpaydate == '') $model->oldcarpaydate = null;
            if($model->payeeemployeeid == '') $model->payeeemployeeid = null;

            if($model->deliverycarbookno == '') $model->deliverycarbookno = null;
            if($model->deliverycarno == '') $model->deliverycarno = null;
            if($model->deliverycardate == '') $model->deliverycardate = null;

            $model->createdby = Auth::user()->id;
            $model->createddate = date("Y-m-d H:i:s");
            $model->modifiedby = Auth::user()->id;
            $model->modifieddate = date("Y-m-d H:i:s");
        });

        static::created(function($model)
        {
            Log::create(['employeeid' => Auth::user()->id,'operation' => 'Add','date' => date("Y-m-d H:i:s"),'model' => class_basename(get_class($model)),'detail' => $model->toJson()]);

            $carpreemption = CarPreemption::find($model->carpreemptionid);
            if($carpreemption->carobjectivetype == 0) {
                $redlabelhistory = Redlabelhistory::where('carpreemptionid',$carpreemption->id)->first();
                $redlabel = Redlabel::find($redlabelhistory->redlabelid);
                $redlabel->carid = $model->carid;
                $redlabel->customerid = $carpreemption->buyercustomerid;
                $redlabel->deposit = $carpreemption->cashpledgeredlabel;
                $redlabel->save();
            }

            if($model->isdraft)
                $carpreemption->status = 3;
            else
                $carpreemption->status = 1;
            $carpreemption->save();

            $car = Car::find($model->carid);
            $car->issold = true;
            if($model->deliverycarbookno != null && $model->deliverycarbookno != ''){
                $car->isdelivered = true;
                KeySlot::where('provinceid', $car->provinceid)->where('no',$car->keyno)->update(['carid' => null ,'active' => true]);
            }
            $car->save();
        });

        static::updating(function($model)
        {
            $carpreemption = CarPreemption::find($model->carpreemptionid);
            $model->provinceid = $carpreemption->provinceid;
            $model->branchid = $carpreemption->branchid;

            if($model->insurancecompanyid == '') $model->insurancecompanyid = null;
            if($model->capitalinsurance == '') $model->capitalinsurance = null;
            if($model->buyerpay == '') $model->buyerpay = null;
            if($model->overdue == '') $model->overdue = null;
            if($model->overdueinterest == '') $model->overdueinterest = null;
            if($model->totaloverdue == '') $model->totaloverdue = null;
            if($model->paybytype == '') $model->paybytype = null;
            if($model->paybyotherdetails == '') $model->paybyotherdetails = null;
            if($model->overdueinstallments == '') $model->overdueinstallments = null;
            if($model->overdueinstallmentdate1 == '') $model->overdueinstallmentdate1 = null;
            if($model->overdueinstallmentamount1 == '') $model->overdueinstallmentamount1 = null;
            if($model->overdueinstallmentdate2 == '') $model->overdueinstallmentdate2 = null;
            if($model->overdueinstallmentamount2 == '') $model->overdueinstallmentamount2 = null;
            if($model->overdueinstallmentdate3 == '') $model->overdueinstallmentdate3 = null;
            if($model->overdueinstallmentamount3 == '') $model->overdueinstallmentamount3 = null;
            if($model->overdueinstallmentdate4 == '') $model->overdueinstallmentdate4 = null;
            if($model->overdueinstallmentamount4 == '') $model->overdueinstallmentamount4 = null;
            if($model->overdueinstallmentdate5 == '') $model->overdueinstallmentdate5 = null;
            if($model->overdueinstallmentamount5 == '') $model->overdueinstallmentamount5 = null;
            if($model->overdueinstallmentdate6 == '') $model->overdueinstallmentdate6 = null;
            if($model->overdueinstallmentamount6 == '') $model->overdueinstallmentamount6 = null;
            if($model->oldcarbuyername == '') $model->oldcarbuyername = null;
            if($model->oldcarpayamount == '') $model->oldcarpayamount = null;
            if($model->oldcarpaytype == '') $model->oldcarpaytype = null;
            if($model->oldcarpaydate == '') $model->oldcarpaydate = null;
            if($model->payeeemployeeid == '') $model->payeeemployeeid = null;

            if($model->deliverycarbookno == '') $model->deliverycarbookno = null;
            if($model->deliverycarno == '') $model->deliverycarno = null;
            if($model->deliverycardate == '') $model->deliverycardate = null;

            $model->modifiedby = Auth::user()->id;
            $model->modifieddate = date("Y-m-d H:i:s");
        });

        static::updated(function($model)
        {
            Log::create(['employeeid' => Auth::user()->id,'operation' => 'Update','date' => date("Y-m-d H:i:s"),'model' => class_basename(get_class($model)),'detail' => $model->toJson()]);

            $carpreemption = CarPreemption::find($model->carpreemptionid);
            if($model->isdraft)
                $carpreemption->status = 3;
            else
                $carpreemption->status = 1;
            $carpreemption->save();

            $car = Car::find($model->carid);
            if($model->deliverycarbookno != null && $model->deliverycarbookno != ''){
                $car->isdelivered = true;
                KeySlot::where('provinceid', $car->provinceid)->where('no',$car->keyno)->where('carid',$car->id)->update(['carid' => null ,'active' => true]);
            }
            else{
                $car->isdelivered = false;
                KeySlot::where('provinceid', $car->provinceid)->where('no',$car->keyno)->whereNull('carid')->update(['carid' => $car->id ,'active' => false]);
            }
            $car->save();
        });

        static::deleted(function($model)
        {
            Log::create(['employeeid' => Auth::user()->id,'operation' => 'Delete','date' => date("Y-m-d H:i:s"),'model' => class_basename(get_class($model)),'detail' => $model->toJson()]);

            $carpreemption = CarPreemption::find($model->carpreemptionid);
            if($carpreemption->carobjectivetype == 0) {
                $redlabelhistory = Redlabelhistory::where('carpreemptionid',$carpreemption->id)->first();
                $redlabel = Redlabel::find($redlabelhistory->redlabelid);
                $redlabel->carid = null;
                $redlabel->save();
            }

            $carpreemption->status = 0;
            $carpreemption->save();

            $car = Car::find($model->carid);
            $car->issold= false;
            $car->isdelivered = false;
            KeySlot::where('provinceid', $car->provinceid)->where('no',$car->keyno)->whereNull('carid')->update(['carid' => $car->id ,'active' => false]);
        });
    }

    public function carpreemption()
    {
        return $this->belongsTo('App\Models\CarPreemption', 'carpreemptionid', 'id');
    }

    public function car()
    {
        return $this->belongsTo('App\Models\Car', 'carid', 'id');
    }

    public function accountingDetail()
    {
        return $this->hasOne('App\Models\AccountingDetail', 'carpaymentid', 'id');
    }
}
