<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement;

use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use PHPUnit\Framework\Assert;

class Dialog extends Component
{
    public function confirm(): void
    {
        $this->getHTMLPage()->find($this->getSelector('confirm'))->click();
    }

    public function decline(): void
    {
        $this->getHTMLPage()->find($this->getSelector('decline'))->click();
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertTrue($this->getHTMLPage()->find($this->getSelector('confirm'))->isVisible());
        Assert::assertTrue($this->getHTMLPage()->find($this->getSelector('decline'))->isVisible());
    }

    protected function specifySelectors(): array
    {
        return [
            new CSSSelector('confirm','.modal.show button[type="submit"],.modal.show button[data-click]'),
            new CSSSelector('decline', '.modal.show .btn-secondary'),
        ];
    }
}
