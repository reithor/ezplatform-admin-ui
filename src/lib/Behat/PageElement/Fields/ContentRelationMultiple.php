<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use Behat\Mink\Session;
use EzSystems\Behat\Browser\Context\OldBrowserContext;
use EzSystems\Behat\Browser\Factory\ElementFactory;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables\ContentRelationTable;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\UniversalDiscoveryWidget;
use PHPUnit\Framework\Assert;

class ContentRelationMultiple extends FieldTypeComponent
{
    /**
     * @var UniversalDiscoveryWidget
     */
    private $universalDiscoveryWidget;

    public function __construct(Browser $browser, UniversalDiscoveryWidget $universalDiscoveryWidget)
    {
        parent::__construct($browser);
        $this->universalDiscoveryWidget = $universalDiscoveryWidget;
    }

    public function setValue(array $parameters): void
    {
        $relationsToSet = [];

        foreach (array_keys($parameters) as $parameterKey) {
            $relationsToSet[$parameterKey] = explode('/', $parameters[$parameterKey])[substr_count($parameters[$parameterKey], '/')];
        }
        if (!$this->isRelationEmpty()) {
            $relationsToSet = $this->removeRedundantRelations($relationsToSet);
        }

        if (count($relationsToSet) > 0) {
            $this->startAddingRelations();
            $this->selectRelationsAndConfirm($relationsToSet, $parameters);
        }
    }

    private function removeRedundantRelations(array $wantedRelations): array
    {
        $contentRelationTableHash = $this->contentRelationTable->getTableHash();
        foreach ($contentRelationTableHash as $row) {
            if (!in_array($row['Name'], $wantedRelations)) {
                $this->contentRelationTable->selectListElement($row['Name']);
            } else {
                $key = array_search($row['Name'], $wantedRelations);
                unset($wantedRelations[$key]);
            }
        }

        $this->contentRelationTable->clickTrashIcon();

        return $wantedRelations;
    }

    private function startAddingRelations()
    {
        if ($this->isRelationEmpty()) {
            $selectSelector = CSSSelector::combine($this->parentSelector, $this->getSelector('selectContent'));
            $this->getHTMLPage()->find($selectSelector)->click();
        } else {
            $this->contentRelationTable->clickPlusButton();
        }
    }

    private function selectRelationsAndConfirm($items, $paths)
    {
        $itemsToSet = array_keys($items);
        foreach ($itemsToSet as $itemToSet) {
            $this->universalDiscoveryWidget->selectContent($paths[$itemToSet]);
        }
        $this->universalDiscoveryWidget->confirm();
    }

    public function getValue(): array
    {
        $selectSelector = CSSSelector::combine($this->parentSelector, $this->getSelector('selectContent'));

        return [
            $this->getHTMLPage()->find($selectSelector)->getValue()
        ];
    }

    public function verifyValueInItemView(array $values): void
    {
        $explodedValue = explode('/', $values['firstItem']);
        $firstValue = $explodedValue[count($explodedValue) - 1];
        $explodedValue = explode('/', $values['secondItem']);
        $secondValue = $explodedValue[count($explodedValue) - 1];

        $viewPatternRegex = '/Multiple relations:[\w\/,: ]* %s [\w \/,:]*/';
        Assert::assertRegExp(
            sprintf($viewPatternRegex, $firstValue),
            $this->getHTMLPage()->find($this->getSelector('fieldContainer'))->getText(),
            'Field has wrong value'
        );
        Assert::assertRegExp(
            sprintf($viewPatternRegex, $secondValue),
            $this->getHTMLPage()->find($this->getSelector('fieldContainer'))->getText(),
            'Field has wrong value'
        );
    }

    public function isRelationEmpty(): bool
    {
        $selectSelector = CSSSelector::combine($this->parentSelector, $this->getSelector('selectContent'));

        return $this->getHTMLPage()->findAll($selectSelector)->any();
    }

    public function specifySelectors(): array
    {
        return [
            new CSSSelector('selectContent', '.ez-relations__cta-btn-label'),
        ];
    }

    public function getFieldTypeIdentifier(): string
    {
        return 'ezobjectrelationlist';
    }
}
