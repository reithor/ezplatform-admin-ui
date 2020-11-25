<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables;

use EzSystems\Behat\Browser\Locator\CSSLocator;

class SubitemsGridList extends ItemsList
{
    public function clickListElement(string $name): void
    {
        $this->getHTMLPage()->findAll($this->getLocator('listElement'))->getByText($name)->click();
    }

    public function canBeSorted(): bool
    {
        return false;
    }

    public function verifyIsLoaded(): void
    {
    }

    protected function specifyLocators(): array
    {
        return [
            new CSSLocator('listElement', '.c-grid-view-item'),
        ];
    }
}
