<?php

namespace App\Providers\Filament;

use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use App\Filament\Resources\MediaResource;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                // 'primary' => Color::Amber,
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                \Awcodes\Curator\CuratorPlugin::make()
                    // ->label('Media')
                    // ->pluralLabel('Media')
                    // ->navigationIcon('heroicon-o-photo')
                    // ->navigationGroup('Content')
                    // ->navigationSort(3)
                    // ->navigationCountBadge()
                    // ->registerNavigation(false)
                    // ->defaultListView('grid' || 'list')
                    ->resource(MediaResource::class),
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                \TomatoPHP\FilamentUsers\FilamentUsersPlugin::make(),

            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->navigationGroups([
                NavigationGroup::make()
                    ->label(__('Items'))
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(__('Resources'))
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(__('User Management'))
                    ->collapsed(),
            ])
            ->topNavigation()
            ->databaseNotifications();
    }
}
