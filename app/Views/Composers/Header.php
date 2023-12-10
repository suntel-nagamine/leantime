<?php

namespace Leantime\Views\Composers;

use Leantime\Core\AppSettings;
use Leantime\Core\Composer;
use Leantime\Core\Environment;
use Leantime\Core\Theme;
use Leantime\Domain\Setting\Repositories\Setting;

/**
 *
 */
class Header extends Composer
{
    public static array $views = [
        'global::sections.header',
    ];

    private Setting $SettingsRepo;
    private Environment $config;
    private AppSettings $AppSettings;
    private Theme $ThemeCore;
    private Theme $themeCore;
    private AppSettings $appSettings;
    private Setting $settingsRepo;

    /**
     * @param Setting     $settingsRepo
     * @param Environment $config
     * @param AppSettings $appSettings
     * @param Theme       $themeCore
     * @return void
     */
    public function init(
        Setting $settingsRepo,
        Environment $config,
        AppSettings $appSettings,
        Theme $themeCore
    ): void {
        $this->settingsRepo = $settingsRepo;
        $this->config = $config;
        $this->appSettings = $appSettings;
        $this->themeCore = $themeCore;
    }

    /**
     * @return array
     */
    /**
     * @return array
     */
    public function with(): array
    {
        $theme = $this->themeCore->getActive();
        $colorMode = $this->themeCore->getColorMode();
        $colorScheme = $this->themeCore->getColorScheme();
        $themeFont = $this->themeCore->getFont();

        // Set colors to use
        if (! isset($_SESSION["companysettings.sitename"])) {
            $sitename = $this->settingsRepo->getSetting("companysettings.sitename");
            if ($sitename !== false) {
                $_SESSION["companysettings.sitename"] = $sitename;
            } else {
                $_SESSION["companysettings.sitename"] = $this->config->sitename;
            }
        }

        if (isset($_SESSION['userdata']) && isset($_SESSION['userdata']['id'])) {
            if (isset($_SESSION['usersettings.timezone'])) {
                date_default_timezone_set($_SESSION['usersettings.timezone']);
            }
        }

        return [
            'sitename' => $_SESSION['companysettings.sitename'] ?? '',
            'primaryColor' => $_SESSION['usersettings.colorScheme.primaryColor'] ?? '',
            'theme' => $theme,
            'version' => $this->appSettings->appVersion ?? '',
            'themeScripts' => [
                $this->themeCore->getJsUrl() ?? '',
                $this->themeCore->getCustomJsUrl() ?? '',
            ],
            'themeColorMode' => $colorMode,
            'themeColorScheme' => $colorScheme,
            'themeFont' => $themeFont,
            'themeStyles' => [
                [
                    'id' => 'themeStyleSheet',
                    'url' => $this->themeCore->getStyleUrl() ?? '',
                ],
                [
                    'url' => $this->themeCore->getCustomStyleUrl() ?? '',
                ],
            ],
            'accents' => [
                $_SESSION['usersettings.colorScheme.primaryColor'] ?? "",
                $_SESSION['usersettings.colorScheme.secondaryColor'] ?? "",
            ],
        ];
    }
}
