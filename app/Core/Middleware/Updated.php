<?php

namespace Leantime\Core\Middleware;

use Closure;
use Leantime\Core\AppSettings;
use Leantime\Core\Eventhelpers;
use Leantime\Core\Frontcontroller;
use Leantime\Core\IncomingRequest;
use Leantime\Domain\Setting\Repositories\Setting as SettingRepository;
use Symfony\Component\HttpFoundation\Response;

class Updated
{
    use Eventhelpers;

    /**
     * Check if Leantime is installed
     *
     * @param \Closure(IncomingRequest): Response $next
     * @throws BindingResolutionException
     **/
    public function handle(IncomingRequest $request, Closure $next): Response
    {
        $dbVersion = $_SESSION['dbVersion'] ?? app()->make(SettingRepository::class)->getSetting('db-version');
        $settingsDbVersion = app()->make(AppSettings::class)->dbVersion;

        if ($dbVersion !== false) {
            //Setting dbVersion only if there is one in the db
            //Otherwise leave dbVersion unset so we can recheck every time the settings db returns false.
            $_SESSION['dbVersion'] = $dbVersion;
        }

        $_SESSION['isUpdated'] = $dbVersion == $settingsDbVersion;

        self::dispatch_event('system_update', ['dbVersion' => $dbVersion, 'settingsDbVersion' => $settingsDbVersion]);

        if ($_SESSION['isUpdated']) {
            return $next($request);
        }

        if (! $response = $this->redirectToUpdate()) {
            return $next($request);
        }

        return $response;
    }

    /**
     * Redirect to update
     *
     * @return Response|false
     * @throws BindingResolutionException
     */
    private function redirectToUpdate(): Response|false
    {
        $frontController = app()->make(Frontcontroller::class);

        if (in_array($frontController::getCurrentRoute(), ['install.update', 'install', 'api.i18n'])) {
            return false;
        }

        return $frontController::redirect(BASE_URL . '/install/update');
    }
}
