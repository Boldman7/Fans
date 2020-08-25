<?php

namespace App\Http\Controllers;

use Alaouy\Youtube\Facades\Youtube;
use App\Album;
use App\Announcement;
use App\Category;
use App\Comment;
use App\Event;
use App\Group;
use App\Hashtag;
use App\Http\Requests\CreateTimelineRequest;
use App\Http\Requests\UpdateTimelineRequest;
use App\Media;
use App\Notification;
use App\Page;
use App\Post;
use App\Repositories\TimelineRepository;
use App\Role;
use App\Setting;
use App\Subscription;
use App\Timeline;
use App\User;
use App\Wallpaper;
use App\UserList;
use App\UserListType;
use Carbon\Carbon;
use Cassandra\Time;
use DB;
use Dotenv\Exception\ValidationException;
use Flash;
use Flavy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\Facades\Image;
use LinkPreview\LinkPreview;
use mysql_xdevapi\Exception;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Storage;
use Stripe\Stripe;
use Teepluss\Theme\Facades\Theme;
use Validator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;


class TimelineController extends AppBaseController
{
    /** @var TimelineRepository */
    private $timelineRepository;

    public function __construct(TimelineRepository $timelineRepo, Request $request)
    {
        $this->timelineRepository = $timelineRepo;
        $this->watchEventExpires();

        $this->request = $request;
        $this->checkCensored();
    }

    protected function checkCensored()
    {
        $messages['not_contains'] = 'The :attribute must not contain banned words';
        if($this->request->method() == 'POST') {
            // Adjust the rules as needed
            $this->validate($this->request, 
                [
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
                  'username'      => 'not_contains',
                  'username'      => 'not_contains',
                  'email'         => 'email',
                ],$messages);
        }
    }

    public function watchEventExpires()
    {   
        if(Auth::user())
        {
            $events = Event::where('user_id',Auth::user()->id)->get();

            if($events)
            {
                foreach ($events as $event) {
                    if(strtotime($event->end_date) < strtotime('-2 week'))
                    {
                        //Deleting Events
                        
                         $event->users()->detach();
                         $event->pages()->detach();
                            // Deleting event posts
                                $event_posts = $event->timeline()->with('posts')->first();
                        
                                if(count($event_posts->posts) != 0)
                                {
                                    foreach($event_posts->posts as $post)
                                    {
                                        $post->deleteMe();
                                    }
                                }

                                //Deleting event notifications
                                $timeline_alerts = $event->timeline()->with('notifications')->first();

                                if(count($timeline_alerts->notifications) != 0)
                                {
                                    foreach($timeline_alerts->notifications as $notification)
                                    {
                                        $notification->delete();
                                    }
                                }

                            $event_timeline = $event->timeline();
                            $event->delete();
                            $event_timeline->delete();

                    }
                }

            }
        } 
        
    }

    /**
     * Display a listing of the Timeline.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $this->timelineRepository->pushCriteria(new RequestCriteria($request));
        $timelines = $this->timelineRepository->all();

        return view('timelines.index')
            ->with('timelines', $timelines);
    }

    /**
     * Show the form for creating a new Timeline.
     *
     * @return Response
     */
    public function create()
    {
        return view('timelines.create');
    }

    /**
     * Store a newly created Timeline in storage.
     *
     * @param CreateTimelineRequest $request
     *
     * @return Response
     */
    public function store(CreateTimelineRequest $request)
    {
        $input = $request->all();

        $timeline = $this->timelineRepository->create($input);

        Flash::success('Timeline saved successfully.');

        return redirect(route('timelines.index'));
    }

     /**
      * Display the specified Timeline.
      *
      * @param  int $id
      *
      * @return Response
      */
    public function showTimeline($username)
    {
        $admin_role_id = Role::where('name', '=', 'admin')->first();
        $posts = [];
        $timeline = Timeline::where('username', $username)->first();
        $user_post = '';

        if ($timeline == null) {
            return redirect('/');
        }

        $timeline_posts = $timeline->posts()->where('active', 1)->orderBy('created_at', 'desc')->with('comments')->paginate(Setting::get('items_page'));

        foreach ($timeline_posts as $timeline_post) {
            //This is for filtering reported(flag) posts, displaying non flag posts
            if ($timeline_post->check_reports($timeline_post->id) == false) {
                array_push($posts, $timeline_post);
            }
        }

        $next_page_url = url('ajax/get-more-posts?page=2&username='.rawurlencode($username));


        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle($timeline->name.' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        if ($timeline->type == 'user') {
            $follow_user_status = '';
            $timeline_post_privacy = '';
            $timeline_post = '';

            $user = User::where('timeline_id', $timeline['id'])->first();
            $own_pages = $user->own_pages();
            $own_groups = $user->own_groups();
            $liked_pages = $user->pageLikes()->get();
            $joined_groups = $user->groups()->get();
            $joined_groups_count = $user->groups()->where('role_id', '!=', $admin_role_id->id)->where('status', '=', 'approved')->get()->count();
            $following_count = $user->following()->where('status', '=', 'approved')->get()->count();
            $followers_count = $user->followers()->where('status', '=', 'approved')->get()->count();
            $followRequests = $user->followers()->where('status', '=', 'pending')->get();
            $user_events = $user->events()->whereDate('end_date', '>=', date('Y-m-d', strtotime(Carbon::now())))->get();
            $guest_events = $user->getEvents();


            $follow_user_status = DB::table('followers')->where('follower_id', '=', Auth::user()->id)
                               ->where('leader_id', '=', $user->id)->first();

            if ($follow_user_status) {
                $follow_user_status = $follow_user_status->status;
            }

            $confirm_follow_setting = $user->getUserSettings(Auth::user()->id);
            $follow_confirm = $confirm_follow_setting->confirm_follow;

           //get user settings
            $live_user_settings = $user->getUserPrivacySettings(Auth::user()->id, $user->id);
            $privacy_settings = explode('-', $live_user_settings);
            $timeline_post = $privacy_settings[0];
            $user_post = $privacy_settings[1];

            // liked_posts
            $liked_post = DB::table('post_likes')->where('user_id', Auth::user()->id)->get();

            return $theme->scope('users/timeline', compact('user', 'timeline', 'posts', 'liked_post','liked_pages', 'next_page_url', 'joined_groups', 'follow_user_status', 'followRequests', 'following_count', 'followers_count', 'timeline_post', 'user_post', 'follow_confirm', 'joined_groups_count', 'own_pages', 'own_groups', 'user_events', 'guest_events', 'username'))->render();

        }
        elseif ($timeline->type == 'page') {
            $page = Page::where('timeline_id', '=', $timeline->id)->first();
            $page_members = $page->members();
            $user_post = 'page';

            // liked_posts
            $liked_post = DB::table('post_likes')->where('user_id', Auth::user()->id)->get();

            return $theme->scope('users/timeline', compact( 'timeline', 'liked_post', 'posts', 'next_page_url', 'user_post', 'username', 'page_members','page'))->render();

        }
//        elseif ($timeline->type == 'group') {
//            $group = Group::where('timeline_id', '=', $timeline->id)->first();
//            $group_members = $group->members();
//            $group_events = $group->getEvents($group->id);
//            $ongoing_events = $group->getOnGoingEvents($group->id);
//            $upcoming_events = $group->getUpcomingEvents($group->id);
//            $user_post = 'group';
//            if ($ongoing_events == '')$ongoing_events = [];
//            if ($upcoming_events == '')$upcoming_events = [];
//            return $theme->scope('users/timeline', compact( 'timeline', 'posts', 'next_page_url', 'user_post', 'username', 'group', 'group_members', 'group_events', 'ongoing_events', 'upcoming_events'))->render();
//
//        } elseif ($timeline->type == 'event') {
//            $user_post = 'event';
//            $event = Event::where('timeline_id', '=', $timeline->id)->first();
//        }
    }

    public function getMorePosts(Request $request)
    {
        $timeline = Timeline::where('username', $request->username)->first();

        $posts = $timeline->posts()->where('active', 1)->orderBy('created_at', 'desc')->with('comments')->paginate(Setting::get('items_page'));
        $theme = Theme::uses('default')->layout('default');


        $user = User::where('timeline_id', $timeline['id'])->first();
        $isSubscribed = $user->followers()->where('follower_id', Auth::user()->id)->where('leader_id', $user->id)->get()->count() > 1 ? true : false;

        $responseHtml = '';
        foreach ($posts as $post) {
            $responseHtml .= $theme->partial('post', ['isSubscribed' => $isSubscribed, 'post' => $post, 'timeline' => $timeline, 'next_page_url' => $posts->appends(['username' => $request->username])->nextPageUrl()]);
        }

        return $responseHtml;
    }

