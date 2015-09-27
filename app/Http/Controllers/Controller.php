<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;

abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;

    protected $viewPermissiondeniedName = 'errors.permissiondenied';

    public function hasPermission($menu){
        if(!Auth::user()->isadmin && !in_array($menu, Auth::user()->employeePermissions()->lists('menu')))
            return false;
        return true;
    }
}
