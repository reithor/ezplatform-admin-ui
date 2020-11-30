<?php

namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Table;

use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Element\DocumentElement;
use EzSystems\Behat\Browser\Element\NodeElement;
use EzSystems\Behat\Browser\Locator\CSSLocator;
use EzSystems\Behat\Browser\Locator\LocatorCollection;
use EzSystems\Behat\Browser\Locator\LocatorInterface;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
use EzSystems\Behat\Browser\Page\Browser;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Pagination;
use PHPUnit\Framework\Assert;

class Table extends Component implements TableInterface
{
    private CONST MAX_PAGE_COUNT = 10;

    /**
     * @var Pagination
     */
    private $pagination;
    /**
     * @var DocumentElement
     */
    private $parentElement;

    /** @var bool */
    private $parentLocatorChanged;
    /**
     * @var VisibleCSSLocator
     */
    private $parentLocator;

    public function __construct(Browser $browser, Pagination $pagination)
    {
        parent::__construct($browser);
        $this->pagination = $pagination;
        $this->parentLocatorChanged = true;
        $this->parentLocator = $this->getLocator('parent');
    }

    public function isEmpty(): bool
    {
        $this->setParentElement();

        return $this->parentElement
           ->setTimeout(0)
           ->findAll($this->locators->get('empty'))
           ->any();
    }

    public function hasElement(array $elementData): bool
    {
        if ($this->isEmpty()) {
            return false;
        }

        $hasElementOnCurrentPage = $this->hasElementOnCurrentPage($elementData);

        if ($hasElementOnCurrentPage) {
            return true;
        }

        $iterationCount = 0;

        while ($this->pagination->isNextButtonActive() && $iterationCount < self::MAX_PAGE_COUNT)
        {
            $this->pagination->clickNextButton();

            $hasElementOnCurrentPage = $this->hasElementOnCurrentPage($elementData);

            if ($hasElementOnCurrentPage) {
                return true;
            }

            $iterationCount++;
        }

        return false;
    }

    public function getColumnValues(array $columnNames): array
    {
        if ($this->isEmpty()) {
            return [];
        }

        $allHeaders = $this->parentElement->findAll($this->getLocator('columnHeader'))
            ->map(function (NodeElement $element) {
                return $element->getText();
            });

        $foundHeaders = array_filter($allHeaders, function (string $header) use ($columnNames) {
            return in_array($header, $columnNames, true);
        });

        $result = [];

        foreach ($foundHeaders as $headerPosition => $header) {
            $result[$header] = $this->parentElement
                ->findAll($this->getTableCellLocator($headerPosition))
                ->map(function(NodeElement $element) {
                    return $element->getText();
                });
        }

        return $result;
    }

    public function endConfiguration(): TableInterface
    {
        return $this;
    }

    public function hasElementOnCurrentPage(array $elementData): bool
    {
        if ($this->isEmpty()) {
            return false;
        }

        $searchedHeaders = array_keys($elementData);

        $allHeaders = $this->parentElement->setTimeout(0)->findAll($this->getLocator('columnHeader'))
            ->map(function (NodeElement $element) {
                return $element->getText();
            });

        $searchedHeadersWithPositions = $this->getHeaderPositions($searchedHeaders, $allHeaders);

        return $this->getMatchingTableRow($searchedHeadersWithPositions, $elementData) !== null;
    }

    public function getTableRow(array $elementData): TableRow
    {
        if ($this->isEmpty()) {
            throw new \Exception('Table row with given data was not found!');
        }

        $searchedHeaders = array_keys($elementData);

        $allHeaders = $this->parentElement->findAll($this->getLocator('columnHeader'))
            ->map(function (NodeElement $element) {
                return $element->getText();
            });

        $foundHeaders = $this->getHeaderPositions($searchedHeaders, $allHeaders);
        $rowElement = $this->getMatchingTableRow($foundHeaders, $elementData);

        $cellLocators = [];
        foreach ($allHeaders as $headerPosition => $header) {
            $cellLocators[] = $this->getTableCellLocator($headerPosition, $header);
        }

        $filteredCellLocators = array_filter($cellLocators, function (LocatorInterface $locator) {
            return $locator->getIdentifier() !== '';
        });

        if ($rowElement) {
            return new TableRow($this->browser, $rowElement, new LocatorCollection($filteredCellLocators));
        }

        throw new \Exception('Table row with given data was not found!');
    }

