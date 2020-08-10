<?php

use Cmgmyr\Messenger\Models\Message;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API routes
|--------------------------------------------------------------------------
*/

Route::post('ajax/switch-language', 'TimelineController@switchLanguage');

// Webhooks
Route::post('webhook', 'CheckoutController@webhook');
Route::post('/register/{affliate}', 'Auth\RegisterController@registerUser');
Route::get('webhook', 'CheckoutController@webhook');

Route::get('/contact', 'PageController@contact');
Route::post('/contact', 'PageController@saveContact');
Route::get('/share-post/{id}', 'PageController@sharePost');
Route::get('/post/{id}', 'PageController@sharePost');
Route::get('/get-location/{location}', 'HomeController@getLocation');

Route::group(['prefix' => 'api', 'middleware' => ['auth', 'cors'], 'namespace' => 'API'], function () {
    Route::group(['prefix' => 'v1'], function () {
        require config('infyom.laravel_generator.path.api_routes');
    });
});

Route::post('pusher/auth', function (Illuminate\Http\Request $request, Pusher $pusher) {
    return $pusher->presence_auth(
        $request->input('channel_name'),
        $request->input('socket_id'),
        uniqid(),
        ['username' => $request->input('username')]
    );
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => ['web']], function () {
    Auth::routes();
});



// Redirect to facebook to authenticate
Route::get('facebook', 'Auth\RegisterController@facebookRedirect');
// Get back to redirect url
Route::get('account/facebook', 'Auth\RegisterController@facebook');

// Redirect to google to authenticate
Route::get('google', 'Auth\RegisterController@googleRedirect');
// Get back to redirect url
Route::get('account/google', 'Auth\RegisterController@google');

// Redirect to twitter to authenticate
Route::get('twitter', 'Auth\RegisterController@twitterRedirect');
// Get back to redirect url
Route::get('account/twitter', 'Auth\RegisterController@twitter');

// Redirect to linkedin to authenticate
Route::get('linkedin', 'Auth\RegisterController@linkedinRedirect');
// Get back to redirect url
Route::get('account/linkedin', 'Auth\RegisterController@linkedin');


// Stripe
Route::group(['prefix' => 'checkout', 'middleware' => ['auth']], function() {
    Route::post('create-checkout-session/{timeline_id}', 'CheckoutController@createCheckoutSession');
    Route::get('create-checkout-session/{timeline_id}', 'CheckoutController@createCheckoutSession');
    Route::get('checkout-session', 'CheckoutController@checkoutSession');
    Route::post('config/{timeline_id}', 'CheckoutController@getConfig');

    // connected account
    Route::post('get-oauth-link', 'CheckoutController@getOAuthLink');
    Route::get('get-oauth-link', 'CheckoutController@getOAuthLink');
    Route::post('/authorize-oauth', 'CheckoutController@authorizeOAuth');
    Route::get('/authorize-oauth', 'CheckoutController@authorizeOAuth');

});

// User support
Route::get('faq', 'HomeController@faq');
Route::get('support', 'HomeController@support');
Route::get('terms-of-use', 'HomeController@termsOfUse');
Route::get('privacy-policy', 'HomeController@privacyPolicy');

// Login
Route::get('/login', 'Auth\LoginController@getLogin');
// Route::post('/login', 'Auth\LoginController@login');
// Route::get('/login2', 'Auth\LoginController@login');

// Register
Route::get('/register', 'Auth\RegisterController@register');

Route::post('/register', 'Auth\RegisterController@registerUser');

Route::get('email/verify', 'Auth\RegisterController@verifyEmail');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', 'TimelineController@showFeed');
    Route::get('/browse', 'TimelineController@showGlobalFeed');
});

//main project register
// Route::get('/main-register', 'Auth\RegisterController@mainProjectRegister');
Route::post('/main-login', 'Auth\LoginController@mainProjectLogin');
// Route::get('/main-user-update', 'Auth\RegisterController@mainUserUpdate');


Route::get('/home', 'HomeController@index');

