<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement;

use Behat\Mink\Session;
use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Context\OldBrowserContext;
use EzSystems\Behat\Browser\Factory\ElementFactory;
use EzSystems\Behat\Browser\Element\Element;
use EzSystems\Behat\Browser\Page\Browser;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables\SubitemsGridList;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables\SubItemsTable;
use PHPUnit\Framework\Assert;

class SubitemsList extends Component
{
    /** @var SubItemsTable */
    protected $table;

    protected $isGridViewEnabled;

    /** @var SubitemsGridList */
    protected $gridList;

    public function __construct(Browser $browser, SubitemsGridList $gridList, SubItemsTable $table)
    {
        parent::__construct($browser);
        $this->table = $table;
        $this->gridList = $gridList;
    }

    public function sortBy(string $columnName, bool $ascending): void
    {
        $this->context->getElementByText($columnName, $this->fields['horizontalHeaders'])->click();

        $isSortedAscending = $this->context->isElementVisible(sprintf('%s%s', $this->fields['horizontalHeaders'], $this->fields['sortingOrderAscending']));

        if ($ascending !== $isSortedAscending) {
            $this->context->getElementByText($columnName, $this->fields['horizontalHeaders'])->click();
        }

        $verificationSelector = $ascending ?
            sprintf('%s%s', $this->fields['horizontalHeaders'], $this->fields['sortingOrderAscending']) :
            sprintf('%s%s', $this->fields['horizontalHeaders'], $this->fields['sortingOrderDescending']);

        $this->context->waitUntilElementIsVisible($verificationSelector);
    }

    public function canBeSorted(): bool
    {
        return $this->table->canBeSorted();
    }

    public function shouldHaveGridViewEnabled(bool $enabled): void
    {
        $this->isGridViewEnabled = $enabled;
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertTrue($this->getHTMLPage()->find($this->getLocator('list'))->isVisible());
    }

    public function getName(): string
    {
        return 'Subitems list';
    }

    public function clickListElement(string $contentName, string $contentType)
    {
        $this->table->clickListElement($contentName, $contentType);
    }

    public function isElementInTable($itemName): bool
    {
        return $$this->table->isElementInTable($itemName);
    }

    protected function specifyLocators(): array
    {
        return [
            new VisibleCSSLocator('list', '.ez-sil'),
            new VisibleCSSLocator('listTable', '.ez-sil .m-sub-items__list'),
        ];
    }
}
