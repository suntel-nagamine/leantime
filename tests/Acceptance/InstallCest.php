<?php

namespace Acceptance;

use Codeception\Attribute\Depends;
use Tests\Support\AcceptanceTester;
use Tests\Support\Page\Acceptance\Install;

class InstallCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function installPageWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/install');
        echo $I->grabPageSource();
        $I->waitForElementVisible('.registrationForm', 120);

        $I->see('Install');
    }

    #[Depends('installPageWorks')]
    public function createDBSuccessfully(AcceptanceTester $I, Install $installPage)
    {
        $installPage->install(
            'test@leantime.io',
            'test',
            'John',
            'Smith',
            'Smith & Co'
        );
    }
}