    public function showFeed(Request $request)
    {
        $mode = "showfeed";
        $user_post = 'showfeed';
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');

        $timeline = Timeline::where('username', Auth::user()->username)->first();

        $id = Auth::id();

        $trending_tags = trendingTags();
        $suggested_users = suggestedUsers();
        $suggested_groups = suggestedGroups();
        $suggested_pages = suggestedPages();

        // Check for hashtag
        if ($request->hashtag) {
            $hashtag = '#'.$request->hashtag;

            $posts = Post::where('description', 'like', "%{$hashtag}%")->where('active', 1)->whereIn('timeline_id', DB::table('followers')->where('follower_id', $id)->pluck('leader_id'))->latest()->paginate(Setting::get('items_page'));
        } // else show the normal feed
        else {
            $posts = Post::whereIn('user_id', function ($query) use ($id) {
                $query->select('leader_id')
                    ->from('followers')
                    ->where('follower_id', $id);
//            })->orWhere('user_id', $id)->where('active', 1)->limit(10);
            })->orWhereIn('id', function ($query1) use ($id) {
                $query1->select('post_id')
                    ->from('pinned_posts')
                    ->where('user_id', $id)
                    ->where('active', 1);
            })->orWhere('user_id', $id)->where('active', 1)->latest()->paginate(Setting::get('items_page'));
        }

        if ($request->ajax) {
            $responseHtml = '';
            foreach ($posts as $post) {
                $responseHtml .= $theme->partial('post', ['post' => $post, 'timeline' => $timeline, 'next_page_url' => $posts->appends(['ajax' => true, 'hashtag' => $request->hashtag])->nextPageUrl()]);
            }

            return $responseHtml;
        }

        $announcement = Announcement::find(Setting::get('announcement'));
        if ($announcement != null) {
            $chk_isExpire = $announcement->chkAnnouncementExpire($announcement->id);

            if ($chk_isExpire == 'notexpired') {
                $active_announcement = $announcement;
                if (!$announcement->users->contains(Auth::user()->id)) {
                    $announcement->users()->attach(Auth::user()->id);
                }
            }
        }

        $next_page_url = url('ajax/get-more-feed?page=2&ajax=true&hashtag='.$request->hashtag.'&username='.Auth::user()->username);
        $theme->setTitle($timeline->name.' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));
//        try{
//            echo $comment->user->avatar;
//        }catch(\Exception $e){
//            echo $e;
//        }

//        echo($posts);

        return $theme->scope('home', compact('timeline', 'posts', 'next_page_url', 'trending_tags', 'suggested_users', 'announcement', 'suggested_groups', 'suggested_pages', 'mode', 'user_post'))
            ->render();
    }

    public function showGlobalFeed(Request $request)
    {
        $mode = 'globalfeed';
        $user_post = 'globalfeed';
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');

        $timeline = Timeline::where('username', Auth::user()->username)->first();

        $id = Auth::id();

        $trending_tags = trendingTags();
        $suggested_users = suggestedUsers();
        $suggested_groups = suggestedGroups();
        $suggested_pages = suggestedPages();

        // Check for hashtag
        if ($request->hashtag) {
            $hashtag = '#'.$request->hashtag;

            $posts = Post::where('description', 'like', "%{$hashtag}%")->where('active', 1)->latest()->paginate(Setting::get('items_page'));
        } // else show the normal feed
        else {
            $posts = Post::orderBy('created_at', 'desc')->where('active', 1)->paginate(Setting::get('items_page'));
        }

        if ($request->ajax) {
            $responseHtml = '';
            foreach ($posts as $post) {
                $responseHtml .= $theme->partial('post', ['post' => $post, 'timeline' => $timeline, 'next_page_url' => $posts->appends(['ajax' => true, 'hashtag' => $request->hashtag])->nextPageUrl()]);
            }

            return $responseHtml;
        }

        $announcement = Announcement::find(Setting::get('announcement'));
        if ($announcement != null) {
            $chk_isExpire = $announcement->chkAnnouncementExpire($announcement->id);

            if ($chk_isExpire == 'notexpired') {
                $active_announcement = $announcement;
                if (!$announcement->users->contains(Auth::user()->id)) {
                    $announcement->users()->attach(Auth::user()->id);
                }
            }
        }

        $next_page_url = url('ajax/get-global-feed?page=2&ajax=true&hashtag='.$request->hashtag.'&username='.Auth::user()->username);

        $theme->setTitle($timeline->name.' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('home', compact('timeline', 'posts', 'next_page_url', 'trending_tags', 'suggested_users', 'announcement', 'suggested_groups', 'suggested_pages', 'mode', 'user_post'))
        ->render();
    }

    public function changeAvatar(Request $request)
    {
        if (Config::get('app.env') == 'demo' && Auth::user()->username == 'bootstrapguru') {
            return response()->json(['status' => '201', 'message' => trans('common.disabled_on_demo')]);
        }
        $timeline = Timeline::where('id', $request->timeline_id)->first();

        if (($request->timeline_type == 'user' && $request->timeline_id == Auth::user()->timeline_id) ||
        ($request->timeline_type == 'page' && $timeline->page->is_admin(Auth::user()->id) == true) ||
        ($request->timeline_type == 'group' && $timeline->groups->is_admin(Auth::user()->id) == true)
        ) {
            if ($request->hasFile('change_avatar')) {
                $timeline_type = $request->timeline_type;

                $change_avatar = $request->file('change_avatar');
                $strippedName = str_replace(' ', '', $change_avatar->getClientOriginalName());
                $photoName = date('Y-m-d-H-i-s').$strippedName;

                // Lets resize the image to the square with dimensions of either width or height , which ever is smaller.
                list($width, $height) = getimagesize($change_avatar->getRealPath());


                $avatar = Image::make($change_avatar->getRealPath());

                if ($width > $height) {
                    $avatar->crop($height, $height);
                } else {
                    $avatar->crop($width, $width);
                }

                $avatar->save(storage_path().'/uploads/'.$timeline_type.'s/avatars/'.$photoName, 60);

                $media = Media::create([
                      'title'  => $photoName,
                      'type'   => 'image',
                      'source' => $photoName,
                    ]);

                $timeline->avatar_id = $media->id;

                if ($timeline->save()) {
                    return response()->json(['status' => '200', 'avatar_url' => url($timeline_type.'/avatar/'.$photoName), 'message' => trans('messages.update_avatar_success')]);
                }
            } else {
                return response()->json(['status' => '201', 'message' => trans('messages.update_avatar_failed')]);
            }
        }
    }

    public function changeCover(Request $request)
    {
        if (Config::get('app.env') == 'demo' && Auth::user()->username == 'bootstrapguru') {
            return response()->json(['status' => '201', 'message' => trans('common.disabled_on_demo')]);
        }
        if ($request->hasFile('change_cover')) {
            $timeline_type = $request->timeline_type;

            $change_avatar = $request->file('change_cover');
            $strippedName = str_replace(' ', '', $change_avatar->getClientOriginalName());
            $photoName = date('Y-m-d-H-i-s').$strippedName;
            $avatar = Image::make($change_avatar->getRealPath());
            $avatar->save(storage_path().'/uploads/'.$timeline_type.'s/covers/'.$photoName, 60);

            $media = Media::create([
              'title'  => $photoName,
              'type'   => 'image',
              'source' => $photoName,
              ]);

            $timeline = Timeline::where('id', $request->timeline_id)->first();
            $timeline->cover_id = $media->id;

            if ($timeline->save()) {
                return response()->json(['status' => '200', 'cover_url' => url($timeline_type.'/cover/'.$photoName), 'message' => trans('messages.update_cover_success')]);
            }
        } else {
            return response()->json(['status' => '201', 'message' => trans('messages.update_cover_failed')]);
        }
    }

    public function createPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_video_upload' => 'max:512000',
        ]);

        if ($validator->fails()) {        
            // return redirect()->back()
            //       ->withInput($request->all())
            //       ->withErrors($validator->errors());
            return response()->json(['status' => '400', 'message' => 'File size is too large. Upload below 100MB']);
        }

        $input = $request->all();
        
        $input['user_id'] = Auth::user()->id;
        
        $post = Post::create($input);
        $post->notifications_user()->sync([Auth::user()->id], true);

//        if ($request->hasFile('post_video_upload')) {
//            $uploadedFile = $request->file('post_video_upload');
//
//
//            $s3 = Storage::disk('uploads');
//
//            $timestamp = date('Y-m-d-H-i-s');
//
//            $strippedName = $timestamp.str_replace(' ', '', $uploadedFile->getClientOriginalName());
//
//            $s3->put('users/gallery/'.$strippedName, file_get_contents($uploadedFile));
//
//            $basename = $timestamp.basename($request->file('post_video_upload')->getClientOriginalName(), '.'.$request->file('post_video_upload')->getClientOriginalExtension());
//
//            //Flavy::thumbnail(storage_path().'/uploads/users/gallery/'.$strippedName, storage_path().'/uploads/users/gallery/'.$basename.'.jpg', 1); //returns array with file info
//
//            $media = Media::create([
//                'title'  => $basename,
//                'type'   => 'video',
//                'source' => $strippedName,
//            ]);
//
//            $post->images()->attach($media);
//        }
//
        if ($request->file('post_images_upload_modified')) {
            foreach ($request->file('post_images_upload_modified') as $postImage) {
                if ($postImage->getSize() > 524288000) {
                    return response()->json(['status' => '400', 'message' => 'File size is too large. Upload below 100MB']);
                }
                if ($postImage->getClientOriginalExtension() != 'mp4') {
                    $strippedName = str_replace(' ', '', $postImage->getClientOriginalName());
                    $photoName = date('Y-m-d-H-i-s') . $strippedName;

                    try {
                        $avatar = Image::make($postImage->getRealPath());
                        $avatar->save(storage_path() . '/uploads/users/gallery/' . $photoName, 60);

                    } catch (NotReadableException $e) {
                        return redirect('/');
                    }
                    $media = Media::create([
                        'title' => $photoName,
                        'type' => 'image',
                        'source' => $photoName,
                    ]);

                    $post->images()->attach($media);
                }
                else {
                    $strippedName = str_replace(' ', '', $postImage->getClientOriginalName());
                    $photoName = date('Y-m-d-H-i-s') . $strippedName;

                    $s3 = Storage::disk('uploads');

                    $timestamp = date('Y-m-d-H-i-s');

                    $strippedName = $timestamp.str_replace(' ', '', $postImage->getClientOriginalName());

                    $s3->put('users/gallery/'.$strippedName, file_get_contents($postImage));

                    $basename = $timestamp.basename($request->file('post_video_upload')->getClientOriginalName(), '.'.$request->file('post_video_upload')->getClientOriginalExtension());

                    $media = Media::create([
                        'title'  => $basename,
                        'type'   => 'video',
                        'source' => $strippedName,
                    ]);

                    $post->images()->attach($media);
                }
            }
        }

