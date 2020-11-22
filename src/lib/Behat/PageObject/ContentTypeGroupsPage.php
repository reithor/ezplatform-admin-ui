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

class ContentTypeGroupsPage extends Page
{
    /** @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\AdminList */
    protected $adminList;

    public function __construct(Session $session, MinkParameters $minkParameters, AdminList $adminList)
    {
        parent::__construct($session, $minkParameters);
        $this->adminList = $adminList;
    }

    public function edit(string $contentTypeGroupName): void
    {
        $this->adminList->editItem(['Name' => $contentTypeGroupName]);
    }

    public function create(): void
    {
        $this->getHTMLPage()->find($this->getSelector('createButton'))->click();
    }

    public function goTo(string $contentTypeGroupName): void
    {
        $this->adminList->clickItem(['Name' => $contentTypeGroupName]);
    }

    public function delete(string $contentTypeGroupName): void
    {
        $this->adminList->selectItem(['Name' => $contentTypeGroupName]);
        $this->getHTMLPage()->find($this->get('trashButton'))->click();
    }

    protected function getRoute(): string
    {
        return '/contenttypegroup/list';
    }

    public function verifyIsLoaded(): void
    {
        $this->adminList->verifyIsLoaded();
        Assert::assertEquals(
            'Content Type groups',
            $this->getHTMLPage()->find($this->getSelector('pageTitle'))->getText()
        );
        Assert::assertEquals(
            'Content Type groups',
            $this->getHTMLPage()->find($this->getSelector('listHeader'))->getText()
        );
    }

    public function getName(): string
    {
        return 'Content Type groups';
    }

    protected function specifySelectors(): array
    {
        return [
            new CSSSelector('pageTitle',  '.ez-header h1'),
            new CSSSelector('listHeader', '.ez-table-header .ez-table-header__headline, header .ez-table__headline, header h5'),
            new CSSSelector('createButton', '.ez-icon-create'),
            new CSSSelector('trashButton', '.ez-icon-trash,button[data-original-title^="Delete"]'),
        ];
    }
}
