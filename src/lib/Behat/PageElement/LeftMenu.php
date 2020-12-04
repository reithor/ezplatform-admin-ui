<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformAdminUi\Behat\PageElement;

use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
use PHPUnit\Framework\Assert;

class LeftMenu extends Component
{
    public function clickButton(string $buttonName): void
    {
        $this->getHTMLPage()->findAll($this->getLocator('buttonSelector'))->getByText($buttonName)->click();
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertTrue($this->getHTMLPage()->find($this->getLocator('menuSelector'))->isVisible());
    }

    public function getName(): string
    {
        return 'Left menu';
    }

    public function browse(): void
    {
        $this->clickButton('Browse');
    }

    protected function specifyLocators(): array
    {
        return [
            new VisibleCSSLocator('buttonSelector', '.ez-sticky-container .btn'),
            new VisibleCSSLocator('menuSelector', '.ez-side-menu'),
        ];
    }
}
