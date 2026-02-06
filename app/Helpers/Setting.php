<?php

namespace App\Helpers;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


use App\Models\{Option, ResourceCategory, ArticleCategory, PageModal};

class Setting {

    public static function info() {

        $setting = DB::table('settings')->first();
        $setting->menu = DB::table('menus')->where('is_active', 1)->first();
        return $setting;

	}

	public static function getFaviconLogo()
    {
        $settings = DB::table('settings')->where('id',1)->first();

        return asset("images/company-icon.ico");
        // return asset("storage/icons/{$settings->website_favicon}");
        // return $settings;
    }


    public static function get_company_logo_storage_path()
    {
        $settings = DB::table('settings')->where('id',1)->first();

        return asset("images/company-logo.png");
        // return asset("storage/logos/{$settings->company_logo}");
        // return $settings->company_logo;
    }

    public static function get_company_favicon_storage_path()
    {
        $settings = DB::table('settings')->where('id',1)->first();

        return asset("images/company-icon.ico");
        // return $settings->website_favicon;
    }

    public static function get_company_tel_no()
    {
        $settings = DB::table('settings')->where('id',1)->first();

        return $settings->tel_no;
    }

    public static function get_company_email()
    {
        $settings = DB::table('settings')->where('id',1)->first();

        return $settings->email;
    }

    public static function bannerTransition($id)
    {
        $transition = Option::find($id);

        return $transition->value;
    }

    public static function date_for_listing($date) {
        if ($date == null || trim($date) == '') {
            return "-";
        }
        else if ($date != null && strtotime($date) < strtotime('-1 day')) {
            return Carbon::parse($date)->isoFormat('lll');
        }

        return Carbon::parse($date)->diffForHumans();
	}

    public static function date_for_news_list($date) {
        if ($date != null && strtotime($date) > strtotime('-1 day')) {
            return Carbon::parse($date)->toFormattedDateString();
        } else {
            return date('F d, Y', strtotime($date));
        }
    }

    public static function resourceCategories()
    {
        $categories = ResourceCategory::where('parent_id', 0)->where('status','Active')->orderBy('name','asc')->get();
        
        return $categories;
    }


    public static function articleCategories()
    {
        $categories = ArticleCategory::orderBy('name','asc')->get();
        
        return $categories;
    }



    public function social($page,$account){
    	if($page == 'facebook')
    		return '
				jsSocials.shares.facebook = {
	                logo: "fa fa-facebook-f",
	                shareUrl: "https://facebook.com/'.$account.'",
	                getCount: function(data) {
	                    return data.count;
	                }
	            };
    		';
    	elseif($page == 'twitter')
    		return '
				jsSocials.shares.twitter = {
	                logo: "fa fa-twitter",
	                shareUrl: "https://twitter.com/'.$account.'",
	                getCount: function(data) {
	                    return data.count;
	                }
	            };
    		';
    	elseif($page == 'instagram')
    		return '
				jsSocials.shares.instagram = {
	                logo: "fa fa-instagram",
	                shareUrl: "https://instagram.com/'.$account.'",
	                getCount: function(data) {
	                    return data.count;
	                }
	            };
    		';
    	elseif($page == 'google')
    		return '
				jsSocials.shares.googleplus = {
	                logo: "fa fa-google-plus",
	                shareUrl: "https://plus.google.com/'.$account.'",
	                getCount: function(data) {
	                    return data.count;
	                }
	            };
    		';
    	elseif($page == 'dribble')
    		return '
				jsSocials.shares.dribbble = {
	                logo: "fa fa-dribbble",
	                shareUrl: "https://dribbble.com/'.$account.'",
	                getCount: function(data) {
	                    return data.count;
	                }
	            };
    		';
    }

    public static function modals($pageName)
    {
        $pages = PageModal::where('status', 'Active')->where('pages', 'like', '%'.$pageName.'%')->first();

        return $pages;
    }
}
