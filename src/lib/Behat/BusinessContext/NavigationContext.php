<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\BusinessContext;

use Behat\Behat\Context\Context;
use Exception;
use EzSystems\Behat\Browser\Page\PageRegistry;
use EzSystems\Behat\Core\Behat\ArgumentParser;
use EzSystems\Behat\Core\Environment\EnvironmentConstants;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Breadcrumb;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\UpperMenu;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\ContentViewPage;
use PHPUnit\Framework\Assert;

class NavigationContext implements Context
{
    /** @var \EzSystems\Behat\Core\Behat\ArgumentParser */
    private $argumentParser;

    /** @var \EzSystems\Behat\Browser\Page\PageRegistry[] */
    private $pageRegistry;

    /** @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\UpperMenu */
    private $upperMenu;

    /** @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\Breadcrumb */
    private $breadcrumb;

    /** @var \EzSystems\EzPlatformAdminUi\Behat\PageObject\ContentViewPage  */
    private $contentViewPage;

    public function __construct(
        ArgumentParser $argumentParser,
        UpperMenu $upperMenu,
        Breadcrumb $breadcrumb,
        ContentViewPage $contentViewPage,
        PageRegistry $pageRegistry
    )
    {
        $this->argumentParser = $argumentParser;
        $this->pageRegistry = $pageRegistry;
        $this->upperMenu = $upperMenu;
        $this->breadcrumb = $breadcrumb;
        $this->contentViewPage = $contentViewPage;
    }

    /**
     * @Given I open :pageName page
     */
    public function openPage(string $pageName): void
    {
        $this->pageRegistry->get($pageName)->open();
    }

    /**
     * @Given I try to open :pageName page
     */
    public function tryToOpenPage(string $pageName): void
    {
        $this->pageRegistry->get($pageName)->tryToOpen();
    }

    /**
     * @Then /^I should be on "?([^\"]*)"? page$/
     * @Then /^I should be on "?([^\"]*)"? "([^\"]*)" page$/
     */
    public function iAmOnPage(string $pageName, string $itemName = ''): void
    {
        if ($itemName !== '') {
            throw new Exception('zbadaj parametry do pagea');
        }

        $this->pageRegistry->get($pageName)->verifyIsLoaded();
    }

    /**
     * @Then I go to :tab tab
     * @Then I go to :subTab in :tab tab
     */
    public function iGoToTab(string $tabName, string $subTab = null): void
    {
        $this->upperMenu->goToTab($tabName);

        if ($subTab !== null) {
            $this->upperMenu->goToSubTab($subTab);
        }
    }

    /**
     * @When I click on :element on breadcrumb
     */
    public function iClickOnBreadcrumbLink(string $element): void
    {
        $this->breadcrumb->verifyIsLoaded();
        $this->breadcrumb->clickBreadcrumbItem($element);
    }

    /**
     * @Given I navigate to content :contentName of type :contentType in :path
     * @Given I navigate to content :contentName of type :contentType
     */
    public function iNavigateToContent(string $contentName, string $contentType, string $path = null)
    {
        if ($path !== null) {
            $path = $this->argumentParser->replaceRootKeyword($path);

            $this->contentViewPage->navigateToPath($path);
            $this->contentViewPage->setExpectedLocationPath($path);
            $this->contentViewPage->verifyIsLoaded();
        }
        $this->contentViewPage->goToSubItem($contentName, $contentType);
        $this->contentViewPage->verifyIsLoaded();
    }

    /**
     * @Given I navigate to content :contentName of type :contentType in root path
     */
    public function iNavigateToContentInRoot(string $contentName, string $contentType)
    {
        $path = $this->argumentParser->replaceRootKeyword('root');
        $this->iNavigateToContent($contentName, $contentType, $path);
    }

    /**
     * @Then breadcrumb shows :path path
     */
    public function verifyIfBreadcrumbShowsPath(string $path): void
    {
        Assert::assertEquals(
            str_replace('/', ' ', $path),
            $this->breadcrumb->getBreadcrumb(),
            'Breadcrumb shows invalid path'
        );
    }

    /**
     * @Then breadcrumb shows :path path under root path
     */
    public function verifyIfBreadcrumbShowsPathUnderRoot(string $path): void
    {
        $path = $this->argumentParser->replaceRootKeyword($path);
        $this->verifyIfBreadcrumbShowsPath($path);
    }

    /**
     * @Given I go to user notifications
     */
    public function iGoToUserNotifications()
    {
        $this->upperMenu->chooseFromUserDropdown('View Notifications');
    }

    /**
     * @Then I should be redirected to root in default view
     */
    public function iShouldBeRedirectedToRootInDefaultView(): void
    {
        // MOVE ME TO PAGE BUILDER
//        if (EnvironmentConstants::get('ROOT_CONTENT_TYPE') === 'Landing page') {
//            $previewType = PageObjectFactory::getPreviewType(EnvironmentConstants::get('ROOT_CONTENT_TYPE'));
//            $pageEditor = PageObjectFactory::createPage($this->browserContext, PageBuilderEditor::PAGE_NAME, $previewType);
//            $pageEditor->pagePreview->setTitle(EnvironmentConstants::get('ROOT_CONTENT_NAME'));
//            $pageEditor->waitUntilLoaded();
//            $pageEditor->verifyIsLoaded();
//        } else {
        $this->contentItemPage = $this->argumentParser->replaceRootKeyword('root');
        $this->contentItemPage->verifyIsLoaded();
    }
}
