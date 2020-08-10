<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Event;
use App\Group;
use App\Hashtag;
use App\Http\Requests;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\LoginSession;
use App\Media;
use App\Notification;
use App\Page;
use App\Payment;
use App\Repositories\UserRepository;
use App\Role;
use App\Setting;
use App\Timeline;
use App\User;
use App\Wallpaper;
use Auth;
use Carbon\Carbon;
use Flash;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Teepluss\Theme\Facades\Theme;
use Validator;

class UserController extends AppBaseController
{
    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo, Request $request)
    {
        $this->request = $request;
        $this->checkCensored();

        $this->userRepository = $userRepo;
        $this->middleware('disabledemo', ['only' => 'deleteMe']);
    }

    protected function checkCensored()
    {
        $messages['not_contains'] = 'The :attribute must not contain banned words';
        if($this->request->method() == 'POST') {
            // Adjust the rules as needed
            $this->validate($this->request,
                [
                    'first_name'    => 'not_contains',
                    'last_name'     => 'not_contains',
                    'username'      => 'not_contains',
                    'name'          => 'not_contains',
                    'about'         => 'not_contains',
                    'title'         => 'not_contains',
                    'description'   => 'not_contains',
                    'tag'           => 'not_contains',
                    'email'         => 'not_contains',
                    'body'          => 'not_contains',
                    'link'          => 'not_contains',
                    'address'       => 'not_contains',
                    'website'       => 'not_contains',
                    'display_name'  => 'not_contains',
                    'key'           => 'not_contains',
                    'value'         => 'not_contains',
                    'subject'       => 'not_contains',
                    'country'       => 'not_contains',
                    'city'          => 'not_contains',
                    'email'         => 'email',
                ],$messages);
        }
    }

    /**
     * Display a listing of the User.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $this->userRepository->pushCriteria(new RequestCriteria($request));
        $users = $this->userRepository->all();

        return view('users.index')
            ->with('users', $users);
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        $input = $request->all();

        $user = $this->userRepository->create($input);

        Flash::success('User saved successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Display the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        return view('users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        return view('users.edit')->with('user', $user);
    }

    /**
     * Update the specified User in storage.
     *
     * @param int               $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        $user = $this->userRepository->update($request->all(), $id);

        Flash::success('User updated successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        $this->userRepository->delete($id);

        Flash::success('User deleted successfully.');

        return redirect(route('users.index'));
    }

    public function userGeneralSettings($username)
    {
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.general_settings').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/settings/general', compact('username'))->render();
    }

    public function userEditProfile($username)
    {
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.general_settings').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/settings/profile', compact('username'))->render();
    }

    public function userPrivacySettings($username)
    {
        $timeline = Timeline::where('username', $username)->first();

        if ($timeline == null) {
            return Redirect::to('/');
        }

        $settings = DB::table('user_settings')->where('user_id', $timeline->user->id)->first();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.privacy_settings').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/settings/privacy', compact('settings'))->render();
    }

    public function userPasswordSettings($username)
    {
        $timeline = Timeline::where('username', $username)
            ->get()->toArray();

        if ($timeline == null) {
            return Redirect::to('/');
        }

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');

        return $theme->scope('users/settings/password')->render();
    }

    public function affliates($username)
    {
        $referrals = User::where('affiliate_id', Auth::user()->id)->where('id', '!=', Auth::user()->id)->paginate(Setting::get('items_page', 10));

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.affiliates').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/affliates', compact('referrals'))->render();
    }

    public function loginSessions($username)
    {
        $users = LoginSession::where('user_id', Auth::user()->id)->paginate(Setting::get('items_page', 10));

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.login_session').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/login_sessions', compact('users'))->render();
    }

    public function deactivate($username)
    {
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.deactivate_account').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/deactivate')->render();
    }

    public function deleteMe($username)
    {
        $timeline = Timeline::where('username', $username)->first();
        $user = $timeline->user;

        // Announcements delete
        $user->announcements()->detach();

        //Deleting post related data for the user
        $user->postLikes()->detach();
        $user->postFollows()->detach();
        $user->postShares()->detach();
        $user->postReports()->detach();
        $user->postTags()->detach();

        //other user posts on logedin user timeline
        $user->deleteOthers();

        foreach ($user->posts()->get() as $post) {
            $post->deleteMe();
        }

        //Deleting page related data for the user
        $user->pageLikes()->detach();
        $admin_role_id = Role::where('name', '=', 'admin')->first();


        foreach ($user->pages()->get() as $page) {
            if (count($page->users()
                    ->where('page_user.role_id', $admin_role_id->id)
                    ->where('page_user.user_id', $user->id)
                    ->get()) != 0) {
                $page->users()->detach();
                $page->likes()->detach();
                $page->timeline->reports()->detach();
                //Deleting page notifications
                $timeline_alerts = $page->timeline()->with('notifications')->first();
                if (count($timeline_alerts->notifications) != 0) {
                    foreach ($timeline_alerts->notifications as $notification) {
                        $notification->delete();
                    }
                }

                //Deleting page posts
                $timeline_posts = $page->timeline()->with('posts')->first();
                if (count($timeline_posts->posts) != 0) {
                    foreach ($timeline_posts->posts as $post) {
                        $post->deleteMe();
                    }
                }
                $page_timeline = $page->timeline();

                //Deleting page albums
                foreach ($page->timeline->albums()->get() as $album) {
                    foreach ($album->photos()->get() as $media) {
                        $saveMedia = $media;
                        $album->photos()->detach($media->id);
                    }
                    $album->delete();
                }
                $page->delete();
                $page_timeline->delete();
            } else {
                $user->pages()->detach($page->id);
            }
        }

        //Deleting comments
        $user->commentLikes()->detach();

        foreach ($user->comments()->get() as $comment) {
            $comment->comments_liked()->detach();
            $dependencies = Comment::where('parent_id', $comment->id)->update(['parent_id' => null]);
            // $comment->replies()->detach();
            $comment->update(['parent_id' => null]);
            $comment->delete();
        }

        //Deleting Events
        $user->events()->detach();

        foreach ($user->userEvents()->get() as $event) {
            $event->users()->detach();
            $event->timeline->reports()->detach();
            // Deleting event posts
            $event_posts = $event->timeline()->with('posts')->first();

            if (count($event_posts->posts) != 0) {
                foreach ($event_posts->posts as $post) {
                    $post->deleteMe();
                }
            }

            //Deleting event notifications
            $timeline_alerts = $event->timeline()->with('notifications')->first();

            if (count($timeline_alerts->notifications) != 0) {
                foreach ($timeline_alerts->notifications as $notification) {
                    $notification->delete();
                }
            }

            $event_timeline = $event->timeline();

            //Deleting event albums
            foreach ($event->timeline->albums()->get() as $album) {
                foreach ($album->photos()->get() as $media) {
                    $saveMedia = $media;
                    $album->photos()->detach($media->id);
                }
                $album->delete();
            }
            $event->delete();
            $event_timeline->delete();
        }

        //Deleting top friends, followers and following
        $user->followers()->detach();
        $user->following()->detach();

        //Deleting notifications for the user
        $user->notifications()->delete();
        $user->notifiedBy()->delete();

        //Deleting timeline and related data
        $user->timelineReports()->detach();
        $timeline->reports()->detach();

        //Deleting user albums
        foreach ($timeline->albums()->get() as $album) {
            foreach ($album->photos()->get() as $media) {
                $saveMedia = $media;
                $album->photos()->detach($media->id);
            }
            $album->delete();
        }

        //Deleting subscriptions,participants, messages and user settings
        $participants = DB::table('participants')->where('user_id', $user->id)->get();
        if(!$participants->isEmpty())
        {
            foreach($participants as $participant)
            {
                $users = DB::table('participants')->where('thread_id',$participant->thread_id)->where('user_id','!=', $user->id)->get();
                if($users->count() == 1)
                {
                    DB::table('participants')->where('thread_id',$participant->thread_id)->delete();
                    DB::table('messages')->where('thread_id',$participant->thread_id)->delete();
                    DB::table('threads')->where('id',$participant->thread_id)->delete();
                }
                elseif($users->count() > 1)
                {
                    DB::table('participants')->where('thread_id',$participant->thread_id)
                        ->where('user_id',$user->id)
                        ->delete();
                    $owner = DB::table('messages')->where('thread_id',$participant->thread_id)
                        ->where('user_id',$user->id)
                        ->get();
                    if(!$owner->isEmpty())
                    {
                        DB::table('messages')->where('thread_id',$participant->thread_id)
                            ->where('user_id',$user->id)
                            ->update(['user_id' => $users->first()->user_id]);
                    }
                }
            }
        }

        DB::table('user_settings')->where('user_id', $user->id)->delete();

        //Deleting groups
        $admin_role_id = Role::where('name', '=', 'admin')->first();
        foreach ($user->groups()->get() as $group) {
            if (count($group->users()
                    ->where('group_user.role_id', $admin_role_id->id)
                    ->where('group_user.user_id', $user->id)
                    ->get()) != 0) {
                //Deleting events in a group
                if (count($group->getEvents()) != 0) {
                    foreach ($group->getEvents() as $event) {
                        $event->users()->detach();
                        $event->timeline->reports()->detach();
                        // Deleting event posts
                        $event_posts = $event->timeline()->with('posts')->first();

                        if (count($event_posts->posts) != 0) {
                            foreach ($event_posts->posts as $post) {
                                $post->deleteMe();
                            }
                        }

                        //Deleting event notifications
                        $timeline_alerts = $event->timeline()->with('notifications')->first();

                        if (count($timeline_alerts->notifications) != 0) {
                            foreach ($timeline_alerts->notifications as $notification) {
                                $notification->delete();
                            }
                        }

                        $event_timeline = $event->timeline();
                        $event->delete();
                        $event_timeline->delete();
                    }
                }
                $group->users()->detach();

                //Deleting group notifications
                $timeline_alerts = $group->timeline()->with('notifications')->first();

                if (count($timeline_alerts->notifications) != 0) {
                    foreach ($timeline_alerts->notifications as $notification) {
                        $notification->delete();
                    }
                }

                //Deleting group posts
                $timeline_posts = $group->timeline()->with('posts')->first();

                if (count($timeline_posts->posts) != 0) {
                    foreach ($timeline_posts->posts as $post) {
                        $post->deleteMe();
                    }
                }
                $group->timeline->reports()->detach();
                $group_timeline = $group->timeline();

                //Deleting group albums
                foreach ($group->timeline->albums()->get() as $album) {
                    foreach ($album->photos()->get() as $media) {
                        $saveMedia = $media;
                        $album->photos()->detach($media->id);
                    }
                    $album->delete();
                }
                $group->delete();
                $group_timeline->delete();
            } else {
                $user->groups()->detach($group->id);
            }
        }

        //Finally delete timeline and then the user
        $user->delete();
        $timeline->delete();


        return redirect('/');
    }

    public function emailNotifications($username)
    {
        $timeline = Timeline::where('username', $username)->with('user')->first();
        $user = $timeline->user;
        $userSettings = $user->getUserSettings($user->id);

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.email_notifications').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/notifications', compact('userSettings'))->render();
    }

    public function addBank($username)
    {
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.general_settings').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/settings/addbank', compact('username'))->render();
    }

    public function addPayment($username)
    {
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.general_settings').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/settings/addpayment', compact('username'))->render();
    }

    public function updateEmailNotifications($username, Request $request)
    {
        if (Config::get('app.env') == 'demo' && $request->username == 'bootstrapguru') {
            Flash::error(trans('common.disabled_on_demo'));

            return Redirect::back();
        }
        $timeline = Timeline::where('username', $username)->with('user')->first();
        $user = $timeline->user;
        $input = $request->except('_token');

        $user_settings = [
            'email_follow'        => $input['email_follow'],
            'email_like_post'     => $input['email_like_post'],
            'email_post_share'    => $input['email_post_share'],
            'email_comment_post'  => $input['email_comment_post'],
            'email_like_comment'  => $input['email_like_comment'],
            'email_reply_comment' => $input['email_reply_comment'],
            'email_join_group'    => $input['email_join_group'],
            'email_like_page'     => $input['email_like_page'], ];

        $privacy = DB::table('user_settings')->where('user_id', $user->id)
            ->update($user_settings);

        Flash::success(trans('messages.email_notifications_updated_success'));

        return Redirect::back();
    }

    public function SaveUserPrivacySettings($username, Request $request)
    {
        $timeline = Timeline::where('username', $username)->with('user')->first();
        $user = $timeline->user;
        $input = $request->except('_token');

        $user_settings = [
            'confirm_follow'        => $input['confirm_follow'],
            'comment_privacy'       => $input['comment_privacy'],
            'follow_privacy'        => $input['follow_privacy'],
            'post_privacy'          => $input['post_privacy'],
            'timeline_post_privacy' => $input['timeline_post_privacy'],
            'message_privacy'       => $input['message_privacy'], ];

        $privacy = DB::table('user_settings')->where('user_id', $user->id)
            ->update($user_settings);

        Flash::success(trans('messages.privacy_settings_updated_success'));

        return Redirect::back();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function generalSettingsValidator(array $data)
    {
        $messages = [
            'no_admin' => 'The name admin is restricted for :attribute'
        ];
        return Validator::make($data, [
            'username' => 'required|max:16|min:5|alpha_num|no_admin|unique:timelines,username,'.Auth::user()->timeline->id,
            'name'     => 'required',
            'email'    => 'unique:users,email,'.Auth::id(),
            'subscribe_price'   => 'between:0.01,999.99',
        ],$messages);

    }

    protected function profileValidator(array $data)
    {
        $messages = [
            'no_admin' => 'The name admin is restricted for :attribute'
        ];
        return Validator::make($data, [
        ],$messages);

    }

    public function saveProfile(Request $request)
    {
        if (Config::get('app.env') == 'demo' && $request->username == 'bootstrapguru') {
            Flash::error(trans('common.disabled_on_demo'));

            return Redirect::back();
        }

        $data = $request->all();
        $validator = $this->profileValidator($data);
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors($validator->errors());
        }

        $user = User::find(Auth::user()->id);
        $user_details = $request->all();
        $user_details['birthday'] = date('Y-m-d', strtotime($request->birthday));
        $user->update($user_details);
        $user->timeline->about = $request->about;
        $user->timeline->save();

        Flash::success(trans('messages.general_settings_updated_success'));
        return redirect()->back();
    }



    public function saveUserGeneralSettings(Request $request)
    {
        if (Config::get('app.env') == 'demo' && $request->username == 'bootstrapguru') {
            Flash::error(trans('common.disabled_on_demo'));

            return Redirect::back();
        }

        $data = $request->all();
        $data['username'] = $request->new_username;
        $validator = $this->generalSettingsValidator($data);
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors($validator->errors());
        }

        $user = User::find(Auth::user()->id);
        $timeline = Timeline::find($user->timeline_id);
        $timeline->update([
            'username' => $data['username'],
            'name'     => $data['name'],
        ]);

        $user_details = $request->except('username', 'name', 'price');
        $user->update($user_details);

        // Save price
        $payment = $user->payment;
        $stripe_price_id = null;
        $stripe_customer_id = null;
        $price = $request->input('subscribe_price');
        if ($payment != NULL) {

            if ($price > 0) {
                // check stripe connected account before save price
                if ($payment->stripe_id == NULL || $payment->is_active == 0) {
                    Flash::error(trans('You must add bank details before set price.'));
                    return redirect($request->new_username.'/settings/general');
                }

                $stripe_response = app('App\Http\Controllers\CheckoutController')->createPrice($price);
                if (isset($stripe_response[0])) {
                    $stripe_price_id = $stripe_response[0]->id;
                }
//                if (isset($stripe_response[1])) {
//                    $stripe_customer_id = $stripe_response[1]->id;
//                }
            }

            $payment->update([
                'price' => $price,
                'stripe_price_id' => $stripe_price_id,
                //'stripe_customer_id' => $stripe_customer_id,
                'password' => $user->email,
            ]);

            $user->update([
                'price' => $data['subscribe_price'],
            ]);


        } else {
            Flash::error('messages.general_settings_price_error');
        }

        Flash::success(trans('messages.general_settings_updated_success'));

        return redirect($request->new_username.'/settings/general');
    }

    protected function paymentDetailsValidator(array $data) {
        $messages = [
            'subscribe_content_confirm.required' => 'You have to agree to adult content confirmation',
        ];
        return Validator::make($data, [
            'card_name' => 'required',
            'card_number' => 'required',
            'cvv' => 'required',
            'expiry' => 'required',
            // 'subscribe_content_confirm' => 'required',
        ], $messages);
    }

    public function saveUserPaymentDetails(Request $request) {
        // create customer
        $data = $request->all();
        $validator = $this->paymentDetailsValidator($data);
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors($validator->errors());
        }
        $user = User::find(Auth::user()->id);
        
        $payment_info = $user->payment()->first();
        if ($payment_info == NULL) {
            $payment_info = new Payment();
            $payment_info->user_id = $user->id;
        }
        
        $customer_response = app('App\Http\Controllers\CheckoutController')->createCustomer($request);
        if ($customer_response->getData()->status == '200') {
            if ($payment_info->stripe_customer_id != NULL) {
                $customer_del_response = app('App\Http\Controllers\CheckoutController')->deleteCustomer($payment_info->stripe_customer_id);
                
            }
            
            $payment_info->stripe_customer_id = $customer_response->getData()->data->id;
        }
        else {
            print_r('create customer failed');
        }
        
        // $payment_info->update($data); 
        $payment_info->billing_address = $request->billing_address;
        $payment_info->billing_city = $request->billing_city;
        $payment_info->billing_state = $request->billing_state;
        $payment_info->billing_zip = $request->billing_zip;
        $payment_info->save();
        $user->is_payment_set = true;
        $user->save();
        Flash::success(trans('messages.general_settings_updated_success'));

        // return redirect()->back();
    }


    protected function bankDetailsValidator(array $data) {

        $messages = [
            'sell_content_confirm.required' => 'You have to agree to adult content confirmation',
        ];

        return \Illuminate\Support\Facades\Validator::make($data, [
            'name' => 'required',
            'country' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'gender' => 'required',
            // 'sell_content_confirm' => 'required'
        ], $messages);
    }

    public function saveUserBankDetails(Request $request) {

        $data = $request->all();
        $validator = $this->bankDetailsValidator($data);
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors($validator->errors());
        }

        $user = \Illuminate\Support\Facades\Auth::user();

        //update user with bank details
        $data = $request->all();

        $payment_info = $user->payment()->first();
        if ($payment_info == null) {
            $payment_info = new Payment();
        }
        $payment_info->user_id = $user->id;
        $payment_info->is_active = false;
        $payment_info->save();
        $payment_info->update($data);

        return redirect('/checkout/get-oauth-link');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function passwordValidator(array $data)
    {
        return Validator::make($data, [
            'new_password'     => 'required|min:6',
            'current_password' => 'required|min:6',
        ]);
    }

    public function saveNewPassword(Request $request)
    {
        if (Config::get('app.env') == 'demo' && $request->username == 'bootstrapguru') {
            Flash::error(trans('common.disabled_on_demo'));

            return Redirect::back();
        }

        $validator = $this->passwordValidator($request->all());

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors($validator->errors());
        }

        $user = User::findOrFail(Auth::user()->id);
        if (Hash::check($request->current_password, Auth::user()->password)) {
            if ($request->current_password != $request->new_password) {
                $user->password = bcrypt($request->new_password);
                $user->save();
                Flash::success(trans('messages.new_password_updated_success'));

                return redirect()->back();
            } else {
                Flash::error(trans('messages.password_no_match'));

                return redirect()->back()->with('Password not match.');
            }
        } else {
            Flash::error(trans('messages.old_password_no_match'));

            return redirect()->back()->with('Password not match.');
        }
    }

    public function messages($username)
    {
        $timeline = Timeline::where('username', $username)->with('user')->first();
        $user = $timeline->user;
        $trending_tags = Hashtag::orderBy('count', 'desc')->get()->random(Setting::get('min_items_page', 5));
        $following = $user->following()->get();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.messages').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/messages', compact('following', 'trending_tags'))->render();
    }

    public function membersList($username)
    {
        $timeline = Timeline::where('username', $username)->with('groups')->first();
        $group = $timeline->groups;
        $group_members = $group->members();
        $group_events = $group->getEvents($group->id);
        $ongoing_events = $group->getOnGoingEvents($group->id);
        $upcoming_events = $group->getUpcomingEvents($group->id);
        if ($ongoing_events == '')$ongoing_events = [];
        if ($upcoming_events == '')$upcoming_events = [];

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.add_members').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/addmembers', compact('timeline', 'group', 'group_members', 'group_events', 'ongoing_events', 'upcoming_events'))->render();
    }

    public function pageMembersList($username)
    {
        // if($username != Auth::user()->username) {
        //     return Redirect('/'.$username);
        // }
        $timeline = Timeline::where('username', $username)->with('page')->first();
        $page = $timeline->page;
        $page_members = $page->members();
        if (!$page->is_admin(Auth::user()->id)) {
            return redirect($username);
        }

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.add_members').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/addpagemembers', compact('timeline', 'page', 'page_members'))->render();
    }

    public function getUsersJoin(Request $request)
    {
        $timelines = Timeline::where('username', 'like', "%{$request->searchname}%")->where('type', 'user')->where('username', '!=', Auth::user()->username)->get();
        $group_id = $request->group_id;
        $group = Group::findOrFail($request->group_id);

        $users = new \Illuminate\Database\Eloquent\Collection();


        foreach ($timelines as $key => $timeline) {
            $user = $timeline->user()->with(['groups' => function ($query) use ($group_id) {
                $query->where('groups.id', $group_id);
            }])->get();

            $users->add($user);
        }

        return response()->json(['status' => '200', 'data' => $users]);
    }

    public function getMembersJoin(Request $request)
    {
        $timelines = Timeline::where('username', 'like', "%{$request->searchname}%")->where('type', 'user')->where('username', '!=', Auth::user()->username)->get();
        $page_id = $request->page_id;
        $page = Page::find($request->page_id);

        $users = new \Illuminate\Database\Eloquent\Collection();


        foreach ($timelines as $key => $timeline) {
            $user = $timeline->user()->with(['pages' => function ($query) use ($page_id) {
                $query->where('pages.id', $page_id);
            }])->get();

            $users->add($user);
        }

        return response()->json(['status' => '200', 'data' => $users]);
    }

    public function getUsersMentions(Request $request)
    {
        $requestData = $request->all();
        $timelines = Timeline::where('name', 'like', "%{$requestData['query']}%")->orWhere('username', 'like', "%{$requestData['query']}%")->where('type', 'user')->get();

        $users = $timelines;
        foreach ($timelines as $key => $value) {
            if ($value->avatar != null) {
                $users[$key]['image'] = url('user/avatar/'.$value->avatar->source);
            } else {
                $gender = isset($value->user) ? $value->user->gender : 'male';
                $users[$key]['image'] = url('user/avatar/default-'.$gender.'-avatar.png');
            }
        }

        return response()->json($users);
    }

    public function addingMembersGroup(Request $request)
    {
        $group = Group::findOrFail($request->group_id);

        if ($request->user_status == 'Joined') {
            $group->users()->detach([$request->user_id]);

            return response()->json(['status' => '200', 'added' => true, 'message' => 'successfully unjoined']);
        } else {
            $chkUser = $group->chkGroupUser($request->group_id, $request->user_id);

            if ($chkUser) {
                $group_user = $group->updateStatus($chkUser->id);
                if ($group_user) {
                    return response()->json(['status' => '200', 'added' => true, 'message' => 'successfully accepted']);
                }
            } else {
                $user_role = Role::where('name', '=', 'user')->first();
                $group->users()->attach($request->user_id, ['group_id' => $request->group_id, 'role_id' => $user_role->id, 'status' => 'approved']);

                return response()->json(['status' => '200', 'added' => true, 'message' => 'successfully added']);
            }
        }
    }

    // public function addingMembersPage(Request $request)
    // {
    //     $page = Page::findOrFail($request->group_id);

    //         if ($request->user_status == "Joined")
    //         {
    //             $page->users()->detach([$request->user_id]);
    //             return response()->json(['status' => '200','added' => true,'message'=>'successfully unjoined']);
    //         }
    //         else
    //         {
    //             $chkUser = $page->chkPageUser($request->group_id,$request->user_id);

    //             if($chkUser)
    //             {
    //                 $page_user = $page->updateStatus($chkUser->id);
    //                 if($page_user)
    //                 return response()->json(['status' => '200','added' => true,'message'=>'successfully accepted']);
    //             }
    //             else
    //             {
    //               $user_role = Role::where('name','=','user')->first();
    //               $page->users()->attach($request->user_id, array('page_id'=>$request->page_id,'role_id'=>$user_role->id,'status'=>'approved'));
    //               return response()->json(['status' => '200','added' => true,'message'=>'successfully added']);
    //             }

    //         }
    // }

    public function addingMembersPage(Request $request)
    {
        $page = Page::find($request->page_id);

        if ($request->user_status == 'Joined') {
            $page->users()->detach([$request->user_id]);

            return response()->json(['status' => '200', 'added' => true, 'message' => 'successfully unjoined']);
        } else {
            $page->users()->attach($request->user_id, ['page_id' => $request->page_id, 'role_id' => 2, 'active' => 1]);

            return response()->json(['status' => '200', 'added' => true, 'message' => 'successfully added']);
        }
    }

    public function followers($username)
    {
        $timeline = Timeline::where('username', $username)->with('user', 'user.pageLikes', 'user.groups', 'user.followers')->first();
        $user = $timeline->user;
        $joined_groups_count = $user->groups()->where('role_id', '!=', 1)->where('status', '=', 'approved')->get()->count();
        $followers = $user->followers()->where('status', '=', 'approved')->get();
        $followRequests = $user->followers()->where('status', '=', 'pending')->get();
        $following_count = $user->following()->where('status', '=', 'approved')->get()->count();
        $followers_count = $user->followers()->where('status', '=', 'approved')->get()->count();
        $follow_user_status = '';
        $follow_user_status = DB::table('followers')->where('follower_id', '=', Auth::user()->id)
            ->where('leader_id', '=', $user->id)->first();

        if ($follow_user_status) {
            $follow_user_status = $follow_user_status->status;
        }

        $confirm_follow_setting = $user->getUserSettings(Auth::user()->id);
        $follow_confirm = $confirm_follow_setting->confirm_follow;

        $live_user_settings = $user->getUserPrivacySettings(Auth::user()->id, $user->id);
        $privacy_settings = explode('-', $live_user_settings);
        $timeline_post = $privacy_settings[0];
        $user_post = $privacy_settings[1];
        $own_pages = $user->own_pages();
        $own_groups = $user->own_groups();
        $user_events = $user->events()->whereDate('end_date', '>=', date('Y-m-d', strtotime(Carbon::now())))->get();
        $guest_events = $user->getEvents();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.followers').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        // liked_posts
        $liked_post = DB::table('post_likes')->where('user_id', \Illuminate\Support\Facades\Auth::user()->id)->get();

        return $theme->scope('users/followers', compact('timeline', 'user', 'liked_post', 'followers', 'followRequests', 'own_groups', 'own_pages', 'follow_user_status', 'following_count', 'followers_count', 'follow_confirm', 'user_post', 'joined_groups_count', 'user_events', 'guest_events'))->render();
    }

    public function following($username)
    {
        $timeline = Timeline::where('username', $username)->with('user', 'user.pageLikes', 'user.groups')->first();
        $user = $timeline->user;
        $following = $user->following()->where('status', '=', 'approved')->get();
        $followRequests = $user->followers()->where('status', '=', 'pending')->get();
        $following_count = $user->following()->where('status', '=', 'approved')->get()->count();
        $followers_count = $user->followers()->where('status', '=', 'approved')->get()->count();
        $joined_groups_count = $user->groups()->where('role_id', '!=', 1)->where('status', '=', 'approved')->get()->count();
        $follow_user_status = '';
        $follow_user_status = DB::table('followers')->where('follower_id', '=', Auth::user()->id)
            ->where('leader_id', '=', $user->id)->first();

        if ($follow_user_status) {
            $follow_user_status = $follow_user_status->status;
        }

        $confirm_follow_setting = $user->getUserSettings(Auth::user()->id);
        $follow_confirm = $confirm_follow_setting->confirm_follow;

        $live_user_settings = $user->getUserPrivacySettings(Auth::user()->id, $user->id);
        $privacy_settings = explode('-', $live_user_settings);
        $timeline_post = $privacy_settings[0];
        $user_post = $privacy_settings[1];
        $own_pages = $user->own_pages();
        $own_groups = $user->own_groups();
        $user_events = $user->events()->whereDate('end_date', '>=', date('Y-m-d', strtotime(Carbon::now())))->get();
        $guest_events = $user->getEvents();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.following').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        // liked_posts
        $liked_post = DB::table('post_likes')->where('user_id', \Illuminate\Support\Facades\Auth::user()->id)->get();

        return $theme->scope('users/following', compact('timeline', 'user', 'liked_post','following', 'followRequests', 'follow_user_status', 'following_count', 'followers_count', 'follow_confirm', 'user_post', 'joined_groups_count', 'own_pages', 'own_groups', 'user_events', 'guest_events'))->render();
    }

    public function getGuestEvents($username)
    {
        $timeline = Timeline::where('username', $username)->with('user', 'user.pageLikes', 'user.groups')->first();
        $user = $timeline->user;

        $following = $user->following()->where('status', '=', 'approved')->get();
        $followRequests = $user->followers()->where('status', '=', 'pending')->get();
        $following_count = $user->following()->where('status', '=', 'approved')->get()->count();
        $followers_count = $user->followers()->where('status', '=', 'approved')->get()->count();
        $joined_groups_count = $user->groups()->where('role_id', '!=', 1)->where('status', '=', 'approved')->get()->count();
        $follow_user_status = '';
        $follow_user_status = DB::table('followers')->where('follower_id', '=', Auth::user()->id)
            ->where('leader_id', '=', $user->id)->first();

        if ($follow_user_status) {
            $follow_user_status = $follow_user_status->status;
        }

        $confirm_follow_setting = $user->getUserSettings(Auth::user()->id);
        $follow_confirm = $confirm_follow_setting->confirm_follow;

        $live_user_settings = $user->getUserPrivacySettings(Auth::user()->id, $user->id);
        $privacy_settings = explode('-', $live_user_settings);
        $timeline_post = $privacy_settings[0];
        $user_post = $privacy_settings[1];
        $own_pages = $user->own_pages();
        $own_groups = $user->own_groups();
        $user_events = $user->events()->whereDate('end_date', '>=', date('Y-m-d', strtotime(Carbon::now())))->get();
        $guest_events = $user->getEvents();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.following').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/guestevents', compact('timeline', 'user', 'following', 'followRequests', 'follow_user_status', 'following_count', 'followers_count', 'follow_confirm', 'user_post', 'joined_groups_count', 'own_pages', 'own_groups', 'user_events', 'guest_events'))->render();
    }

    public function likedPages($username)
    {
        $timeline = Timeline::where('username', $username)->with('user', 'user.pageLikes', 'user.groups')->first();
        $user = $timeline->user;
        $liked_pages = $user->pageLikes;
        $joined_groups_count = $user->groups()->where('role_id', '!=', 1)->where('status', '=', 'approved')->get()->count();
        $following_count = $user->following()->where('status', '=', 'approved')->get()->count();
        $followers_count = $user->followers()->where('status', '=', 'approved')->get()->count();
        $followRequests = $user->followers()->where('status', '=', 'pending')->get();
        $follow_user_status = '';
        $follow_user_status = DB::table('followers')->where('follower_id', '=', Auth::user()->id)
            ->where('leader_id', '=', $user->id)->first();

        if ($follow_user_status) {
            $follow_user_status = $follow_user_status->status;
        }

        $confirm_follow_setting = $user->getUserSettings(Auth::user()->id);
        $follow_confirm = $confirm_follow_setting->confirm_follow;

        $live_user_settings = $user->getUserPrivacySettings(Auth::user()->id, $user->id);
        $privacy_settings = explode('-', $live_user_settings);
        $timeline_post = $privacy_settings[0];
        $user_post = $privacy_settings[1];
        $own_pages = $user->own_pages();
        $own_groups = $user->own_groups();
        $user_events = $user->events()->whereDate('end_date', '>=', date('Y-m-d', strtotime(Carbon::now())))->get();
        $guest_events = $user->getEvents();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.liked_pages').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/liked-pages', compact('timeline', 'liked_pages', 'user', 'followRequests', 'own_pages', 'own_groups', 'follow_user_status', 'following_count', 'followers_count', 'follow_confirm', 'user_post', 'joined_groups_count', 'user_events', 'guest_events'))->render();
    }

    public function joinedGroups($username)
    {
        $timeline = Timeline::where('username', $username)->with('user', 'user.pageLikes', 'user.groups')->first();
        $user = $timeline->user;
        $admin_role_id = Role::where('name', '=', 'admin')->first();
        $joined_groups = $user->groups()->where('role_id', '!=', $admin_role_id->id)->where('status', '=', 'approved')->get();
        $joined_groups_count = $joined_groups->count();
        $following_count = $user->following()->where('status', '=', 'approved')->get()->count();
        $followers_count = $user->followers()->where('status', '=', 'approved')->get()->count();
        $followRequests = $user->followers()->where('status', '=', 'pending')->get();
        $follow_user_status = '';
        $follow_user_status = DB::table('followers')->where('follower_id', '=', Auth::user()->id)
            ->where('leader_id', '=', $user->id)->first();

        if ($follow_user_status) {
            $follow_user_status = $follow_user_status->status;
        }

        $confirm_follow_setting = $user->getUserSettings(Auth::user()->id);
        $follow_confirm = $confirm_follow_setting->confirm_follow;

        $live_user_settings = $user->getUserPrivacySettings(Auth::user()->id, $user->id);
        $privacy_settings = explode('-', $live_user_settings);
        $timeline_post = $privacy_settings[0];
        $user_post = $privacy_settings[1];
        $own_pages = $user->own_pages();
        $own_groups = $user->own_groups();
        $user_events = $user->events()->whereDate('end_date', '>=', date('Y-m-d', strtotime(Carbon::now())))->get();
        $guest_events = $user->getEvents();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.joined_groups').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/joined-groups', compact('timeline', 'user', 'joined_groups', 'followRequests', 'own_groups', 'own_pages', 'follow_user_status', 'following_count', 'followers_count', 'follow_confirm', 'user_post', 'joined_groups_count', 'user_events', 'guest_events'))->render();
    }

    public function followRequests($username)
    {
        $timeline = Timeline::where('username', $username)->with('user', 'user.pageLikes', 'user.groups')->first();
        $user = $timeline->user;
        $followRequests = $user->followers()->where('status', '=', 'pending')->get();
        $joined_groups_count = $user->groups()->where('role_id', '!=', 1)->where('status', '=', 'approved')->get()->count();
        $following_count = $user->following()->where('status', '=', 'approved')->get()->count();
        $followers_count = $user->followers()->where('status', '=', 'approved')->get()->count();
        $follow_user_status = '';
        $follow_user_status = DB::table('followers')->where('follower_id', '=', Auth::user()->id)
            ->where('leader_id', '=', $user->id)->first();

        if ($follow_user_status) {
            $follow_user_status = $follow_user_status->status;
        }

        $confirm_follow_setting = $user->getUserSettings(Auth::user()->id);
        $follow_confirm = $confirm_follow_setting->confirm_follow;

        $live_user_settings = $user->getUserPrivacySettings(Auth::user()->id, $user->id);
        $privacy_settings = explode('-', $live_user_settings);
        $timeline_post = $privacy_settings[0];
        $user_post = $privacy_settings[1];
        $own_pages = $user->own_pages();
        $own_groups = $user->own_groups();
        $user_events = $user->events()->whereDate('end_date', '>=', date('Y-m-d', strtotime(Carbon::now())))->get();
        $guest_events = $user->getEvents();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.follow_requests').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/follow-requests', compact('timeline', 'user', 'followRequests', 'own_groups', 'own_pages', 'follow_user_status', 'following_count', 'followers_count', 'follow_confirm', 'user_post', 'joined_groups_count', 'user_events', 'guest_events'))->render();
    }

    public function acceptFollowRequest(Request $request)
    {
        $user = User::find($request->user_id);

        $follow_user = $user->updateFollowStatus($request->user_id);

        if ($follow_user) {
            Flash::success(trans('messages.request_accepted'));
        }
        //Notify the user for accepting the follow request
        Notification::create(['user_id' => $request->user_id, 'timeline_id' => $user->timeline_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.accepted_follow_request'), 'type' => 'accept_follow_request', 'link' => Auth::user()->username.'/followers']);

        return response()->json(['status' => '200', 'accepted' => true, 'message' => 'follow request successfully accepted']);
    }

    public function rejectFollowRequest(Request $request)
    {
        $user = User::find($request->user_id);


        $follow_user = $user->decilneRequest($request->user_id);

        if ($follow_user) {
            Flash::success(trans('messages.request_rejected'));
        }

        //Notify the user for rejecting the follow request
        Notification::create(['user_id' => $request->user_id, 'timeline_id' => $user->timeline_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.rejected_follow_request'), 'type' => 'reject_follow_request', 'link' => Auth::user()->username]);

        return response()->json(['status' => '200', 'rejected' => true, 'message' => 'follow request successfully accepted']);
    }

    public function getNotifications()
    {
        $notifications = Notification::where('user_id', Auth::user()->id)->with('notified_from')->latest()->paginate(Setting::get('items_page', 10));

        return response()->json(['status' => '200', 'notifications' => $notifications]);
    }

    public function getUnreadNotifications()
    {
        $notifications = Notification::where('seen', 0)->where('user_id', Auth::user()->id)->count();

        return response()->json(['status' => '200', 'unread_notifications' => $notifications]);
    }

    public function getUsersModal(Request $request)
    {
        $users = User::whereIn('id', explode(',', $request->user_ids))->get();
        $heading = isset($request->heading) ? $request->heading : 'Users';

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $responseHtml = $theme->partial('modal', ['users' => $users, 'heading' => $heading]);

        return response()->json(['status' => '200', 'responseHtml' => $responseHtml]);
    }

    public function getEventGuests($username)
    {
        $timeline = Timeline::where('username', $username)->with('event')->first();
        $event = $timeline->event;
        $event_guests = $event->users();

        if (!$event->is_eventadmin(Auth::user()->id, $event->id)) {
            return redirect($username);
        }

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.invitemembers').' | '.Setting::get('site_title').' | '.Setting::get('site_tagline'));

        return $theme->scope('users/inviteguests', compact('timeline', 'event', 'event_guests'))->render();
    }

    public function getMembersInvite(Request $request)
    {
        $timelines = Timeline::where('username', 'like', "%{$request->searchname}%")->where('type', 'user')->where('username', '!=', Auth::user()->username)->get();
        $event_id = $request->event_id;
        $event = Event::find($request->page_id);

        $users = new \Illuminate\Database\Eloquent\Collection();


        foreach ($timelines as $key => $timeline) {
            $user = $timeline->user()->with(['events' => function ($query) use ($event_id) {
                $query->where('events.id', $event_id);
            }])->get();

            $users->add($user);
        }

        return response()->json(['status' => '200', 'data' => $users]);
    }

    public function addingEventMembers(Request $request)
    {
        $event = Event::find($request->event_id);

        if ($request->user_status == 'Invited') {
            $event->users()->detach([$request->user_id]);

            return response()->json(['status' => '200', 'added' => true, 'message' => 'successfully unjoined']);
        } else {
            $event->users()->attach($request->user_id, ['event_id' => $request->event_id]);

            return response()->json(['status' => '200', 'added' => true, 'message' => 'successfully added']);
        }
    }

    public function movePhotos(Request $request)
    {
        if ($request->album_id == null) {
            Flash::error('Select the album to move the photos');
            return redirect()->back();
        }
        if ($request->photos == null) {
            Flash::error('Select atleast one photo');
            return redirect()->back();
        }
        $photo_ids = explode(",", $request->photos);
        foreach ($photo_ids as $photo) {
            $media = Media::find($photo);
            $media->albums()->detach();
            $media->albums()->attach($request->album_id);
        }
        Flash::success('Selected photos moved successfully');
        return redirect()->back();
    }

    public function deletePhotos(Request $request)
    {
        if ($request->photos == null) {
            Flash::error('Select atleast one photo');
            return redirect()->back();
        }
        $photo_ids = explode(",", $request->photos);
        foreach ($photo_ids as $photo) {
            $media = Media::find($photo);
            $media->albums()->where('preview_id', $media->id)->update(['preview_id' => null]);
            $media->albums()->detach();
            $media->delete();
        }
        Flash::success('Selected photos deleted successfully');
        return redirect()->back();
    }

    public function pages($username)
    {
        $timeline = Timeline::where('username', $username)->with('user')->first();
        if ($timeline == null) {
            return redirect('/');
        }
        if ($timeline->id == Auth::user()->timeline_id) {
            $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
            $theme->setTitle('Pages | '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

            return $theme->scope('users/pages')->render();
        } else {
            return redirect($timeline->username);
        }
    }

    public function groups($username)
    {
        $timeline = Timeline::where('username', $username)->with('user')->first();
        if ($timeline == null) {
            return redirect('/');
        }
        if ($timeline->id == Auth::user()->timeline_id) {
            $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
            $theme->setTitle('Groups | '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

            return $theme->scope('users/groups')->render();
        } else {
            return redirect($timeline->username);
        }
    }

    public function wallpaperSettings($username)
    {
        $wallpapers = Wallpaper::all();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.wallpaper_settings').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/settings/wallpaper', compact('wallpapers'))->render();
    }

    public function savedItems($username)
    {
        $trending_tags = trendingTags();
        $suggested_users = suggestedUsers();
        $suggested_groups = suggestedGroups();
        $suggested_pages = suggestedPages();

        $page_timelines = Auth::user()->timelinesSaved()->where('saved_timelines.type','page')->get();
        $group_timelines = Auth::user()->timelinesSaved()->where('saved_timelines.type','group')->get();
        $event_timelines = Auth::user()->timelinesSaved()->where('saved_timelines.type','event')->get();
        $posts = Auth::user()->postsSaved()->get();
        $user = Auth::user();
        $timeline = Timeline::where('username', $username)->with('user')->first();

        if ($timeline == null) {
            return redirect('/');
        }

        $next_page_url = url('ajax/get-more-posts?page=2&username='.rawurlencode($username));

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.saved_items').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/saved', compact('timeline', 'next_page_url', 'event_timelines','group_timelines','page_timelines','trending_tags','suggested_pages','suggested_groups','suggested_users','posts','user'))->render();
    }


}
