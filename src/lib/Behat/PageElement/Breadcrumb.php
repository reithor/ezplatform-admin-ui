<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformAdminUi\Behat\PageElement;

use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Element\Element;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
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
            new VisibleCSSLocator('breadcrumb', '.breadcrumb'),
            new VisibleCSSLocator('breadcrumbItem', '.breadcrumb-item'),
            new VisibleCSSLocator('breadcrumbItemLink', '.breadcrumb-item a'),
            new VisibleCSSLocator('activeBreadcrumb', '.breadcrumb-item.active'),
        ];
    }
}
