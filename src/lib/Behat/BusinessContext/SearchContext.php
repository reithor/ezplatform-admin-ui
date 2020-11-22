<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\BusinessContext;

use Behat\Behat\Context\Context;
use EzSystems\Behat\Browser\Factory\PageObjectFactory;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\SearchPage;

class SearchContext implements Context
{
    /**
     * @var SearchPage
     */
    private $searchPage;

    public function __construct(SearchPage $searchPage)
    {
        $this->searchPage = $searchPage;
    }

    /**
     * @When I search for a Content named :contentItemName
     */
    public function iSearchForContent(string $contentItemName): void
    {
        $this->searchPage->verifyIsLoaded();
        $this->searchPage->search($contentItemName);
    }

    /**
     * @Then I should see in search results an item named :contentItemName
     */
    public function searchResults(string $contentItemName): void
    {
        $this->searchPage->verifyItemInSearchResults($contentItemName);
    }
}
