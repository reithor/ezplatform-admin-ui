<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement;

use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Element\NodeElement;
use EzSystems\Behat\Browser\Locator\CSSLocator;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
use PHPUnit\Framework\Assert;

class UniversalDiscoveryWidget extends Component
{
    private const LONG_TIMEOUT = 20;
    private const SHORT_TIMEOUT = 2;

    public function selectContent(string $itemPath): void
    {
        $pathParts = explode('/', $itemPath);
        $level = 1;

        foreach ($pathParts as $itemName) {
            $this->selectTreeBranch($itemName, $level);
            ++$level;
        }

        $itemName = $pathParts[count($pathParts) - 1];

        if ($this->isMultiSelect()) {
            $this->addItemToMultiselection($itemName, count($pathParts));
        }
    }

    public function confirm(): void
    {
        $this->getHTMLPage()->find($this->getLocator('confirmButton'))->click();
    }

    public function cancel(): void
    {
        $this->getHTMLPage()->find($this->getLocator('cancelButton'))->click();
    }

    protected function isMultiSelect(): bool
    {
        return $this->getHTMLPage()
            ->setTimeout(self::SHORT_TIMEOUT)
            ->findAll($this->getLocator('multiSelectAddButton'))
            ->any();
    }

    protected function addItemToMultiSelection(string $itemName, int $level): void
    {
        $currentSelectedItemLocator = new VisibleCSSLocator('currentSelectedItem', sprintf($this->getLocator('treeLevelSelectedFormat')->getSelector(), $level));
        $this->getHTMLPage()->findAll($currentSelectedItemLocator)->getByText($itemName)->mouseOver();

        $addItemLocator = new VisibleCSSLocator('addItemLocator', sprintf($this->getLocator('currentlySelectedAddItemButtonFormat')->getSelector(), $level));
        $this->getHTMLPage()->find($addItemLocator)->click();

        $addedItemSelector = new VisibleCSSLocator('', sprintf($this->getLocator('currentlySelectedItemAddedFormat')->getSelector(), $level));
        Assert::assertTrue($this->getHTMLPage()->find($addedItemSelector)->isVisible());
    }

    protected function selectTreeBranch(string $itemName, int $level): void
    {
        $treeLevelSelector = new VisibleCSSLocator('', sprintf($this->getLocator('treeLevelFormat')->getSelector(), $level));

        $this->getHTMLPage()->setTimeout(self::LONG_TIMEOUT)->find($treeLevelSelector)->assert()->isVisible();

        $alreadySelectedItemName = $this->getCurrentlySelectedItemName($level);

        if ($itemName === $alreadySelectedItemName) {
            // don't do anything, this level is already selected

            return;
        }

        // when the tree is loaded further for the already selected item we need to make sure it's reloaded properly
        $willNextLevelBeReloaded = $alreadySelectedItemName !== null && $this->isNextLevelDisplayed($level);

        if ($willNextLevelBeReloaded) {
            $currentItems = $this->getItemsFromLevel($level + 1);
        }

        $treeElementsLocator = new VisibleCSSLocator('', sprintf($this->getLocator('treeLevelElementsFormat')->getSelector(), $level));
        $selectedTreeElementLocator = new VisibleCSSLocator('', sprintf($this->getLocator('treeLevelSelectedFormat')->getSelector(), $level));

        $this->getHTMLPage()->findAll($treeElementsLocator)->getByText($itemName)->click();
        $this->getHTMLPage()->findAll($selectedTreeElementLocator)->getByText($itemName)->assert()->isVisible();

        if ($willNextLevelBeReloaded) {
            // Wait until the items displayed previously disappear or change
            $this->getHTMLPage()->waitUntil(function () use ($currentItems, $level) {
                return !$this->isNextLevelDisplayed($level) || $this->getItemsFromLevel($level + 1) !== $currentItems;
            });
        }
    }

    public function openPreview(): void
    {
        $this->getHTMLPage()->find($this->getLocator('previewButton'))->click();
    }

    protected function getItemsFromLevel(int $level): array
    {
        $levelItemsSelector = new VisibleCSSLocator('css', sprintf($this->getLocator('treeLevelElementsFormat')->getSelector(), $level));

        return $this->getHTMLPage()->findAll($levelItemsSelector)->map(
            function (NodeElement $element) {
                return $element->getText();
            }
        );
    }

    private function getCurrentlySelectedItemName(int $level): ?string
    {
        $selectedElementSelector = new VisibleCSSLocator(
            'selectedElement',
            sprintf($this->getLocator('treeLevelSelectedFormat')->getSelector(), $level)
        );

        $elements = $this->getHTMLPage()->setTimeout(self::SHORT_TIMEOUT)->findAll($selectedElementSelector);

        return $elements->any() ? $elements->single()->getText() : null;
    }

    private function isNextLevelDisplayed(int $currentLevel): bool
    {
        return $this->getHTMLPage()->
            setTimeout(self::SHORT_TIMEOUT)->
            find(
                new VisibleCSSLocator(
                    'css',
                    sprintf($this->getLocator('treeLevelElementsFormat')->getSelector(), $currentLevel + 1))
            )->isVisible();
    }

    public function verifyIsLoaded(): void
    {
        $expectedTabTitles = ['Browse', 'Bookmarks', 'Search'];

        $tabs = $this->getHTMLPage()->findAll($this->getLocator('categoryTabSelector'));
        $foundExpectedTitles = [];
        foreach ($tabs as $tab) {
            $tabText = $tab->getText();
            if (in_array($tabText, $expectedTabTitles)) {
                $foundExpectedTitles[] = $tabText;
            }
        }

        Assert::assertEquals($expectedTabTitles, $foundExpectedTitles);
    }

    public function getName(): string
    {
        return 'Universal discovery widget';
    }

    protected function specifyLocators(): array
    {
        return [
            // general selectors
            new CSSLocator('confirmButton', '.c-selected-locations__confirm-button'),
            new CSSLocator('categoryTabSelector', '.c-tab-selector__item'),
            new CSSLocator('cancelButton', '.c-top-menu__cancel-btn'),
            new CSSLocator('mainWindow', '.m-ud'),
            new CSSLocator('selectedLocationsTab', '.c-selected-locations'),
            // selectors for path traversal
            new CSSLocator('treeLevelFormat', '.c-finder-branch:nth-child(%d)'),
            new CSSLocator('treeLevelElementsFormat', '.c-finder-branch:nth-of-type(%d) .c-finder-leaf'),
            new CSSLocator('treeLevelSelectedFormat', '.c-finder-branch:nth-of-type(%d) .c-finder-leaf--marked'),
            // selectors for multiitem selection
            new CSSLocator('multiSelectAddButton', '.c-toggle-selection-button'),
            // itemActions
            new CSSLocator('previewButton', '.c-content-meta-preview__preview-button'),
            new CSSLocator('currentlySelectedItemAddedFormat', '.c-finder-branch:nth-of-type(%d) .c-finder-leaf--marked .c-toggle-selection-button.c-toggle-selection-button--selected'),
            new CSSLocator('currentlySelectedAddItemButtonFormat', '.c-finder-branch:nth-of-type(%d) .c-finder-leaf--marked .c-toggle-selection-button'),
        ];
    }
}
