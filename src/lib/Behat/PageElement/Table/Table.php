<?php

namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Table;

use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Element\DocumentElement;
use EzSystems\Behat\Browser\Element\NodeElement;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
use EzSystems\Behat\Browser\Page\Browser;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Pagination;

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
        $this->parentLocator = new VisibleCSSLocator('parent', '.ez-table');
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
        }

        return false;

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

        $foundHeaders = $this->getHeaders($elementData);

        return $this->getMatchingTableRow($foundHeaders, $elementData) !== null;
    }

    public function getTableRow(array $elementData): TableRow
    {
        if ($this->isEmpty()) {
            return false;
        }

        $foundHeaders = $this->getHeaders($elementData);

        $rowElement = $this->getMatchingTableRow($foundHeaders, $elementData);

        if ($rowElement) {
            return new TableRow($this->browser, $rowElement);
        }

        throw new \Exception('Table row with given data was not found!');


    }

    public function verifyIsLoaded(): void
    {
    }

    protected function specifyLocators(): array
    {
        return [
            new VisibleCSSLocator('empty', '.ez-table__cell--no-content'),
            new VisibleCSSLocator('columnHeader', '.ez-table__header-cell'),
            new VisibleCSSLocator('row', 'tr'),
            new VisibleCSSLocator('cell', '.ez-table__cell:nth-of-type(%d)'),
        ];
    }


    public function withRowLocator(VisibleCSSLocator $locator): self
    {
        $rowLocator = new VisibleCSSLocator('row', $locator->getSelector());

        $this->locators->replace($rowLocator);

        return $this;
    }

    public function withParentLocator(VisibleCSSLocator $locator): self
    {
        $this->parentLocator = $locator;
        $this->parentLocatorChanged = true;

        return $this;
    }

    public function withEmptyLocator(VisibleCSSLocator $locator): self
    {
        $rowLocator = new VisibleCSSLocator('empty', $locator->getSelector());

        $this->locators->replace($rowLocator);

        return $this;
    }

    public function withColumnLocator(VisibleCSSLocator $locator): self
    {
        $columnLocator = new VisibleCSSLocator('columnHeader', $locator->getSelector());

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

    private function getTableCellLocator(int $headerPosition): VisibleCSSLocator
    {
        // +1: headerPosition is 0-indexed, but CSS selectors are 1-indexed
        return new VisibleCSSLocator('tableCell', sprintf($this->getLocator('cell')->getSelector(), $headerPosition + 1));
    }

    /**
     * @param array $elementData
     * @return array
     */
    private function getHeaders(array $elementData): array
    {
        $searchedHeaders = array_keys($elementData);

        $allHeaders = $this->parentElement->findAll($this->getLocator('columnHeader'))
            ->map(function (NodeElement $element) {
                return $element->getText();
            });

        $foundHeaders = array_filter($allHeaders, function (string $header) use ($searchedHeaders) {
            return in_array($header, $searchedHeaders, true);
        });
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
        foreach ($this->getHTMLPage()->findAll($this->getLocator('row')) as $row) {
            foreach ($foundHeaders as $headerPosition => $header) {
                try {
                    $cellValue = $row->setTimeout(0)->find($this->getTableCellLocator($headerPosition))->getText();
                    // value not found, skip row
                } catch (\Exception $exception) {
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