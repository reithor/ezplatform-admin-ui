<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use EzSystems\Behat\Browser\Context\OldBrowserContext;
use EzSystems\Behat\Browser\Page\Page;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\AdminList;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables\DoubleHeaderTable;
use EzSystems\Behat\Browser\Factory\ElementFactory;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables\SimpleTable;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables\SystemInfoTable;
use PHPUnit\Framework\Assert;

class ContentTypePage extends Page
{
    /** @var string locator for container of Content list */
    public $contentFieldDefinitionsListLocator = '.ez-fieldgroup:nth-of-type(2)';

    /** @var string locator for container of Content list */
    public $globalPropertiesTableLocator = '.ez-table--list';

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

//    public function __construct(OldBrowserContext $context, string $contentTypeName)
//    {

//        $this->contentTypeAdminList = ElementFactory::createElement(
//            $this->context,
//            AdminList::ELEMENT_NAME,
//            'Content Type',
//            SimpleTable::ELEMENT_NAME
//        );
//        $this->globalPropertiesTable = ElementFactory::createElement(
//            $this->context,
//            SystemInfoTable::ELEMENT_NAME,
//            $this->globalPropertiesTableLocator
//        );
//        $this->fieldsAdminList = ElementFactory::createElement(
//            $this->context,
//            AdminList::ELEMENT_NAME,
//            'Content',
//            DoubleHeaderTable::ELEMENT_NAME,
//            $this->contentFieldDefinitionsListLocator
//        );
//        $this->pageTitle = $contentTypeName;
//        $this->pageTitleLocator = '.ez-header h1';
//    }
    /**
     * @var string
     */
    private $expectedContentTypeName;

    protected function getRoute(): string
    {
        return '/contenttypegroup/'; //TODO: load content type
    }

    public function getName(): string
    {
        return 'Content Type';
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertEquals(
            sprintf('ContentType: %s', $this->expectedContentTypeName),
            $this->getHTMLPage()->find($this->getSelector('pageTitle'))->getText()
        );

        $this->contentTypeAdminList->verifyIsLoaded();
        $this->fieldsAdminList->verifyIsLoaded();    
    }
    
    public function setExpectedContentTypeName(string $contentTypeName): void
    {
        $this->expectedContentTypeName = $contentTypeName;
    }

    protected function specifySelectors(): array
    {
        return [
            new CSSSelector('pageTitle', '.ez-header h1'),
        ];
    }
}
