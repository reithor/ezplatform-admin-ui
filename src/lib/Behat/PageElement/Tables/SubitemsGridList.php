<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables;

use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Locator\LocatorCollection;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Table\TableInterface;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Table\TableRow;

class SubitemsGridList extends Component implements TableInterface
{
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
            new VisibleCSSLocator('listElement', '.c-grid-view-item'),
        ];
    }

    public function isEmpty(): bool
    {
        return $this->getHTMLPage()->findAll($this->getLocator('listElement'))->any() === false;
    }

    public function hasElement(array $elementData): bool
    {
        $name = array_values($elementData);

        foreach ($this->getHTMLPage()->findAll($this->getLocator('listElement')) as $element)
        {
            if ($element->getText() === $name) {
                return true;
            }
        }

        return false;
    }

    public function hasElementOnCurrentPage(array $elementData): bool
    {
        $name = array_values($elementData);

        foreach ($this->getHTMLPage()->findAll($this->getLocator('listElement')) as $element)
        {
            if ($element->getText() === $name) {
                return true;
            }
        }

        return false;
    }

    public function getTableRow(array $elementData): ?TableRow
    {
        $name = array_values($elementData);

        $elements = $this->getHTMLPage()->findAll($this->getLocator('listElement'));

        foreach ($this->getHTMLPage()->findAll($this->getLocator('listElement')) as $element)
        {
            if ($element->getText() === $name) {
                return new TableRow($this->browser, $element, new LocatorCollection([]));
            }
        }

        return null;
    }
}
