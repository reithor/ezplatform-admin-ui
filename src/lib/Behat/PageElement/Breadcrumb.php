<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement;

use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Element\Element;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use PHPUnit\Framework\Assert;

/** Element that describes breadcrumb */
class Breadcrumb extends Component
{
    public function clickBreadcrumbItem(string $itemName): void
    {
        $this->getHTMLPage()->findAll($this->getSelector('breadcrumbItemLink'))->getByText($itemName)->click();
    }

    public function getActiveName(): string
    {
        return $this->getHTMLPage()->find($this->getSelector('activeBreadcrumb'))->getText();
    }

    public function getBreadcrumb(): string
    {
        return $this->getHTMLPage()->find($this->getSelector('breadcrumb'))->getText();
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertTrue($this->getHTMLPage()->find($this->getSelector('breadcrumbItem'))->isVisible());
    }

    public function getName(): string
    {
        return 'Breadcrumb';
    }

    protected function specifySelectors(): array
    {
        return [
            new CSSSelector('breadcrumb', '.breadcrumb'),
            new CSSSelector('breadcrumbItem', '.breadcrumb-item'),
            new CSSSelector('breadcrumbItemLink', '.breadcrumb-item a'),
            new CSSSelector('activeBreadcrumb', '.breadcrumb-item.active'),
        ];
    }
}
