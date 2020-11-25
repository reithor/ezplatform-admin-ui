<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement;

use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Element\NodeElement;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
use PHPUnit\Framework\Assert;

class UniversalDiscoveryWidget extends Component
{
    private const LONG_TIMEOUT = 10;
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
        $this->getHTMLPage()->setTimeout(self::SHORT_TIMEOUT)->waitUntil(function () {
            return !$this->isVisible();
        });
    }

    public function cancel(): void
    {
        $this->getHTMLPage()->find($this->getLocator('cancelButton'))->click();
        $this->getHTMLPage()->setTimeout(self::SHORT_TIMEOUT)->waitUntil(function () {
            return !$this->isVisible();
        });
    }

    protected function isVisible(): bool
    {
        return $this->getHTMLPage()
            ->setTimeout(self::SHORT_TIMEOUT)
            ->findAll($this->getLocator('mainWindow'))
            ->any();
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
        $currentSelectedItemSelector = new VisibleCSSLocator('', sprintf($this->getLocator('treeLevelSelectedFormat')->getSelector(), $level));
        $this->getHTMLPage()->findAll($currentSelectedItemSelector)->getByText($itemName)->mouseOver();

        $addItemSelector = new VisibleCSSLocator('', sprintf($this->getLocator('currentlySelectedAddItemButtonFormat')->getSelector(), $level));
        $this->getHTMLPage()->find($addItemSelector)->click();

        $addedItemSelector = new VisibleCSSLocator('', sprintf($this->getLocator('currentlySelectedItemAddedFormat')->getSelector(), $level));
        Assert::assertTrue($this->getHTMLPage()->find($addedItemSelector)->isVisible());
    }

    protected function selectTreeBranch(string $itemName, int $level): void
    {
        $treeLevelSelector = new VisibleCSSLocator('', sprintf($this->getLocator('treeLevelFormat')->getSelector(), $level));

        Assert::assertTrue($this->getHTMLPage()->setTimeout(self::LONG_TIMEOUT)->find($treeLevelSelector)->isVisible());

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

        $treeElementsSelector = new VisibleCSSLocator('', sprintf($this->getLocator('treeLevelElementsFormat')->getSelector(), $level));
        $this->getHTMLPage()->findAll($treeElementsSelector)->getByText($itemName)->click();
        Assert::assertTrue(
            $this->getHTMLPage()->findAll(
                new VisibleCSSLocator('', sprintf($this->getLocator('treeLevelSelectedFormat')->getSelector(), $level))
            )->getByText($itemName)->isVisible()
        );

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
            new VisibleCSSLocator('confirmButton', '.c-selected-locations__confirm-button'),
            new VisibleCSSLocator('categoryTabSelector', '.c-tab-selector__item'),
            new VisibleCSSLocator('cancelButton', '.c-top-menu__cancel-btn'),
            new VisibleCSSLocator('mainWindow', '.m-ud'),
            new VisibleCSSLocator('selectedLocationsTab', '.c-selected-locations'),
            // selectors for path traversal
            new VisibleCSSLocator('treeLevelFormat', '.c-finder-branch:nth-child(%d)'),
            new VisibleCSSLocator('treeLevelElementsFormat', '.c-finder-branch:nth-of-type(%d) .c-finder-leaf'),
            new VisibleCSSLocator('treeLevelSelectedFormat', '.c-finder-branch:nth-of-type(%d) .c-finder-leaf--marked'),
            // selectors for multiitem selection
            new VisibleCSSLocator('multiSelectAddButton', '.c-toggle-selection-button'),
            // itemActions
            new VisibleCSSLocator('previewButton', '.c-content-meta-preview__preview-button'),
            new VisibleCSSLocator('currentlySelectedItemAddedFormat', '.c-finder-branch:nth-of-type(%d) .c-finder-leaf--marked .c-toggle-selection-button.c-toggle-selection-button--selected'),
            new VisibleCSSLocator('currentlySelectedAddItemButtonFormat', '.c-finder-branch:nth-of-type(%d) .c-finder-leaf--marked .c-toggle-selection-button.c-toggle-selection-button--selected'),
        ];
    }
}
