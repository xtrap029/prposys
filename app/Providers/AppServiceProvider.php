<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        config(['global.site_icon' => '/storage/public/images/site settings/'.\App\Settings::where('type', 'SITE_LOGO')->first()->value]);
        config(['global.site_color' => \App\Settings::where('type', 'SITE_COLOR')->first()->value]);

        config(['global.site_icon_people' => '/storage/public/images/site settings/'.\App\Settings::where('type', 'SITE_LOGO_PEOPLE')->first()->value]);
        config(['global.site_color_people' => \App\Settings::where('type', 'SITE_COLOR_PEOPLE')->first()->value]);

        config(['global.site_icon_leaves' => '/storage/public/images/site settings/'.\App\Settings::where('type', 'SITE_LOGO_LEAVES')->first()->value]);
        config(['global.site_color_leaves' => \App\Settings::where('type', 'SITE_COLOR_LEAVES')->first()->value]);

        config(['global.site_icon_resources' => '/storage/public/images/site settings/'.\App\Settings::where('type', 'SITE_LOGO_RESOURCES')->first()->value]);
        config(['global.site_color_resources' => \App\Settings::where('type', 'SITE_COLOR_RESOURCES')->first()->value]);

        config(['global.site_icon_travels' => '/storage/public/images/site settings/'.\App\Settings::where('type', 'SITE_LOGO_TRAVELS')->first()->value]);
        config(['global.site_color_travels' => \App\Settings::where('type', 'SITE_COLOR_TRAVELS')->first()->value]);

        config(['global.site_icon_logout' => '/storage/public/images/site settings/'.\App\Settings::where('type', 'SITE_LOGO_LOGOUT')->first()->value]);

        config(['global.site_banner_login' => '/storage/public/images/site settings/'.\App\Settings::where('type', 'SITE_BANNER_LOGIN')->first()->value]);
        config(['global.site_banner_home' => '/storage/public/images/site settings/'.\App\Settings::where('type', 'SITE_BANNER_HOME')->first()->value]);

        config(['global.site_banner_login_driveid' => \App\Settings::where('type', 'SITE_BANNER_LOGIN_DRIVEID')->first()->value]);
        config(['global.site_banner_home_driveid' => \App\Settings::where('type', 'SITE_BANNER_HOME_DRIVEID')->first()->value]);
        
        config(['global.site_dashboard_slider' => preg_split("/\\r\\n|\\r|\\n/",\App\Settings::where('type', 'SITE_DASHBOARD_SLIDER')->first()->value)]);
        config(['global.site_login_greeting' => preg_split("/\\r\\n|\\r|\\n/",\App\Settings::where('type', 'SITE_LOGIN_GREETING')->first()->value)]);

        config(['global.site_app_externals' => \App\AppExternal::orderBy('id', 'asc')->get()]);

        config(['global.max_t_file' => (\App\Settings::where('type', 'MAX_T_FILE')->first()->value/1024).'mb']);
        config(['global.max_tf_reimbursement' => (\App\Settings::where('type', 'MAX_TF_REIMBURSEMENT')->first()->value/1024).'mb']);
        config(['global.max_tf_issue' => (\App\Settings::where('type', 'MAX_TF_ISSUE')->first()->value/1024).'mb']);
        config(['global.max_tl_file' => (\App\Settings::where('type', 'MAX_TL_FILE')->first()->value/1024).'mb']);
        config(['global.max_tl_deposit' => (\App\Settings::where('type', 'MAX_TL_DEPOSIT')->first()->value/1024).'mb']);
        config(['global.max_vendor' => (\App\Settings::where('type', 'MAX_VENDOR')->first()->value/1024).'mb']);
    }
}
