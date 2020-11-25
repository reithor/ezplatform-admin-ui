<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement;

use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Element\NodeElement;
use EzSystems\Behat\Browser\Locator\CSSLocator;
use PHPUnit\Framework\Assert;

class TableNavigationTab extends Component
{
    public function getActiveTabName(): string
    {
        return $this->getHTMLPage()->find($this->getLocator('activeNavLink'))->getText();
    }

    public function goToTab(string $tabName): void
    {
        if ($tabName === $this->getActiveTabName()) {
            return;
        }

        $this->getHTMLPage()->findAll($this->getLocator('navLink'))
            ->filter(function(NodeElement $element) use ($tabName) {
                return strpos($element->getText(), $tabName) !== false;
            })
            ->single()->click();

    }

    public function verifyIsLoaded(): void
    {
        Assert::assertTrue($this->getHTMLPage()->find($this->getLocator('activeNavLink'))->isVisible());
    }

    protected function specifyLocators(): array
    {
        return [
            new CSSLocator('activeNavLink','.ez-tabs .active'),
            new CSSLocator('navLink','.ez-tabs .nav-link'),
        ];
    }
}
