<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\ExpiringSuscriptions;
use App\Filament\Widgets\IncomePerMonthChart;
use App\Filament\Widgets\IncomePerPlanChart;
use App\Filament\Widgets\PendingSuscriptions;
use App\Filament\Widgets\SuscriptionPerPlanChart;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Filament\Widgets\StatsOverviewWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->brandLogo(asset('images/logo-emarket.svg'))
            ->brandLogoHeight('4rem')
            ->favicon(asset('images/favicon.png'))
            // ->sidebarCollapsibleOnDesktop()
            ->sidebarFullyCollapsibleOnDesktop()
            ->collapsibleNavigationGroups(false)
            ->sidebarWidth('14rem')
            ->id('admin')
            ->path('admin')
            ->login()
            // ->registration()
            ->passwordReset()
            // ->emailVerification()
            // ->emailChangeVerification()
            // ->profile()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                // AccountWidget::class,
                StatsOverviewWidget::class,
                IncomePerMonthChart::class,
                IncomePerPlanChart::class,
                SuscriptionPerPlanChart::class,
                ExpiringSuscriptions::class,
                PendingSuscriptions::class
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
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->globalSearch(false)
            ->plugins([
                FilamentEditProfilePlugin::make()
                    ->setTitle('Mi perfil')
                    ->setNavigationGroup('Opciones')
                    ->setIcon('heroicon-o-user')
                    ->shouldShowAvatarForm(
                        value: true,
                        directory: 'avatars', // image will be stored in 'storage/app/public/avatars
                        rules: 'mimes:jpeg,png|max:1024' //only accept jpeg and png files with a maximum size of 1MB
                    )
                    ->shouldShowDeleteAccountForm(false)
                    
            ])
            ->userMenuItems([
                'profile' => Action::make('Edit')
                    ->label(fn() => auth()->user()->name)
                    ->url(fn (): string => EditProfilePage::getUrl())
                    ->icon('heroicon-m-user-circle')
                    ->visible(function (): bool {
                        return auth()->user()->exists();
                    }),
            ]);
    }
}
