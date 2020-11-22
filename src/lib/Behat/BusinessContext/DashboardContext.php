<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\BusinessContext;

use Behat\Behat\Context\Context;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\UpperMenu;
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

    public function __construct(UpperMenu $upperMenu, DashboardPage $dashboardPage)
    {
        $this->upperMenu = $upperMenu;
        $this->dashboardPage = $dashboardPage;
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
        Assert::assertTrue($this->isDraftOnList($draftName));
    }

    /**
     * @Then there's no draft :draftName on Dashboard list
     */
    public function goingToDashboardISeeNoDraft(string $draftName): void
    {
        Assert::assertFalse($this->isDraftOnList($draftName));
    }

    /**
     * @Given I start editing content draft :contentDraftName
     */
    public function startEditingContentDraft(string $contentDraftName): void
    {
        $this->dashboardPage->editDraft($contentDraftName);
    }

    private function isDraftOnList(string $draftName): bool
    {
        if ($this->dashboardPage->isListEmpty()) {
            return false;
        }

        // REFACTOR ME
        return $this->dashboardPage->dashboardTable->isElementOnCurrentPage($draftName);
    }
}
