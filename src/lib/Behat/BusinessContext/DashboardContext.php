<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\BusinessContext;

use Behat\Behat\Context\Context;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\UpperMenu;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\ContentUpdateItemPage;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\DashboardPage;
use PHPUnit\Framework\Assert;

class DashboardContext implements Context
{
    /**
     * @var UpperMenu
     */
    private $upperMenu;
    /**
     * @var DashboardPage
     */
    private $dashboardPage;

    /**
     * @var ContentUpdateItemPage
     */
    private $contentUpdateItemPage;

    public function __construct(UpperMenu $upperMenu, DashboardPage $dashboardPage, ContentUpdateItemPage $contentUpdateItemPage)
    {
        $this->upperMenu = $upperMenu;
        $this->dashboardPage = $dashboardPage;
        $this->contentUpdateItemPage = $contentUpdateItemPage;
    }

    /**
     * @Given I go to dashboard
     */
    public function iGoToDashboard(): void
    {
        $this->upperMenu->goToDashboard();
    }

    /**
     * @Then there's draft :draftName on Dashboard list
     */
    public function goingToDashboardISeeDraft(string $draftName): void
    {
        Assert::assertTrue($this->dashboardPage->isDraftOnList($draftName));
    }

    /**
     * @Then there's no draft :draftName on Dashboard list
     */
    public function goingToDashboardISeeNoDraft(string $draftName): void
    {
        Assert::assertFalse($this->dashboardPage->isDraftOnList($draftName));
    }

    /**
     * @Given I start editing content draft :contentDraftName
     */
    public function startEditingContentDraft(string $contentDraftName): void
    {
        $this->dashboardPage->editDraft($contentDraftName);
        $this->contentUpdateItemPage->setExpectedPageTitle($contentDraftName);
        $this->contentUpdateItemPage->verifyIsLoaded();
    }
}
