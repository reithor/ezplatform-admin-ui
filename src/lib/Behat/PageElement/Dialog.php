<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement;

use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Locator\CSSLocator;
use PHPUnit\Framework\Assert;

class Dialog extends Component
{
    public function confirm(): void
    {
        $this->getHTMLPage()->find($this->getLocator('confirm'))->click();
    }

    public function decline(): void
    {
        $this->getHTMLPage()->find($this->getLocator('decline'))->click();
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertTrue($this->getHTMLPage()->find($this->getLocator('confirm'))->isVisible());
        Assert::assertTrue($this->getHTMLPage()->find($this->getLocator('decline'))->isVisible());
    }

    protected function specifyLocators(): array
    {
        return [
            new CSSLocator('confirm','.modal.show button[type="submit"],.modal.show button[data-click]'),
            new CSSLocator('decline', '.modal.show .btn-secondary'),
        ];
    }
}