        if ($post) {
            // Check for any mentions and notify them
            preg_match_all('/(^|\s)(@\w+)/', $request->description, $usernames);
            foreach ($usernames[2] as $value) {
                $timeline = Timeline::where('username', str_replace('@', '', $value))->first();
                $notification = Notification::create(['user_id' => $timeline->user->id, 'post_id' => $post->id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.mentioned_you_in_post'), 'type' => 'mention', 'link' => 'post/'.$post->id]);
            }
            $timeline = Timeline::where('id', $request->timeline_id)->first();

            //Notify the user when someone posts on his timeline/page/group

            if ($timeline->type == 'page') {
                $notify_users = $timeline->page->users()->whereNotIn('user_id', [Auth::user()->id])->get();
                $notify_message = 'posted on this page';
            } elseif ($timeline->type == 'group') {
                $notify_users = $timeline->groups->users()->whereNotIn('user_id', [Auth::user()->id])->get();
                $notify_message = 'posted on this group';
            } else {
                $notify_users = $timeline->user()->whereNotIn('id', [Auth::user()->id])->get();
                $notify_message = 'posted on your timeline';
            }

            foreach ($notify_users as $notify_user) {
                Notification::create(['user_id' => $notify_user->id, 'timeline_id' => $request->timeline_id, 'post_id' => $post->id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.$notify_message, 'type' => $timeline->type, 'link' => $timeline->username]);
            }


            // Check for any hashtags and save them
            preg_match_all('/(^|\s)(#\w+)/', $request->description, $hashtags);
            foreach ($hashtags[2] as $value) {
                $timeline = Timeline::where('username', str_replace('@', '', $value))->first();
                $hashtag = Hashtag::where('tag', str_replace('#', '', $value))->first();
                if ($hashtag) {
                    $hashtag->count = $hashtag->count + 1;
                    $hashtag->save();
                } else {
                    Hashtag::create(['tag' => str_replace('#', '', $value), 'count' => 1]);
                }
            }

            // Let us tag the post friends :)
            if ($request->user_tags != null) {
                $post->users_tagged()->sync(explode(',', $request->user_tags));
            }
        }

        // $post->users_tagged = $post->users_tagged();
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('ajax');
        $postHtml = $theme->scope('timeline/post', compact('post', 'timeline'))->render();
        
        return response()->json(['status' => '200', 'data' => $postHtml]);
    }

    public function editPost(Request $request)
    {
        $post = Post::where('id', $request->post_id)->with('user')->first();
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('ajax');
        $postHtml = $theme->partial('edit-post', compact('post'));

        return response()->json(['status' => '200', 'data' => $postHtml]);
    }

    public function loadEmoji()
    {
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('ajax');
        $postHtml = $theme->partial('emoji');

        return response()->json(['status' => '200', 'data' => $postHtml]);
    }

    public function updatePost(Request $request)
    {
        $post = Post::where('id', $request->post_id)->first();
        if ($post->user->id == Auth::user()->id) {
            $post->description = $request->description;
            $post->save();
        }

        return redirect()->back();
        // return redirect('post/'.$post->id);
    }

    public function getSoundCloudResults(Request $request)
    {
        $soundcloudJson = file_get_contents('http://api.soundcloud.com/tracks.json?client_id='.env('SOUNDCLOUD_CLIENT_ID').'&q='.$request->q);

        return response()->json(['status' => '200', 'data' => $soundcloudJson]);
    }

    public function postComment(Request $request)
    {
        $comment = Comment::create([
                    'post_id'     => $request->post_id,
                    'description' => $request->description,
                    'user_id'     => Auth::user()->id,
                    'parent_id'   => $request->comment_id,
                  ]);

        $post = Post::where('id', $request->post_id)->first();
        $posted_user = $post->user;

        if ($comment) {
            if (Auth::user()->id != $post->user_id) {
                //Notify the user for comment on his/her post
                Notification::create(['user_id' => $post->user_id, 'post_id' => $request->post_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.commented_on_your_post'), 'type' => 'comment_post']);
            }

            $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('ajax');
            if ($request->comment_id) {
                $reply = $comment;
                $main_comment = Comment::find($reply->parent_id);
                $main_comment_user = $main_comment->user;

                $user = User::find(Auth::user()->id);
                $user_settings = $user->getUserSettings($main_comment_user->id);
                if ($user_settings && $user_settings->email_reply_comment == 'yes') {
                    Mail::send('emails.commentreply_mail', ['user' => $user, 'main_comment_user' => $main_comment_user], function ($m) use ($user, $main_comment_user) {
                        $m->from(Setting::get('noreply_email'), Setting::get('site_name'));
                        $m->to($main_comment_user->email, $main_comment_user->name)->subject('New reply to your comment');
                    });
                }
                $postHtml = $theme->scope('timeline/reply', compact('reply', 'post'))->render();
            } else {
                $user = User::find(Auth::user()->id);
                $user_settings = $user->getUserSettings($posted_user->id);
                if ($user_settings && $user_settings->email_comment_post == 'yes') {
                    Mail::send('emails.commentmail', ['user' => $user, 'posted_user' => $posted_user], function ($m) use ($user, $posted_user) {
                        $m->from(Setting::get('noreply_email'), Setting::get('site_name'));
                        $m->to($posted_user->email, $posted_user->name)->subject('New comment to your post');
                    });
                }

                $postHtml = $theme->scope('timeline/comment', compact('comment', 'post'))->render();
            }
        }

        return response()->json(['status' => '200', 'comment_id' => $comment->id, 'data' => $postHtml]);
    }

    public function likePost(Request $request)
    {
        $post = Post::findOrFail($request->post_id);
        $posted_user = $post->user;
        $like_count = $post->users_liked()->count();

        //Like the post
        if (!$post->users_liked->contains(Auth::user()->id)) {
            $post->users_liked()->attach(Auth::user()->id, ['created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
            $post->notifications_user()->attach(Auth::user()->id);

            $user = User::find(Auth::user()->id);
            $user_settings = $user->getUserSettings($posted_user->id);
            if ($user_settings && $user_settings->email_like_post == 'yes') {
                Mail::send('emails.postlikemail', ['user' => $user, 'posted_user' => $posted_user], function ($m) use ($posted_user, $user) {
                    $m->from(Setting::get('noreply_email'), Setting::get('site_name'));
                    $m->to($posted_user->email, $posted_user->name)->subject($user->name.' '.'liked your post');
                });
            }
            //Notify the user for post like
            $notify_message = 'liked your post';
            $notify_type = 'like_post';
            $status_message = 'successfully liked';

            if ($post->user->id != Auth::user()->id) {
                Notification::create(['user_id' => $post->user->id, 'post_id' => $post->id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.$notify_message, 'type' => $notify_type]);
            }

            $liked_post = \Illuminate\Support\Facades\DB::table('post_likes')->where('user_id', \Illuminate\Support\Facades\Auth::user()->id)->get();
            $liked_post_count = $liked_post != NULL ? count($liked_post) : 0;
            return response()->json(['status' => '200', 'liked' => 'true', 'message' => $status_message, 'likecount' => $like_count, 'post_likes' => $liked_post_count]);
        } //Unlike the post
        else {
            $post->users_liked()->detach([Auth::user()->id]);
            $post->notifications_user()->detach([Auth::user()->id]);

            //Notify the user for post unlike
            $notify_message = 'unliked your post';
            $notify_type = 'unlike_post';
            $status_message = 'successfully unliked';

            if ($post->user->id != Auth::user()->id) {
                Notification::create(['user_id' => $post->user->id, 'post_id' => $post->id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.$notify_message, 'type' => $notify_type]);
            }

            $liked_post = \Illuminate\Support\Facades\DB::table('post_likes')->where('user_id', \Illuminate\Support\Facades\Auth::user()->id)->get();
            $liked_post_count = $liked_post != NULL ? count($liked_post) : 0;
            return response()->json(['status' => '200', 'liked' => 'false', 'message' => $status_message, 'likecount' => $like_count, 'post_likes' => $liked_post_count]);
        }

        if ($post) {
            $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('ajax');
            $postHtml = $theme->scope('timeline/post', compact('post'))->render();
        }

        return response()->json(['status' => '200', 'data' => $postHtml]);
    }

    public function likeComment(Request $request)
    {
        $comment = Comment::findOrFail($request->comment_id);
        $comment_user = $comment->user;

        if (!$comment->comments_liked->contains(Auth::user()->id)) {
            $comment->comments_liked()->attach(Auth::user()->id);
            $comment_likes = $comment->comments_liked()->get();
            $like_count = $comment_likes->count();

            //sending email notification
            $user = User::find(Auth::user()->id);
            $user_settings = $user->getUserSettings($comment_user->id);
            if ($user_settings && $user_settings->email_like_comment == 'yes') {
                Mail::send('emails.commentlikemail', ['user' => $user, 'comment_user' => $comment_user], function ($m) use ($user, $comment_user) {
                    $m->from(Setting::get('noreply_email'), Setting::get('site_name'));
                    $m->to($comment_user->email, $comment_user->name)->subject($user->name.' '.'likes your comment');
                });
            }

            //Notify the user for comment like
            if ($comment->user->id != Auth::user()->id) {
                Notification::create(['user_id' => $comment->user_id, 'post_id' => $comment->post_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.liked_your_comment'), 'type' => 'like_comment']);
            }

            return response()->json(['status' => '200', 'liked' => true, 'message' => 'successfully liked', 'likecount' => $like_count]);
        } else {
            $comment->comments_liked()->detach([Auth::user()->id]);
            $comment_likes = $comment->comments_liked()->get();
            $like_count = $comment_likes->count();

            //Notify the user for comment unlike
            if ($comment->user->id != Auth::user()->id) {
                Notification::create(['user_id' => $comment->user_id, 'post_id' => $comment->post_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.unliked_your_comment'), 'type' => 'unlike_comment']);
            }

            return response()->json(['status' => '200', 'unliked' => false, 'message' => 'successfully unliked', 'likecount' => $like_count]);
        }
    }

    public function sharePost(Request $request)
    {
        $post = Post::findOrFail($request->post_id);
        $posted_user = $post->user;


        if (!$post->users_shared->contains(Auth::user()->id)) {
            $post->users_shared()->attach(Auth::user()->id, ['created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
            $post_share_count = $post->users_shared()->get()->count();
            // we need to insert the shared post into the timeline of the person who shared
            $input['user_id'] = Auth::user()->id;
            $post = Post::create([
                'timeline_id' => Auth::user()->timeline->id,
                'user_id' => Auth::user()->id,
                'shared_post_id' => $request->post_id,
            ]);


            if ($post->user_id != Auth::user()->id) {
                //Notify the user for post share
                Notification::create(['user_id' => $post->user_id, 'post_id' => $request->post_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.shared_your_post'), 'type' => 'share_post', 'link' => '/'.Auth::user()->username]);

                $user = User::find(Auth::user()->id);
                $user_settings = $user->getUserSettings($posted_user->id);

                if ($user_settings && $user_settings->email_post_share == 'yes') {
                    Mail::send('emails.postsharemail', ['user' => $user, 'posted_user' => $posted_user], function ($m) use ($user, $posted_user) {
                        $m->from(Setting::get('noreply_email'), Setting::get('site_name'));
                        $m->to($posted_user->email, $posted_user->name)->subject($user->name.' '.'shared your post');
                    });
                }
            }

            return response()->json(['status' => '200', 'shared' => true, 'message' => 'successfully shared', 'share_count' => $post_share_count]);
        } else {
            $post->users_shared()->detach([Auth::user()->id]);
            $post_share_count = $post->users_shared()->get()->count();

            $sharedPost = Post::where('shared_post_id', $post->id)->delete();

            if ($post->user_id != Auth::user()->id) {
                //Notify the user for post share
                Notification::create(['user_id' => $post->user_id, 'post_id' => $request->post_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.unshared_your_post'), 'type' => 'unshare_post', 'link' => '/'.Auth::user()->username]);
            }

            return response()->json(['status' => '200', 'unshared' => false, 'message' => 'Successfully unshared', 'share_count' => $post_share_count]);
        }
    }

    public function pageLiked(Request $request)
    {
        $page = Page::where('timeline_id', '=', $request->timeline_id)->first();

        if ($page->likes->contains(Auth::user()->id)) {
            $page->likes()->detach([Auth::user()->id]);

            return response()->json(['status' => '200', 'like' => true, 'message' => 'successfully unliked']);
        }
    }

    public function pageReport(Request $request)
    {
        $timeline = Timeline::where('id', '=', $request->timeline_id)->first();

        if ($timeline->type == 'page') {
            $admins = $timeline->page->admins();
            $report_type = 'page_report';
        }
        if ($timeline->type == 'group') {
            $admins = $timeline->groups->admins();
            $report_type = 'group_report';
        }


        if (!$timeline->reports->contains(Auth::user()->id)) {
            $timeline->reports()->attach(Auth::user()->id, ['status' => 'pending']);

            if ($timeline->type == 'user') {
                Notification::create(['user_id' => $timeline->user->id, 'timeline_id' => $timeline->id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.reported_you'), 'type' => 'user_report']);
            } else {
                foreach ($admins as $admin) {
                    Notification::create(['user_id' => $admin->id, 'timeline_id' => $timeline->id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' reported your '.$timeline->type, 'type' => $report_type]);
                }
            }


            return response()->json(['status' => '200', 'reported' => true, 'message' => 'successfully reported']);
        } else {
            $timeline->reports()->detach([Auth::user()->id]);

            if ($timeline->type == 'user') {
                Notification::create(['user_id' => $timeline->user->id, 'timeline_id' => $timeline->id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.unreported_you'), 'type' => 'user_report']);
            } else {
                foreach ($admins as $admin) {
                    Notification::create(['user_id' => $admin->id, 'timeline_id' => $timeline->id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.unreported_your_page'), 'type' => 'page_report']);
                }
            }

            return response()->json(['status' => '200', 'reported' => false, 'message' => 'successfully unreport']);
        }
    }

    public function timelineGroups(Request $request)
    {
        $group = Group::where('timeline_id', '=', $request->timeline_id)->first();

        if ($group->users->contains(Auth::user()->id)) {
            $group->users()->detach([Auth::user()->id]);

            return response()->json(['status' => '200', 'join' => true, 'message' => 'successfully unjoined']);
        }
    }

    public function getYoutubeVideo(Request $request)
    {
        $videoId = Youtube::parseVidFromURL($request->youtube_source);

        $video = Youtube::getVideoInfo($videoId);

        $videoData = [
                        'id'     => $video->id,
                        'title'  => $video->snippet->title,
                        'iframe' => $video->player->embedHtml,
                      ];

        return response()->json(['status' => '200', 'message' => $videoData]);
    }

    public function show($id)
    {
        $timeline = $this->timelineRepository->findWithoutFail($id);

        if (empty($timeline)) {
            Flash::error('Timeline not found');

            return redirect(route('timelines.index'));
        }

        return view('timelines.show')->with('timeline', $timeline);
    }

    /**
     * Show the form for editing the specified Timeline.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $timeline = $this->timelineRepository->findWithoutFail($id);

        if (empty($timeline)) {
            Flash::error('Timeline not found');

            return redirect(route('timelines.index'));
        }

        return view('timelines.edit')->with('timeline', $timeline);
    }

    /**
     * Update the specified Timeline in storage.
     *
     * @param int                   $id
     * @param UpdateTimelineRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTimelineRequest $request)
    {
        $timeline = $this->timelineRepository->findWithoutFail($id);

        if (empty($timeline)) {
            Flash::error('Timeline not found');

            return redirect(route('timelines.index'));
        }

        $timeline = $this->timelineRepository->update($request->all(), $id);

        Flash::success('Timeline updated successfully.');

        return redirect(route('timelines.index'));
    }

    /**
     * Remove the specified Timeline from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $timeline = $this->timelineRepository->findWithoutFail($id);

        if (empty($timeline)) {
            Flash::error('Timeline not found');

            return redirect(route('timelines.index'));
        }

        $this->timelineRepository->delete($id);

        Flash::success('Timeline deleted successfully.');

        return redirect(route('timelines.index'));
    }

    public function follow(Request $request)
    {
        $timeline_id = $request->timeline_id;
        $follow = User::where('timeline_id', '=', $timeline_id)->first();
        $timeline = Timeline::where('id', $timeline_id)->first();

        if (!$follow->followers->contains(Auth::user()->id)) {
            $follow->followers()->attach(Auth::user()->id, ['status' => 'approved']);

            $user = User::find(Auth::user()->id);

            try{
                //Notify the user for follow
                Notification::create(['user_id' => $follow->id, 'timeline_id' => $timeline_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.is_following_you'), 'type' => 'follow']);
            }catch(\Exception $e){
            }

//            return response()->json(['status' => '200', 'followed' => true, 'message' => 'successfully followed']);
            return redirect($timeline->username);
        } else {
            $follow->followers()->detach([Auth::user()->id]);

            try{
                //Notify the user for follow
                Notification::create(['user_id' => $follow->id, 'timeline_id' => $timeline_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.is_unfollowing_you'), 'type' => 'unfollow']);
            }catch(\Exception $e){
            }

//            return response()->json(['status' => '200', 'followed' => false, 'message' => 'successfully unFollowed']);
            return redirect()->route($timeline->username);
        }
    }


    public function followFreeUser(Request $request)
    {
        $timeline_id = $request->timeline_id;
        $follow = User::where('timeline_id', '=', $timeline_id)->first();
        $timeline = Timeline::where('id', $timeline_id)->first();

        if (!$follow->followers->contains(Auth::user()->id)) {
            $follow->followers()->attach(Auth::user()->id, ['status' => 'approved']);

            $user = User::find(Auth::user()->id);

            try{
                //Notify the user for follow
                Notification::create(['user_id' => $follow->id, 'timeline_id' => $timeline_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.is_following_you'), 'type' => 'follow']);
            }catch(\Exception $e){
            }

            return response()->json(['status' => '200', 'followed' => true, 'message' => 'successfully followed']);
        } else {
            $follow->followers()->detach([Auth::user()->id]);

            try{
                //Notify the user for follow
                Notification::create(['user_id' => $follow->id, 'timeline_id' => $timeline_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.is_unfollowing_you'), 'type' => 'unfollow']);
            }catch(\Exception $e){
            }

            return response()->json(['status' => '200', 'followed' => false, 'message' => 'successfully unFollowed']);
        }
    }


    public function unfollow(Request $request)
    {

        $subscription = Subscription::where('follower_id', Auth::user()->id)->where('leader_id', $request->timeline_id)->where('cancel_at', NULL)->first();
        session(['previous_url' => redirect()->back()->getTargetUrl()]);
        app('App\Http\Controllers\CheckoutController')->deleteSubscription($subscription);

    }

    public function unfollowFreeUser(Request $request) {
        $follow = User::where('timeline_id', '=', $request->timeline_id)->first();
        $timeline = Timeline::where('id', $request->timeline_id)->first();

        if (!$follow->followers->contains(Auth::user()->id)) {
            $follow->followers()->attach(Auth::user()->id, ['status' => 'approved']);

            $user = User::find(Auth::user()->id);

            try{
                //Notify the user for follow
                Notification::create(['user_id' => $follow->id, 'timeline_id' => $request->timeline_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.is_following_you'), 'type' => 'follow']);
            }catch(\Exception $e){
            }

            return response()->json(['status' => '200', 'followed' => true, 'message' => 'successfully followed']);
        } else {
            $follow->followers()->detach([Auth::user()->id]);

            try{
                //Notify the user for follow
                Notification::create(['user_id' => $follow->id, 'timeline_id' => $request->timeline_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.is_unfollowing_you'), 'type' => 'unfollow']);
            }catch(\Exception $e){
            }

            return response()->json(['status' => '200', 'followed' => false, 'message' => 'successfully unFollowed']);
        }
    }

    public function userFollowRequest(Request $request)
    {
        $user = User::where('timeline_id', '=', $request->timeline_id)->first();

        if (!$user->followers->contains(Auth::user()->id)) {
            $user->followers()->attach(Auth::user()->id, ['status' => 'pending']);

            //Notify the user for page like
            Notification::create(['user_id' => $user->id, 'timeline_id' => Auth::user()->timeline_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.request_follow'), 'type' => 'follow_requested']);

            return response()->json(['status' => '200', 'followrequest' => true, 'message' => 'successfully sent user follow request']);
        } else {
            if ($request->follow_status == 'approved') {
                $user->followers()->detach([Auth::user()->id]);

                return response()->json(['status' => '200', 'unfollow' => true, 'message' => 'unfollowed successfully']);
            } else {
                $user->followers()->detach([Auth::user()->id]);

                return response()->json(['status' => '200', 'followrequest' => false, 'message' => 'unsuccessfully request']);
            }
        }
    }

    public function getNotifications(Request $request)
    {
        $post = Post::findOrFail($request->post_id);

        if (!$post->notifications_user->contains(Auth::user()->id)) {
            $post->notifications_user()->attach(Auth::user()->id);

            return response()->json(['status' => '200', 'notified' => true, 'message' => 'Successfull']);
        } else {
            $post->notifications_user()->detach([Auth::user()->id]);

            return response()->json(['status' => '200', 'unnotify' => false, 'message' => 'UnSuccessfull']);
        }
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name'     => 'required|max:30|min:5',
            'category' => 'required',
            'username' => 'required|max:26|min:5|alpha_num|unique:timelines|no_admin'
        ];

        $messages = [
            'no_admin' => 'The name admin is restricted for :attribute'
        ];

        return Validator::make($data, $rules, $messages);
    }

    
    public function publicPosts($username)
    {

        $admin_role_id = Role::where('name', '=', 'admin')->first();
        $timeline = Timeline::where('username', $username)->first();
        $user = User::where('timeline_id', $timeline['id'])->first();
        $posts = $timeline->posts()->where('active', 1)->orderBy('created_at', 'desc')->with('comments')->paginate(Setting::get('items_page'));


        if ($timeline->type == 'user') {
            $follow_user_status = '';
            $user = User::where('timeline_id', $timeline['id'])->first();
            $followRequests = $user->followers()->where('status', '=', 'pending')->get();
            $liked_pages = $user->pageLikes()->get();
            $joined_groups = $user->groups()->get();
            $own_pages = $user->own_pages();
            $own_groups = $user->own_groups();
            $following_count = $user->following()->where('status', '=', 'approved')->get()->count();
            $followers_count = $user->followers()->where('status', '=', 'approved')->get()->count();
            $joined_groups_count = $user->groups()->where('role_id', '!=', $admin_role_id->id)->where('status', '=', 'approved')->get()->count();
            $follow_user_status = DB::table('followers')->where('follower_id', '=', $user->id)
                ->where('leader_id', '=', $user->id)->first();
            $user_events = $user->events()->whereDate('end_date', '>=', date('Y-m-d', strtotime(Carbon::now())))->get();
            $guest_events = $user->getEvents();


            if ($follow_user_status) {
                $follow_user_status = $follow_user_status->status;
            }

            $confirm_follow_setting = $user->getUserSettings($user->id);
            $follow_confirm = $confirm_follow_setting->confirm_follow;

            $live_user_settings = $user->getUserPrivacySettings($user->id, $user->id);
            $privacy_settings = explode('-', $live_user_settings);
            $timeline_post = $privacy_settings[0];
            $user_post = $privacy_settings[1];
        } else {
            $user = User::where('id', $user->id)->first();
        }

        $next_page_url = url('ajax/get-more-posts?page=2&username='.rawurlencode($username));

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('public');
        $theme->setTitle(trans('common.posts').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        // liked_posts
        $liked_post = \Illuminate\Support\Facades\DB::table('post_likes')->where('user_id', $user->id)->get();

        return $theme->scope('timeline/public-posts', compact('timeline', 'liked_post', 'user', 'posts', 'liked_pages', 'followRequests', 'joined_groups', 'own_pages', 'own_groups', 'follow_user_status', 'following_count', 'followers_count', 'follow_confirm', 'user_post', 'timeline_post', 'joined_groups_count', 'next_page_url', 'user_events', 'guest_events'))->render();
    }

    public function posts($username)
    {
        
        if (!Auth::check()) {
            $admin_role_id = Role::where('name', '=', 'admin')->first();
            $timeline = Timeline::where('username', $username)->first();
            $user = User::where('timeline_id', $timeline['id'])->first();
//            $posts = $timeline->posts()->where('active', 1)->orderBy('created_at', 'desc')->with('comments')->paginate(Setting::get('items_page'));

            $id = $user->id;

//            if (Auth::user()->id == $id) {
//                $posts = Post::WhereIn('id', function ($query1) use ($id) {
//                    $query1->select('post_id')
//                        ->from('pinned_posts')
//                        ->where('user_id', $id)
//                        ->where('active', 1);
//                })->orWhere('user_id', $id)->where('active', 1)->latest()->paginate(Setting::get('items_page'));
//            } else {
                $posts = Post::Where('user_id', $id)->where('active', 1)->latest()->paginate(Setting::get('items_page'));
//            }

//            $user_lists = UserListType::where(['user_id' => Auth::user()->id])->with('lists')->get();
//
//            if (!empty($user_lists)) {
//
//                foreach ($user_lists as $user_list) {
//                    if (UserList::where(['list_type_id' => $user_list->id, 'saved_user_id' => $id])->get()->isEmpty()) {
//                        $user_list->state = 0;
//                    } else {
//                        $user_list->state = 1;
//                    }
//                }
//            }

            if ($timeline->type == 'user') {
                $follow_user_status = '';
                $user = User::where('timeline_id', $timeline['id'])->first();
                $followRequests = $user->followers()->where('status', '=', 'pending')->get();
                $liked_pages = $user->pageLikes()->get();
                $joined_groups = $user->groups()->get();
                $own_pages = $user->own_pages();
                $own_groups = $user->own_groups();
                $following_count = $user->following()->where('status', '=', 'approved')->get()->count();
                $followers_count = $user->followers()->where('status', '=', 'approved')->get()->count();
                $joined_groups_count = $user->groups()->where('role_id', '!=', $admin_role_id->id)->where('status', '=', 'approved')->get()->count();
                $follow_user_status = DB::table('followers')->where('follower_id', '=', $user->id)
                    ->where('leader_id', '=', $user->id)->first();
                $user_events = $user->events()->whereDate('end_date', '>=', date('Y-m-d', strtotime(Carbon::now())))->get();
                $guest_events = $user->getEvents();
    
    
                if ($follow_user_status) {
                    $follow_user_status = $follow_user_status->status;
                }
    
                $confirm_follow_setting = $user->getUserSettings($user->id);
                $follow_confirm = $confirm_follow_setting->confirm_follow;
    
                $live_user_settings = $user->getUserPrivacySettings($user->id, $user->id);
                $privacy_settings = explode('-', $live_user_settings);
                $timeline_post = $privacy_settings[0];
                $user_post = $privacy_settings[1];
            } else {
                $user = User::where('id', $user->id)->first();
            }
    
            $next_page_url = url('ajax/get-more-posts?page=2&username='.rawurlencode($username));
    
            $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('public');
            $theme->setTitle(trans('common.posts').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));
    
            // liked_posts
            $liked_post = \Illuminate\Support\Facades\DB::table('post_likes')->where('user_id', $user->id)->get();
    
            return $theme->scope('timeline/public-posts', compact('timeline', 'liked_post', 'user', 'posts', 'liked_pages', 'followRequests', 'joined_groups', 'own_pages', 'own_groups', 'follow_user_status', 'following_count', 'followers_count', 'follow_confirm', 'user_post', 'timeline_post', 'joined_groups_count', 'next_page_url', 'user_events', 'guest_events'))->render();

        }
        else {
        
            $admin_role_id = Role::where('name', '=', 'admin')->first();
            $timeline = Timeline::where('username', $username)->first();
            
            if ($timeline == NULL) {
                abort(404);
            }

            $user = User::where('timeline_id', $timeline['id'])->first();
//            $posts = $timeline->posts()->where('active', 1)->orderBy('created_at', 'desc')->with('comments')->paginate(Setting::get('items_page'));

            $id = $user->id;
//            $posts = $timeline->posts()->where('active', 1)->orderBy('created_at', 'desc')->with('comments')->paginate(Setting::get('items_page'));

            if (Auth::user()->id == $id) {
                $posts = (Post::WhereIn('id', function ($query1) use ($id) {
                    $query1->select('post_id')
                        ->from('pinned_posts')
                        ->where('user_id', $id)
                        ->where('active', 1);
                })->orWhere('user_id', $id)->where('active', 1))->paginate(Setting::get('items_page'));
            } else {
                $posts = Post::Where('user_id', $id)->where('active', 1)->latest()->paginate(Setting::get('items_page'));
            }
            $user_lists = UserListType::where(['user_id' => Auth::user()->id])->with('lists')->get();

            if (!empty($user_lists)) {

                foreach ($user_lists as $user_list) {
                    if (UserList::where(['list_type_id' => $user_list->id, 'saved_user_id' => $id])->get()->isEmpty()) {
                        $user_list->state = 0;
                    } else {
                        $user_list->state = 1;
                    }
                }
            }

            if ($timeline->type == 'user') {
                $follow_user_status = '';
                $user = User::where('timeline_id', $timeline['id'])->first();
                $followRequests = $user->followers()->where('status', '=', 'pending')->get();
                $liked_pages = $user->pageLikes()->get();
                $joined_groups = $user->groups()->get();
                $own_pages = $user->own_pages();
                $own_groups = $user->own_groups();
                $following_count = $user->following()->where('status', '=', 'approved')->get()->count();
                $followers_count = $user->followers()->where('status', '=', 'approved')->get()->count();
                $joined_groups_count = $user->groups()->where('role_id', '!=', $admin_role_id->id)->where('status', '=', 'approved')->get()->count();
                $follow_user_status = DB::table('followers')->where('follower_id', '=', Auth::user()->id)
                                    ->where('leader_id', '=', $user->id)->first();
                $user_events = $user->events()->whereDate('end_date', '>=', date('Y-m-d', strtotime(Carbon::now())))->get();
                $guest_events = $user->getEvents();
    
    
                if ($follow_user_status) {
                    $follow_user_status = $follow_user_status->status;
                }
    
                $confirm_follow_setting = $user->getUserSettings(Auth::user()->id);
                $follow_confirm = $confirm_follow_setting->confirm_follow;
    
                $live_user_settings = $user->getUserPrivacySettings(Auth::user()->id, $user->id);
                $privacy_settings = explode('-', $live_user_settings);
                $timeline_post = $privacy_settings[0];
                $user_post = $privacy_settings[1];
            } else {
                $user = User::where('id', Auth::user()->id)->first();
            }
    
            $next_page_url = url('ajax/get-more-posts?page=2&username='.rawurlencode($username));
    
            $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
            $theme->setTitle(trans('common.posts').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));
    
            // liked_posts
            $liked_post = \Illuminate\Support\Facades\DB::table('post_likes')->where('user_id', \Illuminate\Support\Facades\Auth::user()->id)->get();
    
            return $theme->scope('timeline/posts', compact('timeline', 'liked_post', 'user', 'posts', 'liked_pages', 'followRequests', 'joined_groups', 'own_pages', 'own_groups', 'follow_user_status', 'following_count', 'followers_count', 'follow_confirm', 'user_post', 'timeline_post', 'joined_groups_count', 'next_page_url', 'user_events', 'guest_events', 'user_lists'))->render();
        
        }
    }
    
    

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function groupPageValidator(array $data)
    {
        $rules = [
            'name'     => 'required',
            'username' => 'required|max:16|min:5|alpha_num|unique:timelines|no_admin'
        ];
        
        $messages = [
            'no_admin' => 'The name admin is restricted for :attribute'
        ];

        return Validator::make($data, $rules, $messages);
    }


    public function generalPageSettings($username)
    {
        $timeline = Timeline::where('username', $username)->with('page')->first();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.general_settings').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('page/settings/general', compact('timeline', 'username'))->render();
    }

    public function updateGeneralPageSettings(Request $request)
    {
        $validator = $this->groupPageSettingsValidator($request->all());

        if ($validator->fails()) {
            return redirect()->back()
                  ->withInput($request->all())
                  ->withErrors($validator->errors());
        }
        $timeline = Timeline::where('username', $request->username)->first();
        $timeline_values = $request->only('username', 'name', 'about');
        $update_timeline = $timeline->update($timeline_values);

        $page = Page::where('timeline_id', $timeline->id)->first();
        $page_values = $request->only('address', 'phone', 'website');
        $update_page = $page->update($page_values);


        Flash::success(trans('messages.update_Settings_success'));

        return redirect()->back();
    }

    public function privacyPageSettings($username)
    {
        $timeline = Timeline::where('username', $username)->first();
        $page_details = $timeline->page()->first();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.privacy_settings').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('page/settings/privacy', compact('timeline', 'username', 'page_details'))->render();
    }

    public function updatePrivacyPageSettings(Request $request)
    {
        $timeline = Timeline::where('username', $request->username)->first();
        $page = Page::where('timeline_id', $timeline->id)->first();
        $page->timeline_post_privacy = $request->timeline_post_privacy;
        $page->member_privacy = $request->member_privacy;
        $page->save();

        Flash::success(trans('messages.update_privacy_success'));

        return redirect()->back();
    }

    public function rolesPageSettings($username)
    {
        $timeline = Timeline::where('username', $username)->first();
        $page = $timeline->page;
        $page_members = $page->members();
        $roles = Role::pluck('name', 'id');

        $theme = Theme::uses('default')->layout('default');
        $theme->setTitle(trans('common.manage_roles').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('page/settings/roles', compact('timeline', 'page_members', 'roles', 'page'))->render();
    }

    public function likesPageSettings($username)
    {
        $timeline = Timeline::where('username', $username)->with('page')->first();
        $page_likes = $timeline->page->likes()->where('user_id', '!=', Auth::user()->id)->get();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.page_likes').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('page/settings/likes', compact('timeline', 'page_likes'))->render();
    }


    public function deleteComment(Request $request)
    {
        $comment = Comment::find($request->comment_id);

        if($comment->parent_id != null)
        {
            $parent_comment = Comment::find($comment->parent_id);
            $comment->update(['parent_id' => null]);
            $parent_comment->comments_liked()->detach();
            $parent_comment->delete();
        }
        else
        {
            $comment->comments_liked()->detach();
            $comment->delete();
        }
        if (Auth::user()->id != $comment->user_id) {
            //Notify the user for comment delete
            Notification::create(['user_id' => $comment->user->id, 'post_id' => $comment->post_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.deleted_your_comment'), 'type' => 'delete_comment']);
        }
        return response()->json(['status' => '200', 'deleted' => true, 'message' => 'Comment successfully deleted']);
    }

    public function deletePost(Request $request)
    {
        $post = Post::find($request->post_id);
        
        if ($post->user->id == Auth::user()->id) {
            $post->deleteMe();
        }
        return response()->json(['status' => '200', 'deleted' => true, 'message' => 'Post successfully deleted']);
    }

    public function reportPost(Request $request)
    {
        $post = Post::where('id', '=', $request->post_id)->first();
        $reported = $post->managePostReport($request->post_id, Auth::user()->id);

        if ($reported) {
            //Notify the user for reporting his post
            Notification::create(['user_id' => $post->user_id, 'post_id' => $request->post_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.reported_your_post'), 'type' => 'report_post']);

            return response()->json(['status' => '200', 'reported' => true, 'message' => 'Post successfully reported']);
        }
    }

    public function singlePost($post_id)
    {
        $mode = 'posts';
        $post = Post::where('id', '=', $post_id)->first();
        $timeline = Auth::user()->timeline;

        $trending_tags = trendingTags();
        $suggested_users = suggestedUsers();
        $suggested_groups = suggestedGroups();
        $suggested_pages = suggestedPages();

        //Redirect to home page if post doesn't exist
        if ($post == null) {
            return redirect('/');
        }
        $theme = Theme::uses('default')->layout('default');
        $theme->setTitle(trans('common.post').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('timeline/single-post', compact('post', 'timeline', 'suggested_users', 'trending_tags', 'suggested_groups', 'suggested_pages', 'mode'))->render();
    }

    public function eventsList(Request $request, $username)
    {
        $mode = "eventlist";

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        
        $user_events = Event::where('user_id', Auth::user()->id)->get();
        $id = Auth::id();

        $trending_tags = trendingTags();
        $suggested_users = suggestedUsers();
        $suggested_groups = suggestedGroups();
        $suggested_pages = suggestedPages();

        $next_page_url = url('ajax/get-more-feed?page=2&ajax=true&hashtag='.$request->hashtag.'&username='.$username);

        $theme->setTitle(trans('common.events').' | '.Setting::get('site_title').' | '.Setting::get('site_tagline'));

        return $theme->scope('home', compact('next_page_url', 'trending_tags', 'suggested_users', 'suggested_groups', 'suggested_pages', 'mode', 'user_events', 'username'))
        ->render();
    }

    public function addEvent($username, $group_id = null)
    {
        $timeline_name = '';
        if ($group_id) {
            $group = Group::find($group_id);
            $timeline_name = $group->timeline->name;
        }

        $suggested_users = suggestedUsers();
        $suggested_groups = suggestedGroups();
        $suggested_pages = suggestedPages();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        return $theme->scope('event-create', compact('suggested_users', 'suggested_groups', 'suggested_pages', 'username', 'group_id', 'timeline_name'))
            ->render();
    }

     /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validateEventPage(array $data)
    {
        return Validator::make($data, [
            'name'        => 'required|max:30|min:5',
            'start_date'  => 'required',
            'end_date'    => 'required',
            'location'    => 'required',
            'type'        => 'required',
        ]);
    }

    public function createEvent($username, Request $request)
    {
        $validator = $this->validateEventPage($request->all());

        if ($validator->fails()) {
            return redirect()->back()
                  ->withInput($request->all())
                  ->withErrors($validator->errors());
        }

        $start_date = date('Y-m-d H:i', strtotime($request->start_date));
        $end_date  = date('Y-m-d H:i', strtotime($request->end_date));
        
        if ($start_date >= date('Y-m-d', strtotime(Carbon::now())) && $end_date >= $start_date) {
            $user_timeline = Timeline::where('username', $username)->first();
            $timeline = Timeline::create([
                'username'  => $user_timeline->gen_num(),
                'name'      => $request->name,
                'about'     => $request->about,
                'type'      => 'event',
                ]);

            $event = Event::create([
                'timeline_id' => $timeline->id,
                'type'        => $request->type,
                'user_id'     => Auth::user()->id,
                'location'    => $request->location,
                'start_date'  => date('Y-m-d H:i', strtotime($request->start_date)),
                'end_date'    => date('Y-m-d H:i', strtotime($request->end_date)),
                'invite_privacy'        => Setting::get('invite_privacy'),
                'timeline_post_privacy' => Setting::get('event_timeline_post_privacy'),
                ]);

            if ($request->group_id) {
                $event->group_id = $request->group_id;
                $event->save();
            }

            $event->users()->attach(Auth::user()->id);
            Flash::success(trans('messages.create_event_success'));
            return redirect('/'.$timeline->username);
        } else {
            $message = 'Invalid date selection';
            return redirect()->back()->with('message', trans('messages.invalid_date_selection'));
        }
    }

    //Displaying event posts
    public function getEventPosts($username)
    {
        $user_post = 'event';
        $timeline = Timeline::where('username', $username)->with('event', 'event.users')->first();
        $event = $timeline->event;

        if (!$event->is_eventadmin(Auth::user()->id, $event->id) &&  !$event->users->contains(Auth::user()->id)) {
            return redirect($username);
        }

        $posts = $timeline->posts()->where('active', 1)->orderBy('created_at', 'desc')->with('comments')->get();
      
        $next_page_url = url('ajax/get-more-posts?page=2&username='.$username);

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.posts').' | '.Setting::get('site_title').' | '.Setting::get('site_tagline'));

        return $theme->scope('timeline/eventposts', compact('timeline', 'posts', 'event', 'next_page_url', 'user_post'))->render();
    }

     //Displaying event guests
    public function displayGuests($username)
    {
        $timeline = Timeline::where('username', $username)->with('event')->first();
        $event = $timeline->event;
        $event_guests = $event->guests($event->user_id);
        
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.invitemembers').' | '.Setting::get('site_title').' | '.Setting::get('site_tagline'));

        return $theme->scope('users/eventguests', compact('timeline', 'event', 'event_guests'))->render();
    }

    public function generalEventSettings($username)
    {
        $timeline = Timeline::where('username', $username)->with('event')->first();

        $event_details = $timeline->event()->first();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.general_settings').' | '.Setting::get('site_title').' | '.Setting::get('site_tagline'));

        return $theme->scope('event/settings', compact('timeline', 'username', 'event_details'))->render();
    }

    public function updateUserEventSettings($username, Request $request)
    {
        $validator = $this->validateEventPage($request->all());

        if ($validator->fails()) {
            return redirect()->back()
                  ->withInput($request->all())
                  ->withErrors($validator->errors());
        }

        $start_date = date('Y-m-d H:i', strtotime($request->start_date));
        $end_date  = date('Y-m-d H:i', strtotime($request->end_date));
        
        if ($start_date >= date('Y-m-d', strtotime(Carbon::now())) && $end_date >= $start_date) {
            $timeline = Timeline::where('username', $username)->first();
            $timeline_values = $request->only('name', 'about');
            $update_timeline = $timeline->update($timeline_values);

            $event = Event::where('timeline_id', $timeline->id)->first();
            $event_values = $request->only('type', 'location', 'invite_privacy', 'timeline_post_privacy');
            $event_values['start_date'] = date('Y-m-d H:i', strtotime($request->start_date));
            $event_values['end_date'] = date('Y-m-d H:i', strtotime($request->end_date));
            $update_values = $event->update($event_values);

            if ($request->group_id) {
                $event->group_id = $request->group_id;
                $event->save();
            }

            Flash::success(trans('messages.update_event_Settings'));
            return redirect()->back();
        } else {
            Flash::error(trans('messages.invalid_date_selection'));
            return redirect()->back();
        }
    }

    public function deleteEvent(Request $request)
    {
        $event = Event::find($request->event_id);
        
        //Deleting Events
        $event->users()->detach();

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
        
        return response()->json(['status' => '200', 'deleted' => true, 'message' => 'Event successfully deleted']);
    }

    public function allNotifications()
    {
        $mode = 'notifications';
        $notifications = Notification::where('user_id', Auth::user()->id)->with('notified_from')->latest()->paginate(Setting::get('items_page', 10));
        
        $trending_tags = trendingTags();
        $suggested_users = suggestedUsers();
        $suggested_groups = suggestedGroups();
        $suggested_pages = suggestedPages();

        if ($notifications == null) {
            return redirect('/');
        }

        $theme = Theme::uses('default')->layout('default');
        $theme->setTitle(trans('common.notifications').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('timeline/single-post', compact('notifications', 'suggested_users', 'trending_tags', 'suggested_groups', 'suggested_pages', 'mode'))->render();
    }

    public function deleteNotification(Request $request)
    {
        $notification = Notification::find($request->notification_id);
        if ($notification->delete()) {
            Flash::success(trans('messages.notification_deleted_success'));

            return response()->json(['status' => '200', 'notify' => true, 'message' => 'Notification deleted successfully']);
        }
    }

    public function deleteAllNotifications(Request $request)
    {
        $notifications = Notification::where('user_id', Auth::user()->id)->get();

        if ($notifications) {
            foreach ($notifications as $notification) {
                $notification->delete();
            }

            Flash::success(trans('messages.notifications_deleted_success'));
            return response()->json(['status' => '200', 'allnotify' => true, 'message' => 'Notifications deleted successfully']);
        }
    }
    
    public function hidePost(Request $request)
    {
        $post = Post::where('id', '=', $request->post_id)->first();

        if ($post->user->id == Auth::user()->id) {
            $post->active = 0;
            $post->save();

            return response()->json(['status' => '200', 'hide' => true, 'message' => 'Post is hidden successfully']);
        } else {
            return response()->json(['status' => '200', 'unhide' => false, 'message' => 'Unsuccessful']);
        }
    }

    public function linkPreview()
    {
        $linkPreview = new LinkPreview('http://github.com');
        $parsed = $linkPreview->getParsed();
        foreach ($parsed as $parserName => $link) {
            echo $parserName. '<br>' ;
            echo $link->getUrl() . PHP_EOL;
            echo $link->getRealUrl() . PHP_EOL;
            echo $link->getTitle() . PHP_EOL;
            echo $link->getDescription() . PHP_EOL;
            echo $link->getImage() . PHP_EOL;
            print_r($link->getPictures());
            dd();
        }
    }

    public function deleteGroup(Request $request)
    {
        $group = Group::where('id', '=', $request->group_id)->first();
        
        $group->timeline->reports()->detach();
        
        //Deleting events in a group
        if (count($group->getEvents()) != 0) {
            foreach ($group->getEvents() as $event) {
                $event->users()->detach();

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
        
        $timeline_alerts = $group->timeline()->with('notifications')->first();

        if (count($timeline_alerts->notifications) != 0) {
            foreach ($timeline_alerts->notifications as $notification) {
                $notification->delete();
            }
        }
        $timeline_posts = $group->timeline()->with('posts')->first();
        
        if (count($timeline_posts->posts) != 0) {
            foreach ($timeline_posts->posts as $post) {
                $post->deleteMe();
            }
        }
        $group_timeline = $group->timeline();
        $group->delete();
        $group_timeline->delete();

        return response()->json(['status' => '200', 'deleted' => true, 'message' => 'Group successfully deleted']);
    }

    public function allAlbums($username)
    {
        $timeline = Timeline::where('username', $username)->first();
        $albums = $timeline->albums()->with('photos')->get();

        $trending_tags = trendingTags();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(Auth::user()->name.' '.Setting::get('title_seperator').' '.trans('common.albums').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('albums/index', compact('timeline', 'albums', 'trending_tags'))->render();
    }

    public function allPhotos($username)
    {
        $timeline = Timeline::where('username', $username)->first();
        $albums = $timeline->albums()->get();

        if (count($albums) > 0) {
            foreach ($albums as $album) {
                $photos[] = $album->photos()->where('type', 'image')->get();
            }
            foreach ($photos as $photo) {
                foreach ($photo as $image) {
                    $images[] = $image;
                }
            }
        }
        $trending_tags = trendingTags();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(Auth::user()->name.' '.Setting::get('title_seperator').' '.trans('common.photos').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('albums/photos', compact('timeline', 'images', 'trending_tags'))->render();
    }

    public function allVideos($username)
    {
        $timeline = Timeline::where('username', $username)->first();
        if (Setting::get('announcement') != null) {
            $election = Announcement::find(Setting::get('announcement'));
        }

        $albums = $timeline->albums()->get();
        
        if (count($albums) > 0) {
            foreach ($albums as $album) {
                $photos[] = $album->photos()->where('type', 'youtube')->get();
            }
            foreach ($photos as $photo) {
                foreach ($photo as $video) {
                    $videos[] = $video;
                }
            }
        }
        
        $trending_tags = trendingTags();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(Auth::user()->name.' '.Setting::get('title_seperator').' '.trans('common.photos').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('albums/videos', compact('timeline', 'videos', 'trending_tags', 'election'))->render();
    }

    public function viewAlbum($username, $id)
    {
        $timeline = Timeline::where('username', $username)->first();
        $album = Album::where('id', $id)->with('photos')->first();

        $trending_tags = trendingTags();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle($album->name.' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('albums/show', compact('timeline', 'album', 'trending_tags'))->render();
    }

    public function albumPhotoEdit(Request $request)
    {
        $media = Media::find($request->media_id);
        if ($media->source) {
            return response()->json(['status' => '200', 'photo_src' => true, 'pic_source' => $media->source]);
        } else {
            return response()->json(['status' => '200', 'photo_src' => false]);
        }
    }

    public function createAlbum($username)
    {
        $suggested_users = suggestedUsers();
        $suggested_groups = suggestedGroups();
        $suggested_pages = suggestedPages();

        $timeline = Timeline::where('username', Auth::user()->username)->first();
        
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.create_album').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('albums/create', compact('suggested_users', 'suggested_groups', 'suggested_pages', 'timeline'))->render();
    }

    protected function albumValidator(array $data)
    {
        return Validator::make($data, [
            'name'     => 'required|max:30|min:5',
            'privacy'  => 'required'
          ]);
    }

    public function saveAlbum(Request $request, $username)
    {
        // $validator = $this->albumValidator($request->only('name','privacy'));

        // if ($validator->fails()) {
        //     return redirect()->back()
        //           ->withInput($request->all())
        //           ->withErrors($validator->errors());
        // }

        if ($request->album_photos[0] == null || $request->name == null || $request->privacy == null) {
            Flash::error(trans('messages.album_validation_error'));
            return redirect()->back();
        }

        $input = $request->except('_token', 'album_photos');
        $input['timeline_id'] = Timeline::where('username', $username)->first()->id;
        $album = Album::create($input);

        foreach ($request->album_photos as $album_photo) {
            $strippedName = str_replace(' ', '', $album_photo->getClientOriginalName());
            $photoName = date('Y-m-d-H-i-s').$strippedName;
            $photo = Image::make($album_photo->getRealPath());
            $photo->save(storage_path().'/uploads/albums/'.$photoName, 60);

            $media = Media::create([
              'title'  => $album_photo->getClientOriginalName(),
              'type'   => 'image',
              'source' => $photoName,
            ]);

            $album->photos()->attach($media->id);
        }

        if ($request->album_videos[0] != null) {
            foreach ($request->album_videos as $album_video) {
                $match;
                if (preg_match("/(youtube.com|youtu.be)\/(watch)?(\?v=)?(\S+)?/", $album_video, $match)) {
                    if ($match[2] != null) {
                        $videoId = Youtube::parseVidFromURL($album_video);
                        $video = Youtube::getVideoInfo($videoId);
                
                        $video = Media::create([
                        'title'  => $video->snippet->title,
                        'type'   => 'youtube',
                        'source' => $videoId,
                        ]);
                        $album->photos()->attach($video->id);
                    } else {
                        Flash::error(trans('messages.not_valid_url'));
                        return redirect()->back();
                    }
                } else {
                    Flash::error(trans('messages.not_valid_url'));
                    return redirect()->back();
                }
            }
        }

        if ($album) {
            Flash::success(trans('messages.create_album_success'));
            return redirect('/'.$username.'/album/show/'.$album->id);
        } else {
            Flash::error(trans('messages.create_album_error'));
        }
        return redirect()->back();
    }

    public function editAlbum($username, $id)
    {
        $album = Album::where('id', $id)->with('photos')->first();

        $trending_tags = trendingTags();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle($album->name.' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('albums/edit', compact('album', 'trending_tags'))->render();
    }

    public function updateAlbum($username, $id, Request $request)
    {
        // $validator = $this->albumValidator($request->all());

        // if ($validator->fails()) {
        //     return redirect()->back()
        //           ->withInput($request->all())
        //           ->withErrors($validator->errors());
        // }
        if ($request->name == null || $request->privacy == null) {
            Flash::error(trans('messages.album_validation_error'));
            return redirect()->back();
        }

        $album = Album::findOrFail($id);
        $input = $request->except('_token', 'album_photos');
        $album->update($input);
        
        if ($request->album_photos[0] != null) {
            foreach ($request->album_photos as $album_photo) {
                $strippedName = str_replace(' ', '', $album_photo->getClientOriginalName());
                $photoName = date('Y-m-d-H-i-s').$strippedName;
                $photo = Image::make($album_photo->getRealPath());
                $photo->save(storage_path().'/uploads/albums/'.$photoName, 60);

                $media = Media::create([
                  'title'  => $album_photo->getClientOriginalName(),
                  'type'   => 'image',
                  'source' => $photoName,
                ]);

                $album->photos()->attach($media->id);
            }
        }

        if ($request->album_videos[0] != null) {
            foreach ($request->album_videos as $album_video) {
                $match;
                if (preg_match("/(youtube.com|youtu.be)\/(watch)?(\?v=)?(\S+)?/", $album_video, $match)) {
                    if ($match[2] != null) {
                        $videoId = Youtube::parseVidFromURL($album_video);
                        $video = Youtube::getVideoInfo($videoId);
                
                        $video = Media::create([
                        'title'  => $video->snippet->title,
                        'type'   => 'youtube',
                        'source' => $videoId,
                        ]);
                        $album->photos()->attach($video->id);
                    } else {
                        Flash::error(trans('messages.not_valid_url'));
                        return redirect()->back();
                    }
                } else {
                    Flash::error(trans('messages.not_valid_url'));
                    return redirect()->back();
                }
            }
        }

        if ($album) {
            Flash::success(trans('messages.update_album_success'));
            return redirect('/'.$username.'/album/show/'.$album->id);
        } else {
            Flash::error(trans('messages.update_album_error'));
        }
        return redirect()->back();
    }

    public function deleteAlbum($username, $photo_id)
    {
        $album = Album::findOrFail($photo_id);
        $album->photos()->detach();
        if ($album->delete()) {
            Flash::success(trans('messages.delete_album_success'));
        } else {
            Flash::error(trans('messages.delete_album_error'));
        }
        return redirect('/'.$username.'/albums');
    }

    public function addPreview($username, $id, $photo_id)
    {
        $album = Album::findOrFail($id);
        $album->preview_id = $photo_id;
        if ($album->save()) {
            Flash::success(trans('messages.update_preview_success'));
        } else {
            Flash::error(trans('messages.update_preview_error'));
        }
        return redirect()->back();
    }

    public function deleteMedia($username, $photo_id)
    {
        $media = Media::find($photo_id);
        $media->albums()->where('preview_id', $media->id)->update(['albums.preview_id' => null]);
        $media->albums()->detach();
      
        if ($media->delete()) {
            Flash::success(trans('messages.delete_media_success'));
        } else {
            Flash::error(trans('messages.delete_media_error'));
        }
        return redirect()->back();
    }
    
    public function unjoinPage(Request $request)
    {
        $page = Page::where('timeline_id', '=', $request->timeline_id)->first();

        if ($page->users->contains(Auth::user()->id)) {
            $page->users()->detach([Auth::user()->id]);

            return response()->json(['status' => '200', 'join' => true, 'username'=> Auth::user()->username, 'message' => 'successfully unjoined']);
        }
    }
    public function saveWallpaperSettings($username, Request $request)
    {
        if($request->wallpaper == null)
        {
            Flash::error(trans('messages.no_file_added'));
            return redirect()->back();
        }

        $timeline = Timeline::where('username', $username)->first();
        $result = $timeline->saveWallpaper($request->wallpaper);
        if($result)
        {
            Flash::success(trans('messages.wallpaper_added_activated'));
            return redirect()->back();
        }
    }

    public function toggleWallpaper($username,$action, Media $media)
    {
        $timeline = Timeline::where('username', $username)->first();
        
        $result = $timeline->toggleWallpaper($action, $media);

        if($result == 'activate')
        {
            Flash::success(trans('messages.activate_wallpaper_success'));
        }
        if($result == 'deactivate')
        {
            Flash::success(trans('messages.deactivate_wallpaper_success'));
        }
        return Redirect::back();
    }

    public function pageWallpaperSettings($username)
    {
      $timeline = Timeline::where('username', $username)->first();
      $wallpapers = Wallpaper::all();

      $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.wallpaper_settings').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('page/settings/wallpaper', compact('timeline', 'wallpapers'))->render();
    }

    public function groupGeneralSettings($username)
    {
        $timeline = Timeline::where('username', $username)->first();

        $group_details = $timeline->groups()->first();

        $group = Group::where('timeline_id', '=', $timeline->id)->first();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.group_settings').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('group/settings/general', compact('timeline', 'username', 'group_details'))->render();
    }

    public function groupWallpaperSettings($username)
    {
      $timeline = Timeline::where('username', $username)->first();
      $wallpapers = Wallpaper::all();

      $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.wallpaper_settings').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('group/settings/wallpaper', compact('timeline', 'wallpapers'))->render();
    }

    public function saveTimeline(Request $request)
    {
        $timeline = Timeline::find($request->timeline_id);
        if($timeline == null)
        {
           return response()->json(['status' => '201', 'message' => 'Invalid Timeline']); 
        }
        if(Auth::user()->timelinesSaved()->where('timeline_id',$request->timeline_id)->where('saved_timelines.type', $timeline->type)->get()->isEmpty())
        {
            Auth::user()->timelinesSaved()->attach($timeline->id, ['type' => $timeline->type]);
            return response()->json(['status' => '200', 'message' => $timeline->type.' saved successfully']);
        }
        else
        {
            Auth::user()->timelinesSaved()->detach($timeline->id);
            return response()->json(['status' => '200', 'message' => $timeline->type.' unsaved successfully']);
        }
    }

    public function savePost(Request $request)
    {
        $post = Post::find($request->post_id);
        if($post == null)
        {
           return response()->json(['status' => '201', 'message' => 'Invalid Post']); 
        }
        if(Auth::user()->postsSaved()->where('post_id',$request->post_id)->get()->isEmpty())
        {
            Auth::user()->postsSaved()->attach($post->id);
            return response()->json(['status' => '200', 'type' => 'save', 'message' => 'Post saved successfully']);
        }
        else
        {
            Auth::user()->postsSaved()->detach($post->id);
            return response()->json(['status' => '200', 'type' => 'unsave', 'message' => 'Post unsaved successfully']);
        }
    }

    public function pinPost(Request $request)
    {
        $post = Post::find($request->post_id);
        if($post == null)
        {
            return response()->json(['status' => '201', 'message' => 'Invalid Post']);
        }
        if(Auth::user()->postsPinned()->where('post_id',$request->post_id)->get()->isEmpty())
        {
            Auth::user()->postsPinned()->attach($post->id);
            return response()->json(['status' => '200', 'type' => 'pin', 'message' => 'Post pinned successfully']);
        }
        else
        {
            Auth::user()->postsPinned()->detach($post->id);
            return response()->json(['status' => '200', 'type' => 'unpin', 'message' => 'Post unpinned successfully']);
        }
    }

    public function updateUserList(Request $request)
    {

//        $user_id = Auth::user()->id;
//        Auth::user()->userList()->where(['list_type_id' => $request->list_type_id, 'saved_user_id' => $request->saved_user_id])->get()->isEmpty()

        $user_list = UserList::where(['user_id' => Auth::user()->id, 'saved_user_id' => $request->saved_user_id, 'list_type_id' => $request->list_type_id])->first();

        if(empty($user_list))
        {

            $user_list = new UserList;

            $user_list->user_id = Auth::user()->id;
            $user_list->saved_user_id = $request->saved_user_id;
            $user_list->list_type_id = $request->list_type_id;

            $user_list->save();

            return response()->json(['status' => '200', 'type' => 'add', 'message' => 'Added successfully']);
        }
        else
        {
            $user_list->delete();
            return response()->json(['status' => '200', 'type' => 'remove', 'message' => 'Removed successfully']);
        }
    }

    public function getUserList(Request $request)
    {

//        $user_id = Auth::user()->id;
//        Auth::user()->userList()->where(['list_type_id' => $request->list_type_id, 'saved_user_id' => $request->saved_user_id])->get()->isEmpty()

        $user_lists = UserListType::where(['user_id' => Auth::user()->id])->with('lists')->get();

        if (!empty($user_lists)) {

            foreach ($user_lists as $user_list) {
                if (UserList::where(['list_type_id' => $user_list->id, 'saved_user_id' => $request->saved_user_id])->get()->isEmpty()) {
                    $user_list->state = 0;
                } else {
                    $user_list->state = 1;
                }
            }
        }

        return response()->json(['user_lists' => $user_lists]);

//        if(empty($user_list))
//        {
//
//            $user_list = new UserList;
//
//            $user_list->user_id = Auth::user()->id;
//            $user_list->saved_user_id = $request->saved_user_id;
//            $user_list->list_type_id = $request->list_type_id;
//
//            $user_list->save();
//
//            return response()->json(['status' => '200', 'type' => 'add', 'message' => 'Added successfully']);
//        }
//        else
//        {
//            $user_list->delete();
//            return response()->json(['status' => '200', 'type' => 'remove', 'message' => 'Removed successfully']);
//        }
    }


    public function getListsSortBy(Request $request)
    {

        $sort_by = $request->sort_by;
        $order_by = $request->order_by;

        $user_lists = $this->getUsersListOfCurrentUser($sort_by, $order_by);

        return response()->json(['status' => '200', 'user_lists' => $user_lists]);
    }

    public function getUsersListOfCurrentUser($sort_by, $order_by)
    {
        if ($sort_by == 'recent')
            $user_lists = UserListType::where(['user_id' => Auth::user()->id])->orderBy('created_at', $order_by)->with('lists')->get();
        else
            $user_lists = UserListType::where(['user_id' => Auth::user()->id])->with('lists')->get();

        if (!empty($user_lists)) {
            foreach ($user_lists as $user_list) {
                $user_list->count = count($user_list->lists);
            }
        }

        $lists = array();

        foreach ($user_lists as $user_list) {

            $list = array();
            $list['name'] = $user_list->list_type;
            $list['count'] = $user_list->count;
            $list['id'] = $user_list->id;
            $list['created_at'] = $user_list->created_at;

            $lists[] = $list;
        }

        $following_count = Auth::user()->following()->where('status', '=', 'approved')->get()->count();
        $followers_count = Auth::user()->followers()->where('status', '=', 'approved')->get()->count();

        $list = array();
        $list['name'] = trans('common.following-1');
        $list['count'] = $following_count;
        $list['id'] = 'following';
        $list['created_at'] = Auth::user()->created_at;
        $lists[] = $list;

        $list = array();
        $list['name'] = trans('common.followers');
        $list['count'] = $followers_count;
        $list['id'] = 'followers';
        $list['created_at'] = Auth::user()->created_at;
        $lists[] = $list;

        $sorted_lists = array();
        foreach ($lists as $key => $row) {

            if ($sort_by == 'name')
                $sorted_lists[$key] = $row['name'];
            else if ($sort_by == 'people')
                $sorted_lists[$key] = $row['count'];
            else if ($sort_by == 'recent')
                $sorted_lists[$key] = $row['created_at'];
        }

        array_multisort($sorted_lists, $order_by == 'asc' ? SORT_ASC : SORT_DESC, $lists);

        return $lists;
    }

    public function addNewUserList(Request $request)
    {

        $user_lists = UserListType::where(['user_id' => Auth::user()->id])->get();

        foreach ($user_lists as $user_list) {
            if (!strcasecmp($user_list->list_type, $request->new_list_name)) {
                return response()->json(['status' => '202', 'message' => 'This name exists already.']);
            }
        }

        $user_list_type = new UserListType;

        $user_list_type->user_id = Auth::user()->id;
        $user_list_type->list_type = $request->new_list_name;

        $user_list_type->save();
        return response()->json(['status' => '200', 'type' => 'add', 'message' => 'Added successfully']);
    }

    public function showMyLists()
    {
        $user_lists = $this->getUsersListOfCurrentUser('name', 'asc');

        $trending_tags = trendingTags();
        $suggested_users = suggestedUsers();
        $suggested_groups = suggestedGroups();
        $suggested_pages = suggestedPages();

        $following_count = Auth::user()->following()->where('status', '=', 'approved')->get()->count();
        $followers_count = Auth::user()->followers()->where('status', '=', 'approved')->get()->count();

        $theme = Theme::uses('default')->layout('default');
        $theme->setTitle(trans('common.lists').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('timeline/my-lists', compact( 'suggested_users', 'trending_tags', 'suggested_groups', 'suggested_pages', 'user_lists', 'following_count', 'followers_count'))->render();
    }

    public function showSpecificList($list_type_id) {

        $following_count = Auth::user()->following()->where('status', '=', 'approved')->get()->count();
        $followers_count = Auth::user()->followers()->where('status', '=', 'approved')->get()->count();
        $suggested_users = suggestedUsers();

        $saved_users = array();

        if ($list_type_id == 'followers') {

            $saved_users = Auth::user()->followers()->where('status', '=', 'approved')->get();
            $list_type_name = "Fans";

            $theme = Theme::uses('default')->layout('default');
            $theme->setTitle(trans('common.followers-1').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        } else if ($list_type_id == 'following') {

            $saved_users = Auth::user()->following()->where('status', '=', 'approved')->get();
            $list_type_name = "Followers";

            $theme = Theme::uses('default')->layout('default');
            $theme->setTitle(trans('common.following').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        } else {

            $saved_user_list = UserList::where(['list_type_id' => $list_type_id])->with('savedUsers')->get();

            $list_type = UserListType::where(['id' => $list_type_id])->first();
            $list_type_name = $list_type->list_type;

            foreach ($saved_user_list as $key => $list) {
                $saved_users[$key] = $list->savedUsers;
            }

            $theme = Theme::uses('default')->layout('default');
            $theme->setTitle($list_type_name.' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        }

        return $theme->scope('timeline/my-list', compact( 'suggested_users', 'trending_tags', 'suggested_groups', 'suggested_pages', 'user_lists', 'following_count', 'followers_count', 'saved_users', 'list_type_id', 'list_type_name'))->render();
    }

    public function sendTipPost(Request $request)
    {
        $post = Post::findOrFail($request->post_id);
        $posted_user = $post->user;

        $post->tip()->attach(Auth::user()->id, ['amount' => $request->amount, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
        $post->notifications_user()->attach(Auth::user()->id);

        $user = User::find(Auth::user()->id);
        //Notify the user for post like
        $notify_message = 'sent tip for your post';
        $notify_type = 'tip_post';
        $status_message = 'success';

        if ($post->user->id != Auth::user()->id) {
            Notification::create(['user_id' => $post->user->id, 'post_id' => $post->id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.$notify_message, 'type' => $notify_type]);
        }

        return response()->json(['status' => '200', 'message' => $status_message]);
    }


    public function switchLanguage(Request $request)
    {
        if (Auth::check()) {
            Auth::user()->update(['language' => $request->language]);
        }
        else {
            session(['my_locale' => $request->language]);
        }
        App::setLocale($request->language);
        return response()->json(['status' => '200', 'message' => 'Switched language to '.$request->language, 'url' => redirect()->back()->getTargetUrl()]);
    }
}
