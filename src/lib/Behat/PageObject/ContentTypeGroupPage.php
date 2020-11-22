<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use Behat\Mink\Session;
use EzSystems\Behat\Browser\Page\Page;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\AdminList;
use FriendsOfBehat\SymfonyExtension\Mink\MinkParameters;
use PHPUnit\Framework\Assert;

class ContentTypeGroupPage extends Page
{
    /** @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\AdminList  */
    protected $adminList;

    /** @var string */
    protected $expectedName;

    public function __construct(Session $session, MinkParameters $minkParameters, AdminList $adminList)
    {
        parent::__construct($session, $minkParameters);
        $this->adminList = $adminList;
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

    public function create(): void
    {
        $this->getHTMLPage()->find($this->getSelector('createButton'))->click();
    }

    protected function getRoute(): string
    {
        return '/contenttypegroup/<id>'; // TODO: Get ContentTypeGroupID z nazwy
    }

    public function verifyIsLoaded(): void
    {
        $this->adminList->verifyIsLoaded();
        Assert::assertEquals(
            'Content',
            $this->getHTMLPage()->find($this->getSelector('pageTitle'))->getText()
        );
        Assert::assertEquals(
            sprintf("Content Types in '%s'", $this->expectedName),
            $this->getHTMLPage()->find($this->getSelector('listHeader'))->getText()
        );
    }
    
    public function setExpectedContentTypeGroupName(string $expectedName) {
        $this->expectedName = $expectedName;
    }

    public function getName(): string
    {
        return 'Content Type group';
    }

    protected function specifySelectors(): array
    {
        return [
            new CSSSelector('pageTitle',  '.ez-header h1'),
            new CSSSelector('listHeader', '.ez-table-header .ez-table-header__headline, header .ez-table__headline, header h5'),
            new CSSSelector('createButton', '.ez-icon-create'),
        ];
    }
}
