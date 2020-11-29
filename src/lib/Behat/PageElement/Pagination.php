<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement;

use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Context\OldBrowserContext;
use EzSystems\Behat\Browser\Element\Element;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;

class Pagination extends Component
{
    public function isNextButtonActive(): bool
    {
        return $this->getHTMLPage()->setTimeout(0)->findAll($this->getLocator('nextButton'))->any();
    }

    public function clickNextButton(): void
    {
        $this->getHTMLPage()->find($this->getLocator('nextButton'))->click();
    }

    public function verifyIsLoaded(): void
    {

    }

    protected function specifyLocators(): array
    {
        return [
            new VisibleCSSLocator('nextButton','.pagination .page-item.next:not(.disabled)'),
        ];
    }
}