    public function verifyIsLoaded(): void
    {
    }

    protected function specifyLocators(): array
    {
        return [
            new CSSLocator('empty', '.ez-table__cell--no-content,.ez-table-no-content'),
            new CSSLocator('columnHeader', '.ez-table__header-cell,th'),
            new CSSLocator('row', 'tr'),
            new CSSLocator('cell', '.ez-table__cell:nth-of-type(%d),td:nth-of-type(%d)'),
            new CSSLocator('parent', '.ez-table'),
        ];
    }


    public function withRowLocator(CSSLocator $locator): self
    {
        $rowLocator = new CSSLocator('row', $locator->getSelector());

        $this->locators->replace($rowLocator);

        return $this;
    }

    public function withTableCell(CSSLocator $locator): self
    {
        $rowLocator = new CSSLocator('cell', $locator->getSelector());

        $this->locators->replace($rowLocator);

        return $this;
    }

    public function withParentLocator(CSSLocator $locator): self
    {
        $this->parentLocator = $locator;
        $this->parentLocatorChanged = true;

        return $this;
    }

    public function withEmptyLocator(CSSLocator $locator): self
    {
        $rowLocator = new CSSLocator('empty', $locator->getSelector());

        $this->locators->replace($rowLocator);

        return $this;
    }

    public function withColumnLocator(CSSLocator $locator): self
    {
        $columnLocator = new CSSLocator('columnHeader', $locator->getSelector());

        $this->locators->replace($columnLocator);

        return $this;
    }

    private function setParentElement()
    {
        if (!$this->parentLocatorChanged) {
            return;
        }

        $this->parentElement = $this->parentLocator
            ? $this->getHTMLPage()->find($this->parentLocator)
            : $this->getHTMLPage();

        $this->parentLocatorChanged = false;
    }

    private function getTableCellLocator(int $headerPosition, string $identifier = 'tableCell'): CSSLocator
    {
        // +1: headerPosition is 0-indexed, but CSS selectors are 1-indexed
        return new CSSLocator($identifier, sprintf($this->getLocator('cell')->getSelector(), $headerPosition + 1, $headerPosition + 1));
    }

    /**
     * @param array $elementData
     * @return array
     */
    private function getHeaderPositions(array $searchedHeaders, array $allHeaders): array
    {
        $foundHeaders = array_filter($allHeaders, function (string $header) use ($searchedHeaders) {
            return in_array($header, $searchedHeaders, true);
        });

        Assert::assertCount(
            count($searchedHeaders), $foundHeaders,
            sprintf('Could not find all expected headersin the table. Found: %s', implode(',', $foundHeaders))
        );

        return $foundHeaders;
    }

    /**
     * @param array $foundHeaders
     * @param array $elementData
     *
     * @return NodeElement|null
     */
    private function getMatchingTableRow(array $foundHeaders, array $elementData): ?NodeElement
    {
        foreach ($this->parentElement->setTimeout(0)->findAll($this->getLocator('row')) as $row) {
            foreach ($foundHeaders as $headerPosition => $header) {
                try {
                    $cellValue = $row->setTimeout(0)->find($this->getTableCellLocator($headerPosition))->getText();
                } catch (\Exception $exception) {
                    // value not found, skip row
                    continue 2;
                }

                if ($cellValue !== $elementData[$header]) {
                    // if any of the values do not match we skip the entire row
                    continue 2;
                }
            }

            // all values from the row match
            return $row;
        }

        return null;
    }
}