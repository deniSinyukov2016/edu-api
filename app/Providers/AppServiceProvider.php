<?php

namespace App\Providers;

use App\Http\ViewComposers\CategoriesComposer;
use App\Models\Category;
use Illuminate\Support\ServiceProvider;
use App\Http\Requests\Request;

class AppServiceProvider extends ServiceProvider

{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('includes.categories-list', CategoriesComposer::class);
        view()->composer('includes.categories-list-mobile',CategoriesComposer::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
            $this->app->register(\Despark\Apidoc\ApiDocServiceProvider::class);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $hashed_password = $this->user()->password;

        return [
            'oldPassword' => "password_hash:$hashed_password|string|min:5",
            'newPassword' => 'required_with:oldPassword|confirmed|min:6',
        ];
    }
}
