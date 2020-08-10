<?php

namespace App\Http\Controllers;

use App\Notification;
use App\Payment;
use App\Setting;
use App\Subscription;
use App\Timeline;
use App\User;
use Cassandra\Date;
use Cassandra\Time;
use http\Env\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Teepluss\Theme\Facades\Theme;
use Log;
use Illuminate\Support\Facades\DB;

// require_once('../vendor/stripe/init.php');
require_once('/home/fansplatformmjmd/fan/vendor/stripe/init.php');

class CheckoutController extends Controller
{

    private $publishable_key;
    private $secret_key;
    private $client_id;
    private $base_price;
    private $state;
    private $connect_secret_key;
    private $stripe;

    function __construct()
    {
        $this->publishable_key = config('checkout.publishable_key');
        $this->secret_key = config('checkout.secret_key');
        $this->client_id = config('checkout.client_id');
        $this->base_price = 1000;
        $this->stripe_account = null;

        \Stripe\Stripe::setApiVersion("2020-03-02");
    }


    public function getConfig($timeline_id) {
        $user = User::where('timeline_id', '=', $timeline_id)->first();
        $payment = $user->payment()->first();
        return response()->json(['status' => '200', 'publicKey' => $this->publishable_key, 'stripe_id' => $payment->stripe_id]);
    }

    public function createCustomer(Request $param) {

        try {
            $expiries = explode(" / ", $param->expiry);
            $card = [
                'number' => $param->card_number,
                'exp_month' => $expiries[0],
                'exp_year' => $expiries[1],
                'cvc' => $param->cvv
            ];
            \Stripe\Stripe::setApiKey($this->secret_key);
            $token = \Stripe\Token::create([
                'card' => $card,
            ]);

            $customer = \Stripe\Customer::create([
                'name' => $param->card_name,
                'email' => Auth::user()->email,
                'description' => $param->card_name,
                'source' => $token
            ]);
            return response()->json(['status' => 200, 'data' => $customer]);
        } catch (Exception $e) {

            return response()->json(['status' => 400, 'data' => $e->getMessage()]);
        }
    }
    