Route::post('/member/update-role', 'TimelineController@assignMemberRole');
Route::post('/member/updatepage-role', 'TimelineController@assignPageMemberRole');
Route::get('/post/{post_id}', 'TimelineController@singlePost');

Route::get('allnotifications', 'TimelineController@allNotifications');


/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
*/


Route::group(['prefix' => '/admin', 'middleware' => ['auth', 'role:admin']], function () {
    Route::get('/', 'AdminController@dashboard');
    Route::get('/general-settings', 'AdminController@generalSettings');
    Route::post('/general-settings', 'AdminController@updateGeneralSettings');
    Route::post('/home-settings', 'AdminController@updateHomeSettings');

    Route::get('/user-settings', 'AdminController@userSettings');
    Route::post('/user-settings', 'AdminController@updateUserSettings');

    // Route::get('/page-settings', 'AdminController@pageSettings');
    // Route::post('/page-settings', 'AdminController@updatePageSettings');

    // Route::get('/group-settings', 'AdminController@groupSettings');
    // Route::post('/group-settings', 'AdminController@updateGroupSettings');

    // Route::get('/custom-pages', 'AdminController@listCustomPages');
    // Route::get('/custom-pages/create', 'AdminController@createCustomPage');
    // Route::post('/custom-pages', 'AdminController@storeCustomPage');
    // Route::get('/custom-pages/{id}/edit', 'AdminController@editCustomPage');
    // Route::post('/custom-pages/{id}/update', 'AdminController@updateCustomPage');

    // Route::get('/announcements', 'AdminController@getAnnouncements');
    // Route::get('/announcements/create', 'AdminController@createAnnouncement');
    // Route::get('/announcements/{id}/edit', 'AdminController@editAnnouncement');
    // Route::post('/announcements/{id}/update', 'AdminController@updateAnnouncement');
    // Route::post('/announcements', 'AdminController@addAnnouncements');
    // Route::get('/activate/{announcement_id}', 'AdminController@activeAnnouncement');

    Route::get('/themes', 'AdminController@themes');
    Route::get('/change-theme/{name}', 'AdminController@changeTheme');

    Route::get('/users', 'AdminController@showUsers');
    Route::get('/users/{username}/edit', 'AdminController@editUser');
    Route::post('/users/{username}/edit', 'AdminController@updateUser');
    Route::get('/users/{user_id}/delete', 'AdminController@deleteUser');

    Route::get('/users/{username}/delete', 'UserController@deleteMe');
    Route::post('/users/{username}/newpassword', 'AdminController@updatePassword');

    // Route::get('/pages', 'AdminController@showPages');
    // Route::get('/pages/{username}/edit', 'AdminController@editPage');
    // Route::post('/pages/{username}/edit', 'AdminController@updatePage');
    // Route::get('/pages/{page_id}/delete', 'AdminController@deletePage');


    // Route::get('/groups', 'AdminController@showGroups');
    // Route::get('/groups/{username}/edit', 'AdminController@editGroup');
    // Route::post('/groups/{username}/edit', 'AdminController@updateGroup');
    // Route::get('/groups/{group_id}/delete', 'AdminController@deleteGroup');


    Route::get('/manage-reports', 'AdminController@manageReports');
    Route::post('/manage-reports', 'AdminController@updateManageReports');
    Route::get('/mark-safe/{report_id}', 'AdminController@markSafeReports');
    Route::get('/delete-post/{report_id}/{post_id}', 'AdminController@deletePostReports');

    // Route::get('/manage-ads', 'AdminController@manageAds');
    Route::get('/update-database', 'AdminController@getUpdateDatabase');
    Route::post('/update-database', 'AdminController@postUpdateDatabase');
    Route::get('/get-env', 'AdminController@getEnv');
    Route::post('/save-env', 'AdminController@saveEnv');
    // Route::post('/manage-ads', 'AdminController@updateManageAds');
    Route::get('/settings', 'AdminController@settings');
    Route::get('/markpage-safe/{report_id}', 'AdminController@markPageSafeReports');
    // Route::get('/deletepage/{page_id}/{status}', 'AdminController@deletePage');
    Route::get('/deleteuser/{username}', 'UserController@deleteMe');
    // Route::get('/deletegroup/{group_id}', 'AdminController@deleteGroup');

    // Route::get('/category/create', 'AdminController@addCategory');
    // Route::post('/category/create', 'AdminController@storeCategory');
    // Route::get('/category/{id}/edit', 'AdminController@editCategory');
    // Route::post('/category/{id}/update', 'AdminController@updateCategory');

    // Route::get('/events', 'AdminController@getEvents');
    // Route::get('/events/{username}/edit', 'AdminController@editEvent');
    // Route::post('/events/{username}/edit', 'AdminController@updateEvent');
    // Route::get('/events/{event_id}/delete', 'AdminController@removeEvent');

    // Route::get('/event-settings', 'AdminController@eventSettings');
    // Route::post('/event-settings', 'AdminController@updateEventSettings');

    Route::get('/wallpapers', 'AdminController@wallpapers');
    Route::post('/wallpapers', 'AdminController@addWallpapers');
    Route::get('/wallpaper/{wallpaper}/delete', 'AdminController@deleteWallpaper');
});


