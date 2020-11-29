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

    public function __construct(Browser $browser, ContentTypeService $contentTypeService)
    {
        parent::__construct($browser);
        $this->contentTypeService = $contentTypeService;
    }

    public function verifyListIsEmpty(): void
    {
        Assert::assertTrue($this->adminList->isEmpty());
    }

    public function edit(string $contentTypeName): void
    {
        $this->adminList->editItem($contentTypeName);
    }

    public function goTo(string $contentTypeName): void
    {
        $this->adminList->clickItem(['Name' => $contentTypeName]);
    }

    public function createNew(): void
    {
        $this->getHTMLPage()->find($this->getLocator('createButton'))->click();
    }

    public function isContentTypeOnTheList($contentTypeName)
    {
    }

    public function delete(string $contentTypeName)
    {
        $this->adminList->delete($contentTypeName);
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
            new VisibleCSSLocator('listHeader', '.ez-table-header .ez-table-header__headline, header .ez-table__headline, header h5'),
            new VisibleCSSLocator('createButton', '.ez-icon-create'),
        ];
    }
}
