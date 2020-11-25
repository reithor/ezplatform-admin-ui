<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables;

use Behat\Mink\Element\NodeElement;
use EzSystems\Behat\Browser\Locator\CSSLocator;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Pagination;
use PHPUnit\Framework\Assert;

class SubItemsTable extends Table
{
    public function getTableCellValue(string $header, ?string $secondHeader = null): string
    {
        $columnPosition = $this->context->getElementPositionByText(
            $header,
            $this->fields['horizontalHeaders']
        );
        $rowPosition = $this->context->getElementPositionByText(
            $secondHeader,
            $this->fields['listElement']
        );

        return $this->getCellValue($rowPosition, $columnPosition);
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

    public function clickListElement(string $name, ?string $contentType = null): void
    {
        $pagination = ElementFactory::createElement($this->context, Pagination::ELEMENT_NAME);
        $elementPositionInTable = $this->getElementPositionInTable($name, $contentType);
        $isElementInTable = (bool) $elementPositionInTable;

        while (!$isElementInTable && $pagination->isNextButtonActive()) {
            $pagination->clickNextButton();
            $elementPositionInTable = $this->getElementPositionInTable($name, $contentType);
            $isElementInTable = (bool) $elementPositionInTable;
        }

        Assert::assertTrue($isElementInTable, sprintf('There is no sub-item %s on the Sub-item list', $name));

        $this->context->findElement(sprintf($this->fields['nthListElement'], $elementPositionInTable))->click();
    }

    public function clickEditButton(string $listItemName): void
    {
        $this->clickEditButtonByElementLocator($listItemName, $this->fields['listElement']);
    }

    /**
     * Check if list contains link element with given name.
     *
     * @param string $name
     *
     * @return int index of element, with '1' for first element, and '0' for 'not found'
     */
    protected function getElementPositionInTable(string $name, ?string $contentType = null): int
    {
        if ($this->context->isElementVisible($this->fields['noItems'])) {
            return 0;
        }

        $isElementNamePresentInTable = strpos($this->getHTMLPage()->find($this->getLocator('list'))->getText(), $name) !== false;
        if (!$isElementNamePresentInTable) {
            return 0;
        }

        $matchingListElementsPositions = $this->getAllElementsPositionsByText($name, $this->fields['listElement']);
        $matchingCount = count($matchingListElementsPositions);
        for ($i = 0; $i < $matchingCount; ++$i) {
            if ($contentType === null || $this->context->findElement(sprintf($this->fields['listElementType'], $matchingListElementsPositions[$i]))->getText() === $contentType) {
                return $matchingListElementsPositions[$i];
            }
        }

        return 0;
    }

    /**
     * Check if list contains link element with given name.
     *
     * @param string $name
     *
     * @return bool
     */
    public function isElementOnCurrentPage(string $name, ?string $contentType = null): bool
    {
        return (bool) $this->getElementPositionInTable($name, $contentType);
    }

    /**
     * @return array all table records as hash map
     */
    public function getTableHash(): array
    {
        $tableHash = [];

        /** @var NodeElement[] $allHeaders */
        $allHeaders = $this->context->findAllElements($this->fields['horizontalHeaders']);
        $headersCount = count($allHeaders);
        $allHeadersText = [];
        for ($i = 0; $i < $headersCount; ++$i) {
            $allHeadersText[$i] = $allHeaders[$i]->getText();
        }
        /** @var NodeElement[] $allRows */
        $allRows = $this->context->findAllElements($this->fields['listRow']);
        $j = 0;
        foreach ($allRows as $row) {
            $rowHash = [];
            /** @var NodeElement[] $allCells */
            $allCells = $row->findAll('css', 'td');
            $headersCount = count($allHeaders);
            for ($i = 0; $i < $headersCount; ++$i) {
                $rowHash[$allHeadersText[$i]] = $allCells[$i]->getText();
            }
            $tableHash[$j] = $rowHash;
            ++$j;
        }

        return $tableHash;
    }

    public function canBeSorted(): bool
    {
        return true;
    }

    public function verifyIsLoaded(): void
    {
    }

    public function getName(): string
    {
        return 'Subitems table';
    }

    protected function specifyLocators(): array
    {
        return [
            new CSSLocator('horizontalHeaders', '.ez-sil .m-sub-items__list .c-table-view__cell--head'),
            new CSSLocator('listElement', '.ez-sil .m-sub-items__list .c-table-view-item__link'),
            new CSSLocator('nthListElement', '.ez-sil .m-sub-items__list tr:nth-child(%d) .c-table-view-item__link'),
            new CSSLocator('listElementType', '.ez-sil .m-sub-items__list tr:nth-child(%d) .c-table-view-item__cell--content-type'),
            new CSSLocator('sortingOrderAscending', '.ez-sil .m-sub-items__list .c-table-view__cell--sorted-asc'),
            new CSSLocator('sortingOrderDescending', '.ez-sil .m-sub-items__list .c-table-view__cell--sorted-desc'),
            new CSSLocator('editButton', '.ez-sil .m-sub-items__list .c-table-view-item__btn--edit'),
            new CSSLocator('noItems', '.ez-sil .m-sub-items__list .c-no-items'),
        ];
    }
}
