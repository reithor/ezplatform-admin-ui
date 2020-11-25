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
        return $this->getHTMLPage()->findAll($this->getLocator('nextButton'))->any();
    }

    public function clickNextButton(): void
    {
        $this->getHTMLPage()->find($this->getLocator('nextButton'))->click();
//        $this->context->waitUntilElementDisappears($this->fields['spinner'], 10);
    }

    public function verifyIsLoaded(): void
    {

    }

    protected function specifyLocators(): array
    {
        return [
            new VisibleCSSLocator('nextButton','.pagination .page-item.next:not(.disabled)'),
            new VisibleCSSLocator('spinner','.m-sub-items__spinner-wrapper'),
        ];
    }
}
