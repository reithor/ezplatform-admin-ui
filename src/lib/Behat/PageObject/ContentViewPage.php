<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use Behat\Mink\Session;
use EzSystems\Behat\Browser\Page\Page;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use EzSystems\Behat\Core\Environment\EnvironmentConstants;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\ContentTypePicker;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\RightMenu;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\SubitemsList;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\UpperMenu;
use FriendsOfBehat\SymfonyExtension\Mink\MinkParameters;
use PHPUnit\Framework\Assert;

class ContentViewPage extends Page
{
    /** @var RightMenu Element representing the right menu */
    private $rightMenu;

    /** @var SubitemsList */
    private $subItemList;

    /** @var string */
    private $locationPath;
    /**
     * @var ContentTypePicker
     */
    private $contentTypePicker;
    /**
     * @var ContentUpdateItemPage
     */
    private $contentUpdatePage;

    /** @var string  */
    private $expectedContentType;

    public function __construct(
        Session $session,
        MinkParameters $minkParameters,
        RightMenu $rightMenu,
        SubitemsList $subItemList,
        ContentTypePicker $contentTypePicker,
        ContentUpdateItemPage $contentUpdatePage
    )
    {
        parent::__construct($session, $minkParameters);

        $this->rightMenu = $rightMenu;
        $this->subItemList = $subItemList;
        $this->subItemList->shouldHaveGridViewEnabled($this->hasGridViewEnabledByDefault());
        $this->contentTypePicker = $contentTypePicker;
        $this->contentUpdatePage = $contentUpdatePage;
    }

    public function startCreatingContent(string $contentTypeName): ContentUpdateItemPage
    {
        $this->rightMenu->clickButton('Create');
        $this->contentTypePicker->verifyIsLoaded();
        $this->contentTypePicker->select($contentTypeName);
        $this->contentUpdatePage->verifyIsLoaded();

        return $this->contentUpdatePage;
    }

    public function goToSubItem(string $contentName, string $contentType): void
    {
        if ($this->subItemList->canBeSorted()) {
            $this->subItemList->sortBy('Modified', false);
        }

        $this->subItemList->table->clickListElement($contentName, $contentType);

        $this->setExpectedLocationPath(sprintf('%s/%s', $this->locationPath, $contentName));
        $this->verifyIsLoaded();
    }

    public function navigateToPath(string $path): void
    {
        $pathArray = explode('/', $path);
        $menuTab = $pathArray[0] === EnvironmentConstants::get('ROOT_CONTENT_NAME') ? 'Content structure' : $pathArray[0];

        $upperMenu = ElementFactory::createElement($this->context, UpperMenu::ELEMENT_NAME);
        $upperMenu->goToTab('Content');
        $upperMenu->goToSubTab($menuTab);

        $pathSize = count($pathArray);
        if ($pathSize > 1) {
            for ($i = 1; $i < $pathSize; ++$i) {
                $contentPage = PageObjectFactory::createPage($this->context, self::PAGE_NAME, $pathArray[$i - 1]);
                $contentPage->verifyIsLoaded();
                $contentPage->subItemList->table->clickListElement($pathArray[$i]);
            }
        }
    }

    private function hasGridViewEnabledByDefault(): bool
    {
        return $this->pageTitle === 'Media';
    }

    public function setExpectedLocationPath(string $locationPath)
    {
        $this->locationPath = $locationPath;
        $this->expectedContentType = ''; //TODO
    }

    public function verifyIsLoaded(): void
    {
        $this->subItemList->verifyIsLoaded();
        $this->rightMenu->verifyIsLoaded();

        Assert::assertEquals(
            $this->expectedContentType,
            $this->getHTMLPage()->find($this->getSelector('contentType'))->getText()
        );
    }

    public function getName(): string
    {
        return 'Content view';
    }

    protected function specifySelectors(): array
    {
        return [
            new CSSSelector('pageTitle', '.ez-page-title h1'),
            new CSSSelector('contentType', '.ez-page-title h4'),
        ];
    }

    protected function getRoute(): string
    {
        // TODO: resolve IDs from location path

        return '/view/content';
    }
}