/*
|--------------------------------------------------------------------------
| Messages routes
|--------------------------------------------------------------------------
*/

    Route::get('messages/{username?}', 'MessageController@index');



/*
|--------------------------------------------------------------------------
| User routes
|--------------------------------------------------------------------------
*/

// Publicly user profile view

Route::group(['prefix' => '/{username}'], function ($username) {
    Route::get('/', 'TimelineController@posts');
    Route::post('/', 'TimelineController@posts');
});

Route::group(['prefix' => '/{username}', 'middleware' => 'auth'], function ($username) {
    // Route::get('/', 'TimelineController@posts');
    // Route::post('/', 'TimelineController@posts');

    Route::get('/followers', 'UserController@followers');

    Route::get('/following', 'UserController@following');

    Route::get('/event-guests', 'UserController@getGuestEvents');

    Route::get('/posts', 'TimelineController@posts');

    Route::get('/liked-pages', 'UserController@likedPages');
    Route::get('/joined-groups', 'UserController@joinedGroups');
    
    Route::get('/members/{group_id}', 'TimelineController@getGroupMember');

    // Route::get('/groupadmin/{group_id}', 'TimelineController@getAdminMember');
    // Route::get('/groupposts/{group_id}', 'TimelineController@getGroupPosts');
    // Route::get('/page-posts', 'TimelineController@getPagePosts');
    // Route::get('/page-likes', 'TimelineController@getPageLikes');
    // Route::get('/pagemembers', 'TimelineController@getPageMember');
    // Route::get('/pageadmin', 'TimelineController@getPageAdmins');
    // Route::get('/add-members', 'UserController@membersList');
    // Route::get('/add-pagemembers', 'UserController@pageMembersList');

    // Route::get('/groupevent/{group_id}', 'TimelineController@addEvent');
    
    Route::get('/notification/{id}', 'NotificationController@redirectNotification');

    // Route::get('/events', 'TimelineController@eventsList');
    
    // Route::get('/event-posts', 'TimelineController@getEventPosts');
    // Route::get('/invite-guests', 'UserController@guestList');
    // Route::get('/eventguests', 'TimelineController@displayGuests');
    // Route::get('/add-eventmembers', 'UserController@getEventGuests');

    Route::get('/albums', 'TimelineController@allAlbums');
    Route::get('/photos', 'TimelineController@allPhotos');
    Route::get('/videos', 'TimelineController@allVideos');
    Route::get('/album/show/{id}', 'TimelineController@viewAlbum');

    // Route::get('/create-event', 'TimelineController@addEvent');
    // Route::post('/create-event', 'TimelineController@createEvent');

    // Route::get('/create-group', 'TimelineController@addGroup');
    // Route::post('/create-group', 'TimelineController@createGroupPage');

    // Route::get('/create-page', 'TimelineController@addPage');
    // Route::post('/create-page', 'TimelineController@createPage');

    Route::get('/subscribe', 'CheckoutController@subscribe');

});

