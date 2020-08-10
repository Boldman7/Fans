<?php

namespace App\Http\Controllers;

// use Guzzle\Service\Client;
use Illuminate\Http\Request;
use Teepluss\Theme\Facades\Theme;
use App\Setting;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->checkCensored();

        $this->middleware('auth');
        
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
                  'email'         => 'email',
                ],$messages);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('guest');
        // $theme->setTitle(Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));
        $theme->setTitle(Setting::get('site_name'));
        return $theme->scope('home')->render();
    }

    public function getLocation(Request $request)
    {
        $location = str_replace(' ', '+', $request->location);

        $map_url = 'http://www.google.com/maps/place/'.$location;

        return redirect($map_url);
    }

    public function faq(Request $request)
    {
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));
        return $theme->scope('faq')->render();
    }

    public function support(Request $request)
    {
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));
        return $theme->scope('support')->render();
    }

    public function termsOfUse()
    {
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));
        return $theme->scope('termsOfUse')->render();
    }

    public function privacyPolicy()
    {
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));
        return $theme->scope('privacyPolicy')->render();
    }
}
