<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use eZ\Publish\API\Repository\ContentTypeService;
use EzSystems\Behat\Browser\Page\Browser;
use EzSystems\Behat\Browser\Page\Page;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;

class ContentTypePage extends Page
{
    /** @var string locator for container of Content list */
    public $contentFieldDefinitionsListLocator = '.ez-fieldgroup:nth-of-type(2)';

    /** @var string locator for container of Content list */
    public $globalPropertiesTableLocator = '.ez-table--list';

    private $contentTypeTableHeaders = ['Name', 'Identifier', 'Description'];

    /**
     * @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\AdminList
     */
    public $globalPropertiesTable;
    /**
     * @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\AdminList
     */
    public $fieldsAdminList;

    /**
     * @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\AdminList
     */
    public $contentTypeAdminList;

    /**
     * @var string
     */
    private $expectedContentTypeName;
    /**
     * @var ContentTypeService
     */
    private $contentTypeService;
    /**
     * @var mixed
     */
    private $expectedContenTypeGroupId;
    /**
     * @var mixed
     */
    private $expectedContenTypeId;

    public function __construct(Browser $browser, ContentTypeService $contentTypeService)
    {
        parent::__construct($browser);
        $this->contentTypeService = $contentTypeService;
    }

    protected function getRoute(): string
    {
        return sprintf(
            '/contenttypegroup/%d/contenttype/%d',
            $this->expectedContenTypeGroupId, $this->expectedContenTypeId
        );
    }

    public function getName(): string
    {
        return 'Content Type';
    }

    public function verifyIsLoaded(): void
    {
        $this->getHTMLPage()
            ->find($this->getLocator('pageTitle'))
            ->assert()->textEquals($this->expectedContentTypeName);

        $this->contentTypeAdminList->verifyIsLoaded();
        $this->fieldsAdminList->verifyIsLoaded();    
    }
    
    public function setExpectedContentTypeName(string $contentTypeName): void
    {
        $this->expectedContentTypeName = $contentTypeName;

        foreach ($this->contentTypeService->loadContentTypeGroups() as $group)
        {
            foreach ($this->contentTypeService->loadContentTypes($group) as $contentType) {
                if ($contentType->getName() === $contentTypeName) {
                    $this->expectedContenTypeId = $contentType->id;
                    $this->expectedContenTypeGroupId = $group->id;

                    return;
                }
            }
        }
    }

    protected function specifyLocators(): array
    {
        return [
            new VisibleCSSLocator('createButton', '.btn-icon .ez-icon-create'),
            new VisibleCSSLocator('pageTitle', '.ez-header h1'),
        ];
    }
}
