<?php

namespace App\Providers;

use App\Models\Items\Category;
use App\Models\Items\Inspections\ItemInspection;
use App\Models\Items\Inspections\ItemTemplate;
use App\Models\Items\Item;
use App\Policies\Items\CategoryPolicy;
use App\Policies\Items\Inspections\ItemInspectionPolicy;
use App\Policies\Items\Inspections\ItemTemplatePolicy;
use App\Policies\Items\ItemPolicy;
use App\Policies\MediaPolicy;
use Awcodes\Curator\Models\Media;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Item::class, ItemPolicy::class);
        Gate::policy(ItemInspection::class, ItemInspectionPolicy::class);
        Gate::policy(ItemTemplate::class, ItemTemplatePolicy::class);
        Gate::policy(Media::class, MediaPolicy::class);
    }
}
