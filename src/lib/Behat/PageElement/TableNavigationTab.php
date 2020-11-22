<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement;

use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Element\NodeElement;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use PHPUnit\Framework\Assert;

class TableNavigationTab extends Component
{
    public function getActiveTabName(): string
    {
        return $this->getHTMLPage()->find($this->getSelector('activeNavLink'))->getText();
    }

    public function goToTab(string $tabName): void
    {
        if ($tabName === $this->getActiveTabName()) {
            return;
        }

        $this->getHTMLPage()->findAll($this->getSelector('navLink'))
            ->filter(function(NodeElement $element) use ($tabName) {
                return strpos($element->getText(), $tabName) !== false;
            })
            ->single()->click();

    }

    public function verifyIsLoaded(): void
    {
        Assert::assertTrue($this->getHTMLPage()->find($this->getSelector('activeNavLink'))->isVisible());
    }

    protected function specifySelectors(): array
    {
        return [
            new CSSSelector('activeNavLink','.ez-tabs .active'),
            new CSSSelector('navLink','.ez-tabs .nav-link'),
        ];
    }
}
