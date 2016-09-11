<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AccountingDetail extends Model
{

    public $timestamps = false;
    protected $table = 'accounting_details';
    protected $guarded = ['id'];

    protected $fillable = ['provinceid', 'branchid', 'carpaymentid', 'invoiceno', 'date',
        'additionalopenbill', 'insurancefeereceiptcondition', 'compulsorymotorinsurancefeereceiptcondition',
        'payinadvanceamountreimbursementdate', 'payinadvanceamountreimbursementdocno', 'insurancebilldifferent',
        'note1insurancefeereceiptcondition', 'note1compulsorymotorinsurancefeereceiptcondition',
        'insurancefeepayment', 'insurancefeepaidseparatelydate',
        'insurancepremiumnet', 'insurancepremiumcom', 'insurancefeepaidseparatelytotal',
        'compulsorymotorinsurancefeepayment', 'compulsorymotorinsurancefeepaidseparatelydate',
        'compulsorymotorinsurancepremiumnet', 'compulsorymotorinsurancepremiumcom', 'compulsorymotorinsurancefeepaidseparatelytotal',
        'cashpledgeredlabelreceiptbookno', 'cashpledgeredlabelreceiptno', 'cashpledgeredlabelreceiptdate',
        'cashpledgereceiptbookno', 'cashpledgereceiptno', 'cashpledgereceiptdate',
        'systemcalincasefinacecomfinamount', 'systemcalincasefinacecomfinvat', 'systemcalincasefinacecomfinamountwithvat', 'systemcalincasefinacecomfinwhtax', 'systemcalincasefinacecomfintotal',
        'incasefinacecomfinamount', 'incasefinacecomfinvat', 'incasefinacecomfinamountwithvat', 'incasefinacecomfinwhtax', 'incasefinacecomfintotal',
        'receivedcashfromfinacenet', 'receivedcashfromfinacenetshort', 'receivedcashfromfinacenetover', 'oldcarcomamount', 'oldcarcomdate', 'adj',
        'totalaccount1', 'totalaccount1short', 'totalaccount1over', 'totalaccount2', 'totalaccount2short', 'totalaccount2over',
        'totalaccounts', 'totalaccountsshort', 'totalaccountsover',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $carpayment = CarPayment::find($model->carpaymentid);
            $model->provinceid = $carpayment->provinceid;
            $model->branchid = $carpayment->branchid;

            $model->createdby = Auth::user()->id;
            $model->createddate = date("Y-m-d H:i:s");
            $model->modifiedby = Auth::user()->id;
            $model->modifieddate = date("Y-m-d H:i:s");
        });

        static::created(function ($model) {
            Log::create(['employeeid' => Auth::user()->id, 'operation' => 'Add', 'date' => date("Y-m-d H:i:s"), 'model' => class_basename(get_class($model)), 'detail' => $model->toJson()]);
        });

        static::updating(function ($model) {
            $carpayment = CarPayment::find($model->carpaymentid);
            $model->provinceid = $carpayment->provinceid;
            $model->branchid = $carpayment->branchid;

            $model->accountingDetailReceiveAndPays()->delete();

            $model->modifiedby = Auth::user()->id;
            $model->modifieddate = date("Y-m-d H:i:s");
        });

        static::updated(function ($model) {
            Log::create(['employeeid' => Auth::user()->id, 'operation' => 'Update', 'date' => date("Y-m-d H:i:s"), 'model' => class_basename(get_class($model)), 'detail' => $model->toJson()]);
        });

        static::deleting(function ($model) {
            $model->accountingDetailReceiveAndPays()->delete();
        });

        static::deleted(function ($model) {
            Log::create(['employeeid' => Auth::user()->id, 'operation' => 'Delete', 'date' => date("Y-m-d H:i:s"), 'model' => class_basename(get_class($model)), 'detail' => $model->toJson()]);
        });
    }

    public function carpayment()
    {
        return $this->belongsTo('App\Models\CarPayment', 'carpaymentid', 'id');
    }

    public function accountingDetailReceiveAndPays()
    {
        return $this->hasMany('App\Models\AccountingDetailReceiveAndPay', 'accountingdetailid', 'id');
    }
}
