<?php

namespace App\Http\Controllers\Settings;

use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\CarSubModel;
use App\Models\Pricelist;
use App\Repositories\PricelistRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class PricelistController extends Controller {

    protected $menuPermissionName = "การตั้งค่าการขาย";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $carmodels = CarModel::whereHas("carbrand", function($q)
        {
            $q->where('ismain',true);

        })->orderBy('name', 'asc')->get(['id', 'name']);
        $carmodelselectlist = array();
        array_push($carmodelselectlist,':เลือกแบบ');
        foreach($carmodels as $item){
            array_push($carmodelselectlist,$item->id.':'.$item->name);
        }

        $carsubmodels = CarSubModel::has('pricelists')->orderBy('name', 'asc')->get(['id', 'name']);
        $carsubmodelselectlist = array();
        array_push($carsubmodelselectlist,':เลือกรุ่น');
        foreach($carsubmodels as $item){
            array_push($carsubmodelselectlist,$item->id.':'.$item->name);
        }

        return view('settings.pricelist',
            ['carmodelselectlist' => implode(";",$carmodelselectlist),
            'carsubmodelselectlist' => implode(";",$carsubmodelselectlist)]);
    }

    public function read()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new PricelistRepository(), Input::all());
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new PricelistRepository(), $request);
    }

    public function readSelectlistForDisplayInGrid()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $carsubmodels = CarSubModel::has('pricelists')->orderBy('name', 'asc')->get(['id', 'name']);
        $carsubmodelselectlist = array();
        array_push($carsubmodelselectlist,':เลือกรุ่น');
        foreach($carsubmodels as $item){
            array_push($carsubmodelselectlist,$item->id.':'.$item->name);
        }

        return ['carsubmodelselectlist'=>implode(";",$carsubmodelselectlist)];
    }

    public function getprice($carsubmodelid,$date)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $date = date('Y-m-d', strtotime($date));
        $pricelists = Pricelist::where('carsubmodelid',$carsubmodelid)
            ->where('effectivefrom','<=',$date)
            ->where('effectiveTo','>=',$date)
            ->orderBy('sellingpricewithaccessories', 'asc')->get(['id', 'sellingpricewithaccessories', 'promotion']);

        return ['count'=> count($pricelists),'pricelists'=>$pricelists];
    }

    public function import()
    {
        try {
            $file = Input::file('pricelist');
            //$path = Input::file('pricelist')->getRealPath();
            $temp = null;
            Excel::load($file, function ($reader) use ($temp) {
                //$reader->dump();
                // Loop through all rows
                $reader->each(function ($row) {

                    $carBrand = CarBrand::where('name', 'NISSAN')->first();
                    $carModel = CarModel::firstOrCreate(['name' => $row->d . ' ' . $row->e, 'cartypeid' => $row->c, 'carbrandid' => $carBrand->id]);
                    $carSubModel = CarSubModel::firstOrCreate(['code' => $row->g, 'name' => $row->f, 'taxinvoicename' => $row->h, 'carmodelid' => $carModel->id]);

                    $pricelist = Pricelist::firstOrNew(['carmodelid' => $carModel->id, 'carsubmodelid' => $carSubModel->id
                        , 'effectivefrom' => date('Y-m-d', strtotime($row->a)), 'effectiveto' => date('Y-m-d', strtotime($row->b))
                        , 'sellingprice' => $row->j, 'accessoriesprice' => $row->k, 'sellingpricewithaccessories' => $row->i
                        , 'margin' => $row->l, 'ws50' => $row->m, 'dms' => $row->n
                        , 'execusiveinternal' => $row->o, 'execusivecampaing' => $row->p, 'execusivetotalcampaing' => $row->q, 'execusivetotalmargincampaing' => $row->r
                        , 'internal' => $row->s, 'campaing' => $row->t, 'totalmargincampaing' => $row->u]);
                    $pricelist->effectivefrom = $row->a;
                    $pricelist->effectiveto = $row->b;
                    $pricelist->save();
                });

            });
        } catch (Exception $e) {
            return 'Message: ' . $e->getMessage();
        }

        return redirect()->action('Settings\PricelistController@index');
    }
}