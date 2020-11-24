<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use Behat\Mink\Element\NodeElement;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use EzSystems\Behat\Browser\Selector\SelectorInterface;
use PHPUnit\Framework\Assert;

class Matrix extends FieldTypeComponent
{
    public function setValue(array $parameters): void
    {
        $matrixValues = $this->parseParameters($parameters);

        $availableRows = count($this->getHTMLPage()->findAll($this->getSelector('row')));
        $rowsToSet = count($matrixValues);

        if ($rowsToSet > $availableRows) {
            $this->addRows($rowsToSet - $availableRows);
        }

        foreach ($matrixValues as $rowIndex => $row) {
            foreach ($row as $column => $value) {
                $this->internalSetValue((int)$rowIndex, $column, $value);
            }
        }
    }

    public function getValue(): array
    {
        return [$this->getParsedTableValue($this->getSelector('editModeTableHeaders'), $this->getSelector('editModeTableRow'))];
    }

    public function verifyValueInItemView(array $expectedValue): void
    {
        $parsedTable = $this->getParsedTableValue($this->getSelector('viewModeTableHeaders'), $this->getSelector('viewModeTableRow'));

        Assert::assertEquals($expectedValue['value'], $parsedTable);
    }

    private function parseParameters(array $parameters): array
    {
        $rows = explode(',', $parameters['value']);

        $columnIdentifiers = explode(':', array_shift($rows));
        $numberOfColumns = count($columnIdentifiers);

        $parsedRows = [];
        foreach ($rows as $row) {
            $parsedRow = [];
            $columnValues = explode(':', $row);
            for ($i = 0; $i < $numberOfColumns; ++$i) {
                $parsedRow[$columnIdentifiers[$i]] = $columnValues[$i];
            }

            $parsedRows[] = $parsedRow;
        }

        return $parsedRows;
    }

    private function addRows(int $numberOfRows): void
    {
        for ($i = 0; $i < $numberOfRows; ++$i) {
            $this->getHTMLPage()->find($this->getSelector('addRowButton'))->click();
        }
    }

    private function internalSetValue(int $rowIndex, string $column, $value): void
    {
        $matrixCellSelector = CSSSelector::combine(
            $this->getSelector('matrixCellSelectorFormat')->getSelector(),
            new CSSSelector('', $rowIndex),
            new CSSSelector('', $column),
        );

        $this->getHTMLPage()->find($matrixCellSelector)->setValue($value);
    }

    private function getParsedTableValue(SelectorInterface $headerSelector, SelectorInterface $rowSelector): string
    {
        $parsedTable = '';

        $headers = $this->getHTMLPage()->findAll($headerSelector)->map(function (NodeElement $element) {
            return $element->getText();
        });

        $parsedTable .= implode(':', $headers);

        $rows = $this->getHTMLPage()->findAll($rowSelector);
        foreach ($rows as $row) {
            $parsedTable .= ',';
            $cellValues = $row
                ->findAll(new CSSSelector('', 'td'))
                ->map(function (NodeElement $element) { return $element->getText();});
            $parsedTable .= implode(':', $cellValues);
        }

        return $parsedTable;
    }

    protected function specifySelectors(): array
    {
        return [
            new CSSSelector('matrixCellSelectorFormat', '[name="ezplatform_content_forms_content_edit[fieldsData][ezmatrix][value][entries][%d][%s]"]'),
            new CSSSelector('row', '.ez-table__matrix-entry'),
            new CSSSelector('addRowButton', '.ez-btn--add-matrix-entry'),
            new CSSSelector('viewModeTableHeaders', '.ez-content-field-value thead th'),
            new CSSSelector('viewModeTableRow', '.ez-content-field-value tbody tr'),
            new CSSSelector('editModeTableHeaders', '.ez-table thead th[data-identifier]'),
            new CSSSelector('editModeTableRow', '.ez-table tr.ez-table__matrix-entry'),
        ];
    }

    public function getFieldTypeIdentifier(): string
    {
        return 'ezmatrix';
    }
}
