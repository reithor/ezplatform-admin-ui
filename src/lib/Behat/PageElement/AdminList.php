<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement;

use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Context\OldBrowserContext;
use EzSystems\Behat\Browser\Element\NodeElement;
use EzSystems\Behat\Browser\Selector\CSSSelector;
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
        Assert::assertTrue($this->getHTMLPage()->find($this->getSelector('tableHeadline'))->isVisible());
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

    protected function specifySelectors(): array
    {
        return [
            new CSSSelector('paginationNextButton', '.ez-pagination a.page-link[rel="next"]'),
            new CSSSelector('tableHeadline', '.ez-table-header__headline'),
        ];
    }
}
