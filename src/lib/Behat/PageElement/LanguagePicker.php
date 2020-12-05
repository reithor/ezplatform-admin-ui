<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformAdminUi\Behat\PageElement;

use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Element\ElementInterface;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
use PHPUnit\Framework\Assert;

class LanguagePicker extends Component
{
    public function chooseLanguage($language): void
    {
        $this->getHTMLPage()->findAll($this->getLocator('languageSelector'))->getByText($language)->click();
    }

    public function getLanguages(): array
    {
        return $this->getHTMLPage()->findAll($this->getLocator('languageSelector'))->map(
            function (ElementInterface $element) {
                return $element->getText();
            }
        );
    }

    public function isVisible(): bool
    {
        return $this->getHTMLPage()->findAll($this->getLocator('languagePickerSelector'))->any();
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertTrue($this->isVisible());
    }

    protected function specifyLocators(): array
    {
        return [
            new VisibleCSSLocator('languagePickerSelector', '.ez-extra-actions--edit:not(.ez-extra-actions--hidden) #content_edit_language'),
            new VisibleCSSLocator('languageSelector', '.ez-extra-actions--edit:not(.ez-extra-actions--hidden) #content_edit_language .form-check-label'),
        ];
    }
}
