<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement;

use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Element\NodeElement;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables\Table;
use PHPUnit\Framework\Assert;

class AdminList extends Component
{
    /** @var string list table title placed in the blue bar */
    protected $listHeader;

    /** @var Table */
    public $table;

    public function verifyIsLoaded(): void
    {
        Assert::assertTrue($this->getHTMLPage()->find($this->getLocator('tableHeadline'))->isVisible());
    }

    public function getName(): string
    {
        return 'Admin List';
    }

    public function editItem(array $parameters)
    {
    }

    public function selectItem(array $parameters): NodeElement
    {

    }

    public function clickItem(array $parameters)
    {

    }

    public function isEmpty()
    {
    }

    public function delete(string $contentTypeName)
    {
    }

    protected function specifyLocators(): array
    {
        return [
            new VisibleCSSLocator('paginationNextButton', '.ez-pagination a.page-link[rel="next"]'),
            new VisibleCSSLocator('tableHeadline', '.ez-table-header__headline'),
        ];
    }
}