    public function deleteCustomer($customerId) {
        try {
            
            $stripeClient = new \Stripe\StripeClient($this->secret_key);
            $customer = $stripeClient->customers->delete($customerId);
            return response()->json(['status' => 200, 'data' => $customer]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'data' => $e->getMessage()]);
        }
    }
    
    public function createPrice($price) {
        try {

            \Stripe\Stripe::setApiKey($this->secret_key);
            $user = Auth::user();
            $timeline = Timeline::find($user->timeline_id);
            $payment = $user->payment;
            $product_id = $payment->product_id;
            $stripe_price = null;
            $stripe_customer = null;

            $product = \Stripe\Product::create([
                'name' => $timeline->username,
            ], ["stripe_account" => $payment->stripe_id]);

            $product_id = $product->id;
            $payment->update([
                'product_id' => $product_id,
            ]);

            $stripe_price = \Stripe\Price::create([
                'unit_amount' => $price * 100,
                'currency' => 'usd',
                'recurring' => ['interval' => 'month'],
                'product' => $product_id,
            ], ["stripe_account" => $payment->stripe_id]);

//            $stripe_customer =  \Stripe\Customer::create([
//                'email' => $user->email,
//                'description' => $timeline->username,
//                'source' => $this->connect_secret_key
//            ], ["stripe_account" => $payment->stripe_id]);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
//        return [$stripe_price, $stripe_customer];
        return [$stripe_price];
    }


    public function createCheckoutSession($timeline_id)
    {

        \Stripe\Stripe::setApiKey($this->secret_key);
        $user = User::where('timeline_id', '=', $timeline_id)->first();
        $timeline = Timeline::where('id', $timeline_id)->first();
        $payment = $user->payment()->first();
        $domain_url = url('/');

        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'subscription',
            'customer_email' => Auth::user()->email,
            'line_items' => [[
                'quantity' => 1,
                'price' => $payment->stripe_price_id,
            ]],
            'metadata' => ['follower' => Auth::user()->id, 'following' => $timeline_id],
            'subscription_data' => [
                'application_fee_percent' => config('checkout.platform_fee'),
            ],
            'success_url' => $domain_url.'/'.$timeline->username,
            'cancel_url' => $domain_url,
        ], ["stripe_account" => $payment->stripe_id]);
        return response()->json(['status' => '200', 'sessionId' => $checkout_session['id'], 'message' => $checkout_session]);
    }

    // Connected account
    public function getOAuthLink(Request $request) {

//        $user = Auth::user();
//
//        //update user with bank details
//        $data = $request->all();
//
//        $payment_info = $user->payment()->first();
//        if ($payment_info == null) {
//            $payment_info = new Payment();
//        }
//        $payment_info->user_id = $user->id;
//        $payment_info->is_active = false;
//        $payment_info->save();
//
//        $payment_info->update($data);

        $this->state = bin2hex(random_bytes('16'));
        session(['state' => $this->state]);

        $params = array(
            'state' => $this->state,
            'client_id' => $this->client_id,
        );
        $url = 'https://connect.stripe.com/express/oauth/authorize?'.http_build_query($params).'&suggested_capabilities[]=transfers';
        return redirect($url);
        // return response()->json(['url' => $url]);
    }

    public function authorizeOAuth(Request $request) {

        \Stripe\Stripe::setApiKey($this->secret_key);

        if (session('state') != $request->input('state')) {
            return response()->json(['status' => '403', 'error' => 'Incorrect state parameter']);
        }

        $code = $request->input('code');

        try {
            $stripeResponse = \Stripe\OAuth::token([
                'grant_type' => 'authorization_code',
                'code' => $code
            ]);
        } catch (\Stripe\Error\OAuth\InvalidGrant $exception) {
            return response()->json(['status' => '400', 'message' => 'Invalid Authorization code'.$code]);
        } catch (\Exception $e) {
            return response()->json(['status' => '500', 'message' => $e->getMessage()]);
        }

        $connectedAccountId = $stripeResponse->stripe_user_id;

        // Create login link
        $loginLinkObj = \Stripe\Account::createLoginLink($connectedAccountId, []);

        $user = Auth::user();
        $timeline = Timeline::where('id', $user->timeline_id)->first();
        $payment_info = $user->payment()->first();
        $payment_info->stripe_id = $connectedAccountId;
        $payment_info->dashboard_url = $loginLinkObj->url;
        $payment_info->save();

        $user->is_bank_set = true;
        $user->save();

        //create customer
        $this->connect_secret_key = $stripeResponse->access_token;
        \Stripe\Stripe::setApiKey($this->connect_secret_key);
        \Stripe\Customer::create(
            ["email" => Auth::user()->email],
            ["address" => Auth::user()->payment->city],
            ["name" => $timeline->username],
            ["description" => "Subscription for media"],
            ["stripe_account" => $connectedAccountId]
        );

        return redirect($timeline->username.'/settings/addbank');
    }

    public function createSubscription(Request $request) {

        \Stripe\Stripe::setApiKey($this->secret_key);

        if (session('state') != $request->input('state')) {
            return response()->json(['status' => '403', 'error' => 'Incorrect state parameter']);
        }

        $code = $request->input('code');

        try {
            $stripeResponse = \Stripe\OAuth::token([
                'grant_type' => 'authorization_code',
                'code' => $code
            ]);
        } catch (\Stripe\Error\OAuth\InvalidGrant $exception) {
            return response()->json(['status' => '400', 'message' => 'Invalid Authorization code'.$code]);
        } catch (\Exception $e) {
            return response()->json(['status' => '500', 'message' => $e->getMessage()]);
        }

        $connectedAccountId = $stripeResponse->stripe_user_id;

        // Create login link
        $loginLinkObj = \Stripe\Account::createLoginLink($connectedAccountId, []);

        $user = Auth::user();
        $timeline = Timeline::where('id', $user->timeline_id)->first();
        $payment_info = $user->payment()->first();
        $payment_info->stripe_id = $connectedAccountId;
        $payment_info->dashboard_url = $loginLinkObj->url;
        $payment_info->save();

        $user->is_bank_set = true;
        $user->save();

        //create customer
//        $this->connect_secret_key = $stripeResponse->access_token;
//        \Stripe\Stripe::setApiKey($this->connect_secret_key);
//        \Stripe\Customer::create(
//            ["email" => Auth::user()->email],
//            ["address" => Auth::user()->payment->city],
//            ["name" => $timeline->username],
//            ["description" => "Subscription for media"],
//            ["stripe_account" => $connectedAccountId]
//        );

        return redirect($timeline->username.'/settings/addbank');
    }


    public function deleteSubscription($request) {

        \Stripe\Stripe::setApiKey($this->secret_key);
        $subscription = \Stripe\Subscription::retrieve($request->subscription_id, ["stripe_account" => Payment::where('user_id', $request->leader_id)->first()->stripe_id]);
        $subscription->cancel();
    }


    public function subscribe($username) {

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle($username.' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));
        return $theme->scope('users/subscribe-modal', compact('username'))->render();
    }

    public function follow($timeline_id, $user_id)
    {
        $follow = User::where('timeline_id', '=', $timeline_id)->first();
        $timeline = Timeline::where('id', $timeline_id)->first();

        if (!$follow->followers->contains($user_id)) {
            $follow->followers()->attach($user_id, ['status' => 'approved']);

            $user = User::find($user_id);

            try{
                //Notify the user for follow
                Notification::create(['user_id' => $timeline_id, 'timeline_id' => $timeline_id, 'notified_by' => $user_id, 'description' => Timeline::where('id', $user_id)->first()->username.' '.trans('common.is_following_you'), 'type' => 'follow']);
            }catch(\Exception $e){
            }

            return response()->json(['status' => '200', 'followed' => true, 'message' => 'successfully followed']);
        } else {
            $follow->followers()->detach([$user_id]);

            try{
                //Notify the user for follow
                Notification::create(['user_id' => $timeline_id, 'timeline_id' => $timeline_id, 'notified_by' => $user_id, 'description' => Timeline::where('id', $user_id)->first()->username.' '.trans('common.is_unfollowing_you'), 'type' => 'unfollow']);
            }catch(\Exception $e){
            }

            return response()->json(['status' => '200', 'followed' => false, 'message' => 'successfully unFollowed']);
        }
    }

    public function webhook(Request $request) {

        $webhook_secret = 'whsec_Q7L23oi0augJo69ilxWFusbKhN75oDzW';
        $payload = $request->getContent();
        $sig_header = $request->header('stripe-signature');

        $event = null;

        try {
            // Make sure the event is coming from Stripe by checking the signature header
            $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $webhook_secret);
        }
        catch (Exception $e) {
            http_response_code(403);
            echo json_encode([ 'error' => $e->getMessage() ]);
            exit;
        }

        $session = $event->data->object;
        if ($event->type == 'account.updated') {

            $payment = Payment::where('stripe_id', $session->id)->first();

            if ($session->capabilities->transfers == 'active') {
                if ($payment != null) {
                    $payment->is_active = true;
                    $payment->save();

                    try {
                        Notification::create(['user_id' => $payment->user_id, 'timeline_id' => $payment->user_id, 'notified_by' => $payment->user_id, 'description' => trans('common.bank_set_success'), 'type' => 'subscribe']);
                    } catch (\Exception $e) {

                    }
                }
            }
            else {
                //Notify the bank details
                try {
                    Notification::create(['user_id' => $payment->user_id, 'timeline_id' => $payment->user_id, 'notified_by' => $payment->user_id, 'description' => trans('common.bank_set_fail'), 'type' => 'subscribe']);
                } catch (\Exception $e) {

                }
            }
        }
        if($event->type == 'checkout.session.completed') {

            // follow
            $follwer_id = $session->metadata->follower;
            $leader_id = $session->metadata->following;
            $username = Timeline::where('id', $follwer_id)->first()->username;

            $this->follow($leader_id, $follwer_id);

            $affected = DB::table('followers')
                ->where('follower_id', $follwer_id)
                ->where('leader_id', $leader_id)
                ->update(['subscription_id' => $session->metadata->subscription]);

            $subscription = new Subscription();
            $subscription->subscription_id = $session->subscription;
            $subscription->follower_id = $follwer_id;
            $subscription->leader_id = $leader_id;
            $subscription->start_at = date('Y-m-d H:i');
            $subscription->save();

            return redirect($username);
        }
        if ($event->type == 'customer.subscription.deleted') {

            $subscription = Subscription::where('subscription_id', $session->id)->first();
            $follow = User::where('timeline_id', '=', $subscription->leader_id)->first();
            $username = Timeline::where('id', $subscription->leader_id)->first()->username;
            $follower_id = $subscription->follower_id;
            $leader_id = $subscription->leader_id;
            //$follow->followers()->detach([$follower_id]);
            $subscription->cancel_at = date('Y-m-d H:i');
            $subscription->save();

            $this->follow($leader_id, $follower_id);
            
            // app('App\Http\Controllers\TimelineController')->posts($username);
        }
    }

}
