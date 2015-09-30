<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Ipaddress;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers;

    /**
     * Create a new authentication controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\Guard  $auth
     * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
     * @return void
     */
    public function __construct(Guard $auth, Registrar $registrar)
    {
        $this->auth = $auth;
        $this->registrar = $registrar;

        $this->middleware('guest', ['except' => 'getLogout']);
    }

    public function getLogin()
    {
        if (getenv('HTTP_X_FORWARDED_FOR'))
            $ip=getenv('HTTP_X_FORWARDED_FOR');
        else
            $ip=getenv('REMOTE_ADDR');

        $host = gethostbyaddr($ip);

        $ipAddress = 'Address : '.$ip.' Host : '.$host;

        return view('auth.login',['ipAddress' => $ipAddress]);
    }

    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'username' => 'required', 'password' => 'required',
        ]);

        $credentials = $request->only('username', 'password', 'active');

        $employee = Employee::where('username', $credentials['username'])->where('active', true)->first();

        if($employee != null && password_verify($credentials['password'], $employee->password)){
            if(!$employee->isadmin){
                if (getenv('HTTP_X_FORWARDED_FOR'))
                    $ip=getenv('HTTP_X_FORWARDED_FOR');
                else
                    $ip=getenv('REMOTE_ADDR');
                $host = gethostbyaddr($ip);
                $ipAddress = 'Address : '.$ip.' Host : '.$host;
                $count = Ipaddress::where('ip', $ip)->count();

                $today = date("Y-m-d");

                if($count == 0 || ($employee->loginstartdate != null && $today < date('Y-m-d', strtotime($employee->loginstartdate))) || ($employee->loginenddate != null && $today > date('Y-m-d', strtotime($employee->loginenddate))))
                    return view('errors.permissiondenied',['ipAddress' => $ipAddress]);

                if($employee->branchid == null){
                    return redirect($this->loginPath())
                        ->withInput($request->only('username', 'remember'))
                        ->withErrors([
                            'username' => 'บัญชีเข้าใช้งานของคุณยังไม่ได้ผูกกับสาขา โปรดติดต่อหัวหน้า หรือผู้ดูแล',
                        ]);
                }
            }

            if ($this->auth->attempt($credentials, $request->has('remember')))
            {
                return redirect()->intended($this->redirectPath());
            }
        }
        else{
            return redirect($this->loginPath())
                ->withInput($request->only('username', 'remember'))
                ->withErrors([
                    'username' => $this->getFailedLoginMessage(),
                ]);
        }
    }

    public function getLogout()
    {
        $this->auth->logout();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/auth/login');
    }
}
