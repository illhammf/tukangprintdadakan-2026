<?php

namespace App\Providers\Filament;

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
use Filament\Pages;
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
            ->default()
            ->id('admin')
            ->path('admin')
            ->spa()
            ->login()
            ->passwordReset()
            ->defaultThemeMode(ThemeMode::Light)
            ->font('Montserrat')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->maxContentWidth(MaxWidth::SevenExtraLarge)
            ->sidebarCollapsibleOnDesktop()

            ->discoverResources(
                in: app_path('Filament/Admin/Resources'),
                for: 'App\\Filament\\Admin\\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/Admin/Pages'),
                for: 'App\\Filament\\Admin\\Pages'
            )
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverClusters(
                in: app_path('Filament/Admin/Clusters'),
                for: 'App\\Filament\\Admin\\Clusters'
            )

            ->widgets([
                SambutanDashboard::class,
                StatistikDashboard::class,
                RingkasanPendapatan::class,
                PengambilanBesok::class,
                PesananTerbaru::class,
                LayananTerlaris::class,
                LatestAccessLogs::class,
            ])

            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Master Data'),

                NavigationGroup::make()
                    ->label('Pemesanan'),

                NavigationGroup::make()
                    ->label('Website'),

                NavigationGroup::make()
                    ->label('Administration'),
            ])

            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label(fn (): string => Auth::user()?->name ?? 'Profil Saya')
                    ->url(fn (): string => EditProfilePage::getUrl())
                    ->icon('heroicon-m-user-circle'),
            ])

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
                    ->color('#29b'),

                AuthUIEnhancerPlugin::make()
                    ->showEmptyPanelOnMobile(false)
                    ->formPanelPosition('right')
                    ->formPanelWidth('40%')
                    ->emptyPanelBackgroundImageOpacity('70%')
                    ->emptyPanelBackgroundImageUrl('https://picsum.photos/seed/picsum/1260/750.webp/?blur=1'),

                LightSwitchPlugin::make()
                    ->position(Alignment::BottomCenter)
                    ->enabledOn([
                        'auth.login',
                        'auth.password',
                    ]),

                FilamentEditProfilePlugin::make()
                    ->slug('my-profile')
                    ->setTitle('My Profile')
                    ->shouldRegisterNavigation(false)
                    ->shouldShowDeleteAccountForm(false)
                    ->shouldShowSanctumTokens(false)
                    ->shouldShowBrowserSessionsForm()
                    ->shouldShowAvatarForm(),
            ])

            ->viteTheme('resources/css/filament/admin/theme.css')

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