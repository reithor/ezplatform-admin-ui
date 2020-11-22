<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use Behat\Mink\Session;
use EzSystems\Behat\Browser\Context\OldBrowserContext;
use EzSystems\Behat\Browser\Page\Page;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\ContentUpdateForm;
use EzSystems\Behat\Browser\Factory\ElementFactory;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\RightMenu;
use FriendsOfBehat\SymfonyExtension\Mink\MinkParameters;
use PHPUnit\Framework\Assert;

class ContentUpdateItemPage extends Page
{
    /**
     * @var ContentUpdateForm
     */
    private $contentUpdateForm;

    /**
     * @var RightMenu
     */
    private $rightMenu;

    private $pageTitle;

    public function __construct(
        Session $session,
        MinkParameters $minkParameters,
        RightMenu $rightMenu,
        ContentUpdateForm $contentUpdateForm
    )
    {
        parent::__construct($session, $minkParameters);
        $this->rightMenu = $rightMenu;
        $this->contentUpdateForm = $contentUpdateForm;
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertEquals(
            $this->pageTitle,
            $this->getHTMLPage()->find($this->getSelector('pageTitle'))->getText()
        );

        $this->rightMenu->verifyIsLoaded();
        $this->contentUpdateForm->verifyIsLoaded();
    }

    public function setExpectedPageTitle(string $title)
    {
        $this->pageTitle = $title;
    }

    public function getName(): string
    {
        return 'Content Update';
    }

    public function fillFieldWithValue($label, array $values)
    {
        $this->contentUpdateForm->fillFieldWithValue($label, $values);
    }

    public function getField(string $fieldName)
    {
        return $this->contentUpdateForm->getField($fieldName);
    }

    public function close()
    {
        $this->contentUpdateForm->closeUpdateForm();
    }

    protected function specifySelectors(): array
    {
        return [
            new CSSSelector('pageTitle', '.ez-content-edit-page-title__title'),
        ];
    }

    protected function getRoute(): string
    {
        throw new \Exception('This page cannot be opened on its own!');
    }
}
