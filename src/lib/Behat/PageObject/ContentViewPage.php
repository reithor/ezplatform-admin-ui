<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use EzSystems\Behat\API\Facade\ContentFacade;
use EzSystems\Behat\Browser\Page\Browser;
use EzSystems\Behat\Browser\Page\Page;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Breadcrumb;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\ContentItemAdminPreview;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\ContentTypePicker;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Dialog;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\LanguagePicker;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\RightMenu;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\SubitemsList;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\UpperMenu;
use PHPUnit\Framework\Assert;

class ContentViewPage extends Page
{
    /** @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\RightMenu Element representing the right menu */
    private $rightMenu;

    /** @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\SubitemsList */
    private $subItemList;

    /** @var string */
    private $locationPath;
    /**
     * @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\ContentTypePicker
     */
    private $contentTypePicker;
    /**
     * @var ContentUpdateItemPage
     */
    private $contentUpdatePage;

    /** @var string */
    private $expectedContentType;
    /**
     * @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\LanguagePicker
     */
    private $languagePicker;
    /**
    /**
     * @var string
     */
    private $expectedContentName;
    /**
     * @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\Dialog
     */
    private $dialog;
    /**
     * @var \EzSystems\Behat\API\Facade\ContentFacade
     */
    private $contentFacade;
    private $route;
    /**
     * @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\Breadcrumb
     */
    private $breadcrumb;
    /**
     * @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\ContentItemAdminPreview
     */
    private $contentItemAdminPreview;

    public function __construct(
        Browser $browser,
        RightMenu $rightMenu,
        SubitemsList $subItemList,
        ContentTypePicker $contentTypePicker,
        ContentUpdateItemPage $contentUpdatePage,
        LanguagePicker $languagePicker,
        Dialog $dialog,
        ContentFacade $contentFacade,
        Breadcrumb $breadcrumb,
        ContentItemAdminPreview $contentItemAdminPreview
    ) {
        parent::__construct($browser);

        $this->rightMenu = $rightMenu;
        $this->subItemList = $subItemList;
        $this->subItemList->shouldHaveGridViewEnabled($this->hasGridViewEnabledByDefault());
        $this->contentTypePicker = $contentTypePicker;
        $this->contentUpdatePage = $contentUpdatePage;
        $this->languagePicker = $languagePicker;
        $this->dialog = $dialog;
        $this->contentFacade = $contentFacade;
        $this->breadcrumb = $breadcrumb;
        $this->contentItemAdminPreview = $contentItemAdminPreview;
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

        $this->subItemList->clickListElement($contentName, $contentType);

        $this->setExpectedLocationPath(sprintf('%s/%s', $this->locationPath, $contentName));
        $this->verifyIsLoaded();
    }

    public function navigateToPath(string $path): void
    {
        throw new \Exception('jak najmniej tego uzywac...');
        // $pathArray = explode('/', $path);
        // $menuTab = $pathArray[0] === EnvironmentConstants::get('ROOT_CONTENT_NAME') ? 'Content structure' : $pathArray[0];

        // $upperMenu = ElementFactory::createElement($this->context, UpperMenu::ELEMENT_NAME);
        // $upperMenu->goToTab('Content');
        // $upperMenu->goToSubTab($menuTab);

        // $pathSize = count($pathArray);
        // if ($pathSize > 1) {
        //     for ($i = 1; $i < $pathSize; ++$i) {
        //         $contentPage = PageObjectFactory::createPage($this->context, self::PAGE_NAME, $pathArray[$i - 1]);
        //         $contentPage->verifyIsLoaded();
        //         $contentPage->subItemList->table->clickListElement($pathArray[$i]);
        //     }
        // }
    }

    private function hasGridViewEnabledByDefault(): bool
    {
        return $this->expectedContentName === 'Media';
    }

    public function setExpectedLocationPath(string $locationPath)
    {
        $content = $this->contentFacade->getContentByLocationURL($locationPath);

        $this->locationPath = $locationPath;
        $this->expectedContentType = $content->getContentType()->getName();
        $this->expectedContentName = $content->getName();
        $this->route = sprintf('/view/content/%s/full/1/%s', $content->id, $content->contentInfo->getMainLocation()->id);
    }

    public function verifyIsLoaded(): void
    {
        $this->subItemList->verifyIsLoaded();
        $this->rightMenu->verifyIsLoaded();

        Assert::assertContains(
            $this->expectedContentName,
            $this->breadcrumb->getBreadcrumb(),
            'Breadcrumb shows invalid path'
        );

        Assert::assertEquals(
            $this->expectedContentName,
            $this->getHTMLPage()->find($this->getLocator('pageTitle'))->getText()
        );

        Assert::assertEquals(
            $this->expectedContentType,
            $this->getHTMLPage()->find($this->getLocator('contentType'))->getText()
        );
    }

    public function getName(): string
    {
        return 'Content view';
    }

    public function editContent(?string $language)
    {
        $this->rightMenu->clickButton('Edit');

        if ($this->languagePicker->isVisible()) {
            $availableLanguages = $this->languagePicker->getLanguages();
            Assert::assertGreaterThan(1, count($availableLanguages));
            Assert::assertContains($language, $availableLanguages);
            $this->languagePicker->chooseLanguage($language);
        }
    }

    public function isChildElementPresent(array $parameters): bool
    {
        return $this->subItemList->isElementInTable($parameters);
    }

    public function sendToTrash()
    {
        $this->rightMenu->clickButton('Send to Trash');
        $this->dialog->confirm();
    }

    public function verifyFieldHasValues(string $fieldLabel, array $expectedFieldValues, ?string $fieldTypeIdentifier)
    {
        $this->contentItemAdminPreview->verifyFieldHasValues($fieldLabel, $expectedFieldValues, $fieldTypeIdentifier);
    }

    protected function specifyLocators(): array
    {
        return [
            new VisibleCSSLocator('pageTitle', '.ez-page-title h1'),
            new VisibleCSSLocator('contentType', '.ez-page-title h4'),
        ];
    }

    protected function getRoute(): string
    {
        return $this->route;
    }
}
