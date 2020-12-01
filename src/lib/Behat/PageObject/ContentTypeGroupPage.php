<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use Behat\Mink\Session;
use eZ\Publish\API\Repository\ContentTypeService;
use EzSystems\Behat\Browser\Page\Browser;
use EzSystems\Behat\Browser\Page\Page;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Dialog;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Table\Table;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\Dialogalog as DialogalogAlias;
use PHPUnit\Framework\Assert;

class ContentTypeGroupPage extends Page
{
    /** @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\AdminList  */
    protected $adminList;

    /** @var string */
    protected $expectedName;
    /**
     * @var ContentTypeService
     */
    private $contentTypeService;
    /**
     * @var mixed
     */
    private $contentTypeGroupId;
    /**
     * @var Table
     */
    private $table;
    /**
     * @var Dialog
     */
    private $dialog;

    public function __construct(Browser $browser, ContentTypeService $contentTypeService, Table $table, Dialog $dialog)
    {
        parent::__construct($browser);
        $this->contentTypeService = $contentTypeService;
        $this->table = $table->withParentLocator($this->getLocator('tableContainer'));
        $this->dialog = $dialog;
    }

    public function verifyListIsEmpty(): void
    {
        Assert::assertTrue($this->table->isEmpty());
    }

    public function edit(string $contentTypeName): void
    {
        $this->table->getTableRow(['Name' => $contentTypeName])->edit();
    }

    public function goTo(string $contentTypeName): void
    {
        $this->table->getTableRow(['Name' => $contentTypeName])->goToItem();
    }

    public function createNew(): void
    {
        $this->getHTMLPage()->find($this->getLocator('createButton'))->click();
    }

    public function isContentTypeOnTheList($contentTypeName): bool
    {
        return $this->table->hasElement(['Name' => $contentTypeName]);
    }

    public function delete(string $contentTypeName)
    {
        $this->table->getTableRow(['Name' => $contentTypeName])->select();
        $this->getHTMLPage()->find($this->getLocator('deleteButton'))->click();
        $this->dialog->verifyIsLoaded();
        $this->dialog->confirm();
    }

    protected function getRoute(): string
    {
        return sprintf('/contenttypegroup/%d', $this->contentTypeGroupId);
    }

    public function verifyIsLoaded(): void
    {
        $this->getHTMLPage()
            ->find($this->getLocator('pageTitle'))
            ->assert()->textEquals($this->expectedName);
        $this->getHTMLPage()
            ->find($this->getLocator('listHeader'))
            ->assert()->textEquals(sprintf("Content Types in '%s'", $this->expectedName));
    }
    
    public function setExpectedContentTypeGroupName(string $expectedName) {
        $this->expectedName = $expectedName;
        $groups = $this->contentTypeService->loadContentTypeGroups();

        foreach ($groups as $group) {
            if ($group->identifier === $expectedName)
            {
                $this->contentTypeGroupId = $group->id;
                return;
            }
        }
    }

    public function getName(): string
    {
        return 'Content Type group';
    }

    protected function specifyLocators(): array
    {
        return [
            new VisibleCSSLocator('pageTitle',  '.ez-header h1'),
            new VisibleCSSLocator('createButton', '.ez-icon-create'),
            new VisibleCSSLocator('listHeader', '.ez-table-header .ez-table-header__headline, header .ez-table__headline, header h5'),
            new VisibleCSSLocator('tableContainer', '.ez-container'),
            new VisibleCSSLocator('deleteButton', '.ez-icon-trash,button[data-original-title^="Delete"]'),
        ];
    }
}
