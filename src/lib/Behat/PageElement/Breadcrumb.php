<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement;

use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Element\Element;
use EzSystems\Behat\Browser\Locator\CSSLocator;
use PHPUnit\Framework\Assert;

/** Element that describes breadcrumb */
class Breadcrumb extends Component
{
    public function clickBreadcrumbItem(string $itemName): void
    {
        $this->getHTMLPage()->findAll($this->getLocator('breadcrumbItemLink'))->getByText($itemName)->click();
    }

    public function getActiveName(): string
    {
        return $this->getHTMLPage()->find($this->getLocator('activeBreadcrumb'))->getText();
    }

    public function getBreadcrumb(): string
    {
        return $this->getHTMLPage()->find($this->getLocator('breadcrumb'))->getText();
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertTrue($this->getHTMLPage()->find($this->getLocator('breadcrumbItem'))->isVisible());
    }

    public function getName(): string
    {
        return 'Breadcrumb';
    }

    protected function specifyLocators(): array
    {
        return [
            new CSSLocator('breadcrumb', '.breadcrumb'),
            new CSSLocator('breadcrumbItem', '.breadcrumb-item'),
            new CSSLocator('breadcrumbItemLink', '.breadcrumb-item a'),
            new CSSLocator('activeBreadcrumb', '.breadcrumb-item.active'),
        ];
    }
}