Route::group(['prefix' => '/{username}', 'middleware' => ['auth', 'editown']], function ($username) {

    Route::get('/messages', 'UserController@messages');
    Route::get('/follow-requests', 'UserController@followRequests');

    Route::get('/pages-groups', 'TimelineController@pagesGroups');
    
    Route::get('/album/create', 'TimelineController@createAlbum');
    Route::post('/album/create', 'TimelineController@saveAlbum');

    Route::get('/album/{id}/edit', 'TimelineController@editAlbum');
    Route::post('/album/{id}/edit', 'TimelineController@updateAlbum');
    Route::get('/album/{album}/delete', 'TimelineController@deleteAlbum');

    Route::get('/album-preview/{id}/{photo_id}', 'TimelineController@addPreview');
    Route::get('/delete-media/{media}', 'TimelineController@deleteMedia');

    Route::post('/move-photos', 'UserController@movePhotos');
    Route::post('/delete-photos', 'UserController@deletePhotos');

    // Route::get('/pages', 'UserController@pages');
    // Route::get('/groups', 'UserController@groups');
    Route::get('/saved', 'UserController@savedItems');

});

/*
|--------------------------------------------------------------------------
| User settings routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => '/{username}/settings', 'middleware' => ['auth', 'editown']], function ($username) {
    Route::get('/general', 'UserController@userGeneralSettings');
    Route::post('/general', 'UserController@saveUserGeneralSettings');

    Route::get('/profile', 'UserController@userEditProfile');
    Route::post('/profile', 'UserController@saveProfile');

    Route::get('/privacy', 'UserController@userPrivacySettings');
    Route::post('/privacy', 'UserController@SaveUserPrivacySettings');

    Route::get('/wallpaper', 'UserController@wallpaperSettings');
    Route::post('/wallpaper', 'TimelineController@saveWallpaperSettings');
    Route::get('/toggle-wallpaper/{action}/{media}', 'TimelineController@toggleWallpaper');

    Route::get('/password', 'UserController@userPasswordSettings');
    Route::post('/password', 'UserController@saveNewPassword');

    Route::get('/affliates', 'UserController@affliates');
    Route::get('/login_sessions', 'UserController@loginSessions');

    Route::get('/deactivate', 'UserController@deactivate');
    Route::get('/deleteme', 'UserController@deleteMe');

    Route::get('/notifications', 'UserController@emailNotifications');
    Route::post('/notifications', 'UserController@updateEmailNotifications');
    
    Route::get('/addbank', 'UserController@addBank');
    Route::post('/addbank', 'UserController@addBank');
    
    Route::get('/addpayment', 'UserController@addPayment');
    Route::post('/addpayment', 'UserController@addPayment');

    Route::get('/save-payment-details', 'UserController@saveUserPaymentDetails');
    Route::post('/save-payment-details', 'UserController@saveUserPaymentDetails');

    Route::get('/save-bank-details', 'UserController@saveUserBankDetails');
    Route::post('/save-bank-details', 'UserController@saveUserBankDetails');

});


/*
|--------------------------------------------------------------------------
| Page settings routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => '/{username}/page-settings', 'middleware' => ['auth', 'editpage']], function ($username) {
    Route::get('/general', 'TimelineController@generalPageSettings');
    Route::post('/general', 'TimelineController@updateGeneralPageSettings');
    Route::get('/privacy', 'TimelineController@privacyPageSettings');
    Route::post('/privacy', 'TimelineController@updatePrivacyPageSettings');
    Route::get('/wallpaper', 'TimelineController@pageWallpaperSettings');
    Route::post('/wallpaper', 'TimelineController@saveWallpaperSettings');
    Route::get('/toggle-wallpaper/{action}/{media}', 'TimelineController@toggleWallpaper');
    Route::get('/roles', 'TimelineController@rolesPageSettings');
    Route::get('/likes', 'TimelineController@likesPageSettings');
});

/*
|--------------------------------------------------------------------------
| Group settings routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => '/{username}/group-settings', 'middleware' => ['auth', 'editgroup']], function ($username) {
    Route::get('/general', 'TimelineController@groupGeneralSettings');
    Route::post('/general', 'TimelineController@updateUserGroupSettings');
    // Route::get('/closegroup', 'TimelineController@groupGeneralSettings');
    // Route::get('/join-requests/{group_id}', 'TimelineController@getJoinRequests');
    Route::get('/wallpaper', 'TimelineController@groupWallpaperSettings');
    Route::post('/wallpaper', 'TimelineController@saveWallpaperSettings');
    Route::get('/toggle-wallpaper/{action}/{media}', 'TimelineController@toggleWallpaper');
});

/*
|--------------------------------------------------------------------------
| Event settings routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => '/{username}/event-settings', 'middleware' => ['auth','editevent']], function ($username) {
    Route::get('/general', 'TimelineController@generalEventSettings');
    Route::post('/general', 'TimelineController@updateUserEventSettings');
});

/*
|--------------------------------------------------------------------------
| Ajax Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for ajax.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::group(['prefix' => 'ajax', 'middleware' => ['auth']], function () {
    Route::post('create-post', 'TimelineController@createPost');

    Route::post('get-youtube-video', 'TimelineController@getYoutubeVideo');
    Route::post('like-post', 'TimelineController@likePost');
    Route::post('follow-post-free', 'TimelineController@followFreeUser');
    Route::post('unfollow-post-free', 'TimelineController@unfollowFreeUser');
    Route::post('unfollow-post/', 'TimelineController@unfollow');
    Route::post('notify-user', 'TimelineController@getNotifications');
    Route::post('post-comment', 'TimelineController@postComment');
    Route::post('page-like', 'TimelineController@pageLike');
    Route::post('change-avatar', 'TimelineController@changeAvatar');
    Route::post('change-cover', 'TimelineController@changeCover');
    Route::post('comment-like', 'TimelineController@likeComment');
    Route::post('comment-delete', 'TimelineController@deleteComment');
    Route::post('post-delete', 'TimelineController@deletePost');
    Route::post('page-delete', 'TimelineController@deletePage');
    Route::post('share-post', 'TimelineController@sharePost');
    Route::post('page-liked', 'TimelineController@pageLiked');
    // Route::post('get-soundcloud-results', 'TimelineController@getSoundCloudResults');
    // Route::post('join-group', 'TimelineController@joiningGroup');
    // Route::post('join-close-group', 'TimelineController@joiningClosedGroup');
    // Route::post('join-accept', 'TimelineController@acceptJoinRequest');
    // Route::post('join-reject', 'TimelineController@rejectJoinRequest');
    // Route::post('follow-accept', 'UserController@acceptFollowRequest');
    // Route::post('follow-reject', 'UserController@rejectFollowRequest');
    Route::get('get-more-posts', 'TimelineController@getMorePosts');
    Route::get('get-more-feed', 'TimelineController@showFeed');
    Route::get('get-global-feed', 'TimelineController@showGlobalFeed');
    // Route::post('add-memberGroup', 'UserController@addingMembersGroup');
    Route::post('get-users', 'UserController@getUsersJoin');
    Route::get('get-users-mentions', 'UserController@getUsersMentions');
    // Route::post('groupmember-remove', 'TimelineController@removeGroupMember');
    // Route::post('group-join', 'TimelineController@timelineGroups');
    Route::post('report-post', 'TimelineController@reportPost');
    // Route::post('follow-user-confirm', 'TimelineController@userFollowRequest');
    Route::post('post-message/{id}', 'MessageController@update');
    Route::post('create-message', 'MessageController@store');
    Route::post('page-report', 'TimelineController@pageReport');
    Route::post('get-notifications', 'UserController@getNotifications');
    Route::post('get-unread-notifications', 'UserController@getUnreadNotifications');
    Route::post('get-messages', 'MessageController@getMessages');
    Route::post('get-message/{id}', 'MessageController@getMessage');
    Route::post('get-conversation/{id}', 'MessageController@show');
    Route::post('get-private-conversation/{userId}', 'MessageController@getPrivateConversation');
    Route::post('get-unread-message', 'UserController@getUnreadMessage');
    Route::post('get-unread-messages', 'MessageController@getUnreadMessages');
    // Route::post('pagemember-remove', 'TimelineController@removePageMember');
    Route::post('get-users-modal', 'UserController@getUsersModal');
    Route::post('edit-post', 'TimelineController@editPost');
    Route::get('load-emoji', 'TimelineController@loadEmoji');
    Route::post('update-post', 'TimelineController@updatePost');
    Route::post('/mark-all-notifications', 'NotificationController@markAllRead');
    // Route::post('add-page-members', 'UserController@addingMembersPage');
    // Route::post('get-members-join', 'UserController@getMembersJoin');
    // Route::post('announce-delete', 'AdminController@removeAnnouncement');
    // Route::post('category-delete', 'AdminController@removeCategory');
    Route::post('get-members-invite', 'UserController@getMembersInvite');
    // Route::post('add-event-members', 'UserController@addingEventMembers');
    // Route::post('join-event', 'TimelineController@joiningEvent');
    // Route::post('event-delete', 'TimelineController@deleteEvent');
    Route::post('notification-delete', 'TimelineController@deleteNotification');
    Route::post('allnotifications-delete', 'TimelineController@deleteAllNotifications');
    Route::post('post-hide', 'TimelineController@hidePost');
    // Route::post('group-delete', 'TimelineController@deleteGroup');
    Route::post('media-edit', 'TimelineController@albumPhotoEdit');
    // Route::post('unjoinPage', 'TimelineController@unjoinPage');
    Route::post('save-timeline', 'TimelineController@saveTimeline');
    Route::post('save-post', 'TimelineController@savePost');
    Route::post('pin-post', 'TimelineController@pinPost');

});


/*
|--------------------------------------------------------------------------
| Image routes
|--------------------------------------------------------------------------
*/

