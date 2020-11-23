<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables;

use EzSystems\Behat\Browser\Selector\CSSSelector;

class SubitemsGridList extends ItemsList
{
    public function clickListElement(string $name): void
    {
        $this->getHTMLPage()->findAll($this->getSelector('listElement'))->getByText($name)->click();
    }

    public function canBeSorted(): bool
    {
        return false;
    }

    public function verifyIsLoaded(): void
    {
    }

    protected function specifySelectors(): array
    {
        return [
            new CSSSelector('listElement', '.c-grid-view-item'),
        ];
    }
}
