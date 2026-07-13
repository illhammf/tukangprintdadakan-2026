<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Pages\Dashboard;
use App\Filament\Admin\Widgets\LatestAccessLogs;
use App\Filament\Admin\Widgets\LayananTerlaris;
use App\Filament\Admin\Widgets\PengambilanBesok;
use App\Filament\Admin\Widgets\PesananTerbaru;
use App\Filament\Admin\Widgets\RingkasanPendapatan;
use App\Filament\Admin\Widgets\SambutanDashboard;
use App\Filament\Admin\Widgets\StatistikDashboard;
use Awcodes\LightSwitch\Enums\Alignment;
use Awcodes\LightSwitch\LightSwitchPlugin;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use DiogoGPinto\AuthUIEnhancer\AuthUIEnhancerPlugin;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Hasnayeen\Themes\Http\Middleware\SetTheme;
use Hasnayeen\Themes\ThemesPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;
use Njxqlus\FilamentProgressbar\FilamentProgressbarPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            /*
            |--------------------------------------------------------------------------
            | Identitas Panel
            |--------------------------------------------------------------------------
            */

            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName('Tukang Print Dadakan')
            ->font('Montserrat')

            /*
            |--------------------------------------------------------------------------
            | Autentikasi
            |--------------------------------------------------------------------------
            */

            ->login()
            ->passwordReset()

            /*
            |--------------------------------------------------------------------------
            | Tampilan Panel
            |--------------------------------------------------------------------------
            */

            ->spa()
            ->defaultThemeMode(ThemeMode::Light)
            ->colors([
                'primary' => Color::Amber,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
                'danger' => Color::Rose,
                'info' => Color::Sky,
                'gray' => Color::Slate,
            ])
            ->maxContentWidth(MaxWidth::SevenExtraLarge)
            ->sidebarCollapsibleOnDesktop()
            ->unsavedChangesAlerts()

            /*
            |--------------------------------------------------------------------------
            | Resources, Pages, dan Clusters
            |--------------------------------------------------------------------------
            */

            ->discoverResources(
                in: app_path('Filament/Admin/Resources'),
                for: 'App\\Filament\\Admin\\Resources',
            )
            ->discoverPages(
                in: app_path('Filament/Admin/Pages'),
                for: 'App\\Filament\\Admin\\Pages',
            )
            ->pages([
                Dashboard::class,
            ])
            ->discoverClusters(
                in: app_path('Filament/Admin/Clusters'),
                for: 'App\\Filament\\Admin\\Clusters',
            )

            /*
            |--------------------------------------------------------------------------
            | Widget Dashboard
            |--------------------------------------------------------------------------
            */

            ->widgets([
                SambutanDashboard::class,
                StatistikDashboard::class,
                RingkasanPendapatan::class,
                PengambilanBesok::class,
                PesananTerbaru::class,
                LayananTerlaris::class,
                LatestAccessLogs::class,
            ])

            /*
            |--------------------------------------------------------------------------
            | Grup Navigasi
            |--------------------------------------------------------------------------
            */

            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Master Data')
                    ->icon('heroicon-o-circle-stack'),

                NavigationGroup::make()
                    ->label('Pemesanan dan Transaksi')
                    ->icon('heroicon-o-shopping-bag'),

                NavigationGroup::make()
                    ->label('Website dan Operasional')
                    ->icon('heroicon-o-globe-alt'),

                NavigationGroup::make()
                    ->label('Administrasi Sistem')
                    ->icon('heroicon-o-shield-check'),
            ])

            /*
            |--------------------------------------------------------------------------
            | Menu Pengguna
            |--------------------------------------------------------------------------
            */

            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label(
                        fn (): string => Auth::user()?->name
                            ? 'Profil ' . Auth::user()->name
                            : 'Profil Saya'
                    )
                    ->url(
                        fn (): string => EditProfilePage::getUrl()
                    )
                    ->icon('heroicon-m-user-circle'),

                'website' => MenuItem::make()
                    ->label('Buka Website')
                    ->url(
                        fn (): string => route('home')
                    )
                    ->icon('heroicon-m-globe-alt')
                    ->openUrlInNewTab(),
            ])

            /*
            |--------------------------------------------------------------------------
            | Plugin
            |--------------------------------------------------------------------------
            */

            ->plugins([
                FilamentShieldPlugin::make()
                    ->gridColumns([
                        'default' => 2,
                        'lg' => 3,
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 2,
                        'lg' => 3,
                    ])
                    ->resourceCheckboxListColumns([
                        'default' => 2,
                        'lg' => 3,
                    ]),

                ThemesPlugin::make(),

                FilamentProgressbarPlugin::make()
                    ->color('#f59e0b'),

                AuthUIEnhancerPlugin::make()
                    ->showEmptyPanelOnMobile(false)
                    ->formPanelPosition('right')
                    ->formPanelWidth('42%')
                    ->emptyPanelBackgroundImageOpacity('82%')
                    ->emptyPanelBackgroundImageUrl(
                        asset('images/paneladmin.png')
                    ),

                LightSwitchPlugin::make()
                    ->position(Alignment::BottomCenter)
                    ->enabledOn([
                        'auth.login',
                        'auth.password',
                    ]),

                FilamentEditProfilePlugin::make()
                    ->slug('profil-saya')
                    ->setTitle('Profil Saya')
                    ->shouldRegisterNavigation(false)
                    ->shouldShowDeleteAccountForm(false)
                    ->shouldShowSanctumTokens(false)
                    ->shouldShowBrowserSessionsForm()
                    ->shouldShowAvatarForm(),
            ])

            /*
            |--------------------------------------------------------------------------
            | Custom Theme
            |--------------------------------------------------------------------------
            */

            ->viteTheme('resources/css/filament/admin/theme.css')

            /*
            |--------------------------------------------------------------------------
            | Middleware
            |--------------------------------------------------------------------------
            */

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
                SetTheme::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}