Route::get('user/avatar/{filename}', function ($filename) {
    return Image::make(storage_path().'/uploads/users/avatars/'.$filename)->response();
});

Route::get('user/cover/{filename}', function ($filename) {
    return Image::make(storage_path().'/uploads/users/covers/'.$filename)->response();
});

Route::get('user/gallery/video/{filename}', function ($filename) {
    $fileContents = Storage::disk('uploads')->get("users/gallery/{$filename}");
    $response = Response::make($fileContents, 200);
    $response->header('Content-Type', 'video/mp4');

    return $response;
});

Route::get('user/gallery/{filename}', function ($filename) {
    return Image::make(storage_path().'/uploads/users/gallery/'.$filename)->response();
});


Route::get('page/avatar/{filename}', function ($filename) {
    return Image::make(storage_path().'/uploads/pages/avatars/'.$filename)->response();
});

Route::get('page/cover/{filename}', function ($filename) {
    return Image::make(storage_path().'/uploads/pages/covers/'.$filename)->response();
});

Route::get('group/avatar/{filename}', function ($filename) {
    return Image::make(storage_path().'/uploads/groups/avatars/'.$filename)->response();
});

Route::get('group/cover/{filename}', function ($filename) {
    return Image::make(storage_path().'/uploads/groups/covers/'.$filename)->response();
});

Route::get('setting/{filename}', function ($filename) {
    return Image::make(storage_path().'/uploads/settings/'.$filename)->response();
});

Route::get('event/cover/{filename}', function ($filename) {
    return Image::make(storage_path().'/uploads/events/covers/'.$filename)->response();
});

Route::get('event/avatar/{filename}', function ($filename) {
    return Image::make(storage_path().'/uploads/events/avatars/'.$filename)->response();
});

Route::get('/page/{pagename}', 'PageController@page');

Route::get('album/{filename}', function ($filename) {
    return Image::make(storage_path().'/uploads/albums/'.$filename)->response();
});

Route::get('wallpaper/{filename}', function ($filename) {
    return Image::make(storage_path().'/uploads/wallpapers/'.$filename)->response();
});

