<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use Behat\Mink\Session;
use EzSystems\Behat\Browser\Page\Browser;
use EzSystems\Behat\Browser\Page\Page;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\AdminList;
use FriendsOfBehat\SymfonyExtension\Mink\MinkParameters;
use PHPUnit\Framework\Assert;

class LanguagesPage extends Page
{
    /** @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\AdminList */
    protected $adminList;

    public function __construct(Browser $browser, AdminList $adminList)
    {
        parent::__construct($browser);
        $this->adminList = $adminList;
    }

    public function edit(string $languageName): void
    {
        $this->adminList->editItem(['Name' => $languageName]);
    }

    public function create(): void
    {
        $this->getHTMLPage()->find($this->getLocator('createButton'))->click();
    }

    public function delete(string $languageName): void
    {
        $this->adminList->selectItem(['Name' => $languageName]);
        $this->getHTMLPage()->find($this->get('trashButton'))->click();
    }

    public function verifyLanguageAttribute(string $label, string $value, string $itemName): void
    {
//        // TODO
//        Assert::assertEquals(
//            $value,
//            $this->adminList->
//        );
    }

    protected function getRoute(): string
    {
        return 'language/list';
    }

    public function verifyIsLoaded(): void
    {
        $this->adminList->verifyIsLoaded();
        Assert::assertEquals(
            'Content',
            $this->getHTMLPage()->find($this->getLocator('pageTitle'))->getText()
        );
        Assert::assertEquals(
            sprintf("Content Types in '%s'", $this->expectedName),
            $this->getHTMLPage()->find($this->getLocator('listHeader'))->getText()
        );
    }

    public function getName(): string
    {
        return 'Languages';
    }

    protected function specifyLocators(): array
    {
        return [
            new VisibleCSSLocator('pageTitle',  '.ez-header h1'),
            new VisibleCSSLocator('listHeader', '.ez-table-header .ez-table-header__headline, header .ez-table__headline, header h5'),
            new VisibleCSSLocator('createButton', '.ez-icon-create'),
            new VisibleCSSLocator('trashButton', '.ez-icon-trash,button[data-original-title^="Delete"]'),
        ];
    }
}
