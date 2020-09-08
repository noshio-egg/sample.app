<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{

	/**
	 * The policy mappings for the application.
	 *
	 * @var array
	 */
	protected $policies = [ // 'App\Model' => 'App\Policies\ModelPolicy',
	];

	/**
	 * Register any authentication / authorization services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->registerPolicies();

		/**
		 * 開発者ロール
		 */
		Gate::define('developer', function ($user) {
			return ($user->role == 1);
		});

		/**
		 * システム管理者ロール
		 */
		Gate::define('admin', function ($user) {
			return ($user->role > 0 && $user->role <= 2);
		});

		/**
		 * 一般ユーザロール
		 */
		Gate::define('user', function ($user) {
			return ($user->role > 0 && $user->role <= 10);
		});
	}
}
