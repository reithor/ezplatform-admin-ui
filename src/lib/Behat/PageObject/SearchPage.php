<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use EzSystems\Behat\Browser\Context\OldBrowserContext;
use EzSystems\Behat\Browser\Factory\ElementFactory;
use EzSystems\Behat\Browser\Page\Page;
use EzSystems\Behat\Browser\Locator\CSSLocator;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables\DashboardTable;
use PHPUnit\Framework\Assert;

class SearchPage extends Page
{
    public function search(string $contentItemName): void
    {
        $this->getHTMLPage()->find($this->getLocator('inputField'))->setValue($contentItemName);
        $this->getHTMLPage()->find($this->getLocator('buttonSearch'))->click();
    }

    public function verifyItemInSearchResults($contentItemName): void
    {
        // powrot do tabelek...
        $table = ElementFactory::createElement($this->context, DashboardTable::ELEMENT_NAME, '.container');
        Assert::assertTrue($table->isElementInTable($contentItemName));
    }

    protected function getRoute(): string
    {
        return '/search';
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertEquals(
            'Search',
            $this->getHTMLPage()->find($this->getLocator('pageTitle'))->getText()
        );
    }

    public function getName(): string
    {
        return 'Search';
    }

    protected function specifyLocators(): array
    {
        return [
            new CSSLocator('inputField', '.ez-search-form #search_query'),
            new CSSLocator('buttonSearch', '.ez-btn--search'),
            new CSSLocator('pageTitle', '.ez-page-title .ez-page-title__content-name'),
        ];
    }
}
