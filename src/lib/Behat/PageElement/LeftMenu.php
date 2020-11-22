<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement;

use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use PHPUnit\Framework\Assert;

class LeftMenu extends Component
{
    public function clickButton(string $buttonName): void
    {
        $this->getHTMLPage()->findAll($this->getSelector('buttonSelector'))->getByText($buttonName)->click();
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertTrue($this->getHTMLPage()->find($this->getSelector('menuSelector'))->isVisible());
    }

    public function getName(): string
    {
        return 'Left menu';
    }

    protected function specifySelectors(): array
    {
        return [
            new CSSSelector('buttonSelector', '.ez-sticky-container .btn'),
            new CSSSelector('menuSelector', '.ez-side-menu'),
        ];
    }
}
