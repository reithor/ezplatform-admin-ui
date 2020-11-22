<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use EzSystems\Behat\Browser\Page\Page;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use PHPUnit\Framework\Assert;

class AdminUpdateItemPage extends Page
{
    /** @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\RightMenu */
    public $rightMenu;

    public function getFieldValue($label)
    {
        return $this->getField($label)->getValue();
    }

    protected function getRoute(): string
    {
        throw new \Exception('Update Page cannot be opened on its own!');
    }

    public function getName(): string
    {
        return 'Admin item update';
    }

    public function fillFieldWithValue(string $fieldName, $value, ?string $containerName = null): void
    {
        $fieldElement = $this->getField($fieldName, null);
        $fieldElement->setValue($value);
    }

    public function clickButton(string $label, int $indexOfButton = 0): void
    {
        $formButtons = $this->context->findAllElements($this->fields['button'], $this->getHTMLPage()->find($this->getSelector('mainFormSection')));
        $filteredButtons = array_values(array_filter($formButtons, function ($element) use ($label) { return $element->getText() === $label; }));

        $filteredButtons[$indexOfButton]->click();
    }

    public function verifyIsLoaded(): void
    {
        $this->rightMenu->verifyIsLoaded();
        Assert::assertTrue($this->getHTMLPage()->find($this->getSelector('formElement'))->isVisible());
    }

    protected function specifySelectors(): array
    {
        return [
            new CSSSelector('formElement', '.form-group'),
            new CSSSelector('mainFormSection', 'form'),
            new CSSSelector('closeButton', '.ez-content-edit-container__close'),
            new CSSSelector('button', 'button'),
        ];
    }
}
