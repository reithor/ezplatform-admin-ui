<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\BusinessContext;

use Behat\Behat\Context\Context;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\ContentPreviewPage;
use EzSystems\Behat\Browser\Factory\PageObjectFactory;

class ContentPreviewContext implements Context
{
    /**
     * @var ContentPreviewPage
     */
    private $contentPreviewPage;

    public function __construct(ContentPreviewPage $contentPreviewPage)
    {
        $this->contentPreviewPage = $contentPreviewPage;
    }

    /**
     * @When I go to :viewName view in :contentName preview
     */
    public function iGoToPreview(string $viewName, string $contentName): void
    {
        $this->contentPreviewPage->verifyIsLoaded();
        $this->contentPreviewPage->goToView($viewName);
    }

    /**
     * @When I go back from content preview
     */
    public function iGoToBackFromPreview(): void
    {
        $this->contentPreviewPage->verifyIsLoaded();
        $this->contentPreviewPage->goBackToEditView();
    }
}
