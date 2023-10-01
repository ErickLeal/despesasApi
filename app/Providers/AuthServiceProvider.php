<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('expense-user', function (User $user, Expense $expense) {
            return $user->id === $expense->user_id;
        });
    }
}
