<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformAdminUi\Behat\PageElement;

use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Locator\CSSLocator;
use PHPUnit\Framework\Assert;

class RightMenu extends Component
{
    public function clickButton(string $buttonName): void
    {
        $this->getHTMLPage()
            ->findAll($this->getLocator('menuButton'))
            ->assert()->hasElements()
            ->getByText($buttonName)
            ->click();
    }

    public function isButtonActive(string $buttonName): bool
    {
        return $this->getHTMLPage()->findAll($this->getLocator('menuButton'))->getByText($buttonName)->hasAttribute('disabled');
    }

    public function isButtonVisible(string $buttonName): bool
    {
        return $this->getHTMLPage()->findAll($this->getLocator('menuButton'))->getByText($buttonName)->any();
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertTrue($this->getHTMLPage()->find($this->getLocator('menuButton'))->isVisible());
    }

    protected function specifyLocators(): array
    {
        return [
             new CSSLocator('menuButton', '.ez-context-menu .btn'),
        ];
    }
}
