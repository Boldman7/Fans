<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\LoginSession;
use Cassandra\Date;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Setting;
use App\Timeline;
use Illuminate\Support\Facades\Hash;
use Teepluss\Theme\Facades\Theme;
use Validator;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function getLogin()
    {
        //echo "kkk";
        if(isset($_GET['email']))
        {
            $user = DB::table('users')->where('email', $_GET['email'])->first();

            if (empty($user)) {
                return \redirect('/');
            }

            if(Auth::loginUsingId($user->id)){
                return \redirect('/');
            }
        }
        else{
            $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('guest');
            $theme->setTitle(trans('auth.login').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

            return $theme->scope('users.login')->render();
        }

    }

    public function login(Request $request)
    {

//        echo $request->email;

//        $user = User::where('USER_NAME', '=', $request->email)->first();
        $user = DB::table('users')->where('email', $request->email)->first();

        if (empty($user)) {

            $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('guest');
            $theme->setTitle(trans('auth.login').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

            $error_msg = "Login failed. Try again";
            return $theme->scope('users.login', compact('error_msg'))->render();
        }

        if(Auth::loginUsingId($user->id) && Hash::check($request->password, $user->password) && $user->email_verified == 1){

            //save to loginSessions
            $login_session = new LoginSession();
            $login_session->user_id = Auth::user()->id;
            $login_session->user_name = Timeline::where('id', Auth::user()->id)->first()->username;
            $login_session->ip_address = $_SERVER['REMOTE_ADDR'];
            $login_session->machine_name = gethostname();
            $login_session->os = getOS();
            $login_session->browser = getBrowser();

            // get location
            $PublicIP = get_client_ip();
//            $json     = file_get_contents("http://ipinfo.io/$PublicIP/geo");
//            $json     = json_decode($json, true);
//            $country  = $json['country'];
//            $region   = $json['region'];
//            $city     = $json['city'];
//            $login_session->location = $region." ".$city;
            $login_session->date = date("Y-m-d");
            $login_session->save();

            return \redirect('/');
        }
        else {
            $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('guest');
            $theme->setTitle(trans('auth.login').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

            $error_msg = "Login failed. Try again";
            return $theme->scope('users.login', compact('error_msg'))->render();
        }


//        $data = $request->all();
//        $validate = Validator::make($data, [
//            'email'    => 'required',
//            'password' => 'required',
//        ]);
//
//        if (!$validate->passes()) {
//            return response()->json(['status' => '201', 'message' => trans('auth.login_failed')]);
//        } else {
//            $user = '';
//            $nameoremail = '';
//            $canLogin = false;
//            $remember = ($request->remember ? true : false);
//
//            if (filter_var(($request->email), FILTER_VALIDATE_EMAIL)  == true) {
//                $nameoremail = $request->email;
//                $user = DB::table('users')->where('email', $request->email)->first();
//            } else {
//                $timeline = DB::table('timelines')->where('username', $request->email)->first();
//                if ($timeline != null) {
//                    $user = DB::table('users')->where('timeline_id', $timeline->id)->first();
//                    if ($user) {
//                        $nameoremail = $user->email;
//                    }
//                }
//            }
//
//            if (Setting::get('mail_verification') == 'off') {
//                $canLogin = true;
//            } else {
//                if ($user != null) {
//                    if ($user->email_verified == 1) {
//                        $canLogin = true;
//                    } else {
//                        return response()->json(['status' => '201', 'message' => trans('messages.verify_mail')]);
//                    }
//                }
//            }
//        }
//
//        if ($canLogin && Auth::attempt(['email' => $nameoremail])) {
//            return \redirect('/');
////            return response()->json(['status' => '200', 'message' => trans('auth.login_success')]);
//        } else {
//            return response()->json(['status' => '201', 'message' => trans('auth.login_failed')]);
//        }
    }

    //
    public function mainProjectLogin(Request $request)
    {
        $data = $request->all();
        $validate = Validator::make($data, [
            'email'    => 'required',
            'password' => 'required',
        ]);

        if (!$validate->passes()) {
//            return response()->json(['status' => '201', 'message' => trans('auth.login_failed')]);
            return redirect()->back();
        } else {
            $user = '';
            $nameoremail = '';
            $canLogin = false;
            $remember = ($request->remember ? true : false);

            if (filter_var(($request->email), FILTER_VALIDATE_EMAIL)  == true) {
                $nameoremail = $request->email;
                $user = DB::table('users')->where('email', $request->email)->first();
            } else {
                $timeline = DB::table('timelines')->where('username', $request->email)->first();
                if ($timeline != null) {
                    $user = DB::table('users')->where('timeline_id', $timeline->id)->first();
                    if ($user) {
                        $nameoremail = $user->email;
                    }
                }
            }

            if (Setting::get('mail_verification') == 'off') {
                $canLogin = true;
            } else {
                if ($user != null) {
                    if ($user->email_verified == 1) {
                        $canLogin = true;
                    } else {
//                        return response()->json(['status' => '201', 'message' => trans('messages.verify_mail')]);
                        return redirect()->back();
                    }

                }
            }
        }

        if ($canLogin && Auth::attempt(['email' => $nameoremail, 'password' => $request->password], $remember)) {
            // return response()->json(['status' => '200', 'message' => trans('auth.login_success')]);
            //save to loginSessions
            $login_session = new LoginSession();
            $login_session->user_id = Auth::user()->id;
            $login_session->user_name = Timeline::where('id', Auth::user()->id)->first()->username;
            $login_session->ip_address = $_SERVER['REMOTE_ADDR'];
            $login_session->machine_name = gethostname();
            $login_session->os = getOS();
            $login_session->browser = getBrowser();

            // get location
            $PublicIP = get_client_ip();
//            $json     = file_get_contents("http://ipinfo.io/$PublicIP/geo");
//            $json     = json_decode($json, true);
//            $country  = $json['country'];
//            $region   = $json['region'];
//            $city     = $json['city'];
//            $login_session->location = $region." ".$city;
            $login_session->date = date("Y-m-d");
            $login_session->save();
            return redirect('/');
        } else {
            // return response()->json(['status' => '201', 'message' => trans('auth.login_failed')]);
            return redirect()->back();
        }
    }
}
