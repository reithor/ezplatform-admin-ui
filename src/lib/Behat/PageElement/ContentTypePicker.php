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

class ContentTypePicker extends Component
{
    public function select(string $contentTypeName): void
    {
        $this->getHTMLPage()->find($this->getLocator('filterInput'))->setValue($contentTypeName);
        $this->getHTMLPage()->setTimeout(5)->findAll($this->getLocator('filteredItem'))->getByText($contentTypeName)->click();
    }

    public function verifyIsLoaded(): void
    {
        $headerText = $this->getHTMLPage()->find($this->getLocator('headerSelector'))->getText();
        Assert::assertEquals('Create content', $headerText);
        $this->getHTMLPage()->find($this->getLocator('filterInput'))->clear();
    }

    public function getName(): string
    {
        return 'Content Type picker';
    }

    protected function specifyLocators(): array
    {
        return [
            new VisibleCSSLocator('filterInput', '.ez-extra-actions__section-content--content-type .ez-instant-filter__input'),
            new VisibleCSSLocator('filteredItem', '.ez-extra-actions__section-content--content-type .ez-instant-filter__group-item:not([hidden])'),
            new VisibleCSSLocator('headerSelector', '.ez-extra-actions--create .ez-extra-actions__header'),
        ];
    }
}
