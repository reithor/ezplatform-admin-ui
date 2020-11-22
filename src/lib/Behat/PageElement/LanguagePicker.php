<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement;

use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Element\NodeElement;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use PHPUnit\Framework\Assert;

class LanguagePicker extends Component
{
    public function chooseLanguage($language): void
    {
        $this->getHTMLPage()->findAll($this->getSelector('languageSelector'))->getByText($language)->click();
    }

    public function getLanguages(): array
    {
        return $this->getHTMLPage()->findAll($this->getSelector('languageSelector'))->map(
            function (NodeElement $element) {
                return $element->getText();
            }
        );
    }

    public function isVisible(): bool
    {
        return $this->getHTMLPage()->findAll($this->getSelector('languagePickerSelector'))->any();
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertTrue($this->isVisible());
    }

    protected function specifySelectors(): array
    {
        return [
            new CSSSelector('languagePickerSelector', '.ez-extra-actions--edit:not(.ez-extra-actions--hidden) #content_edit_language'),
            new CSSSelector('languageSelector', '.ez-extra-actions--edit:not(.ez-extra-actions--hidden) #content_edit_language .form-check-label'),
        ];
    }
}
