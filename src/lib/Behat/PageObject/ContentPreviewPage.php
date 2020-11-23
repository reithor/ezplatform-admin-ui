<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use ErrorException;
use EzSystems\Behat\Browser\Page\Page;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use PHPUnit\Framework\Assert;

class ContentPreviewPage extends Page
{
    protected function getRoute(): string
    {
        throw new ErrorException('Preview page cannot be opened on its own!');
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertTrue($this->getHTMLPage()->find($this->getSelector('previewNav'))->isVisible());
    }

    public function getName(): string
    {
        return 'Content preview';
    }

    protected function specifySelectors(): array
    {
        return [
            new CSSSelector('previewNav', '.ez-preview__nav'),
            new CSSSelector('backToEdit', '.ez-preview__nav .ez-preview__item--back a'),
            new CSSSelector('title', '.ez-preview__nav .ez-preview__item--description'),
            new CSSSelector('desktop', '.ez-preview__nav .ez-preview__item--actions .ez-icon-desktop'),
            new CSSSelector('tablet', '.ez-preview__nav .ez-preview__item--actions .ez-icon-tablet'),
            new CSSSelector('mobile', '.ez-preview__nav .ez-preview__item--actions .ez-icon-mobile'),
            new CSSSelector('selectedView', '.ez-preview__action--selected'),
        ];
    }

    public function goBackToEditView(): void
    {
        $this->getHTMLPage()->find($this->getSelector('backToEdit'))->click();
    }

    public function goToView(string $viewName): void
    {
        if ($viewName !== $this->getActiveViewName()) {
            $this->getHTMLPage()->find($this->getSelector($viewName))->click();
        }
    }

    public function getActiveViewName(): string
    {
        return $this->getHTMLPage()->find($this->getSelector('selectedView'))->getAttribute('data-preview-mode');
    }
}
