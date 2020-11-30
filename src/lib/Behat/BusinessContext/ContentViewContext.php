<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\BusinessContext;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use EzSystems\Behat\Core\Behat\ArgumentParser;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\DraftConflictDialog;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\LeftMenu;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\UniversalDiscoveryWidget;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\ContentViewPage;
use PHPUnit\Framework\Assert;

class ContentViewContext implements Context
{
    private $argumentParser;
    /**
     * @var ContentViewPage
     */
    private $contentViewPage;
    /**
     * @var LeftMenu
     */
    private $leftMenu;
    /**
     * @var UniversalDiscoveryWidget
     */
    private $universalDiscoveryWidget;
    /**
     * @var DraftConflictDialog
     */
    private $draftConflictDialog;

    public function __construct(
        ArgumentParser $argumentParser,
        ContentViewPage $contentViewPage,
        LeftMenu $leftMenu,
        UniversalDiscoveryWidget $universalDiscoveryWidget,
        DraftConflictDialog $draftConflictDialog)
    {
        $this->argumentParser = $argumentParser;
        $this->contentViewPage = $contentViewPage;
        $this->leftMenu = $leftMenu;
        $this->universalDiscoveryWidget = $universalDiscoveryWidget;
        $this->draftConflictDialog = $draftConflictDialog;
    }

    /**
     * @Given I start creating a new content :contentType
     */
    public function startCreatingContent(string $contentType): void
    {
        $this->contentViewPage->startCreatingContent($contentType);
    }

    /**
     * @Given I start editing the current content
     * @Given I start editing the current content in :language language
     */
    public function startEditingContent(string $language = null): void
    {
        $this->contentViewPage->editContent($language);
    }

    /**
     * @Given I open UDW and go to :itemPath
     */
    public function iOpenUDWAndGoTo(string $itemPath): void
    {
        $this->leftMenu->verifyIsLoaded();
        $this->leftMenu->browse();

        $this->universalDiscoveryWidget->verifyIsLoaded();
        $this->universalDiscoveryWidget->selectContent($this->argumentParser->replaceRootKeyword($itemPath));
        $this->universalDiscoveryWidget->openPreview();
    }

    /**
     * @Then there's a :itemName :itemType on Subitems list
     */
    public function verifyThereIsItemInSubItemList(string $itemName, string $itemType): void
    {
        Assert::assertTrue($this->contentViewPage->isChildElementPresent(['Name' => $itemName, 'Content Type' => $itemType]));
    }

    /**
     * @Then there's no :itemName :itemType on Subitems list
     */
    public function verifyThereIsNoItemInSubItemListInRoot(string $itemName, string $itemType): void
    {
        Assert::assertFalse($this->contentViewPage->isChildElementPresent(['Name' => $itemName, 'Content Type' => $itemType]));
    }

    /**
     * @Given I should be viewing Content Item :contentName in :path
     */
    public function verifyImOnContentItemPage(string $contentName, ?string $path = null)
    {
        $path = $this->argumentParser->replaceRootKeyword($path);
        $this->contentViewPage->setExpectedLocationPath(sprintf("%s/%s", $path, $contentName));
        $this->contentViewPage->verifyIsLoaded();
    }

    /**
     * @Then content attributes equal
     */
    public function contentAttributesEqual(TableNode $parameters): void
    {
        foreach ($parameters->getHash() as $fieldData) {
            $fieldLabel = $fieldData['label'];
            $fieldTypeIdentifier = $fieldData['fieldTypeIdentifier'] ?? null;
            $expectedFieldValues = $fieldData;
            $this->contentViewPage->verifyFieldHasValues($fieldLabel, $expectedFieldValues, $fieldTypeIdentifier);
        }
    }

    /**
     * @When I start creating new draft from draft conflict modal
     */
    public function startCreatingNewDraftFromDraftConflictModal(): void
    {
        $this->draftConflictDialog->verifyIsLoaded();
        $this->draftConflictDialog->createNewDraft();
    }

    /**
     * @When I start editing draft with version number :versionNumber from draft conflict modal
     */
    public function startEditingDraftFromDraftConflictModal(string $versionNumber): void
    {
        $this->draftConflictDialog->verifyIsLoaded();
        $this->draftConflictDialog->edit($versionNumber);
    }

    /**
     * @When I send content to trash
     */
    public function iSendContentToTrash(): void
    {
        $this->contentViewPage->sendToTrash();
    }
}
