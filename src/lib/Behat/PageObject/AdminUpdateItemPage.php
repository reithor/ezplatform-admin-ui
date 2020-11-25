<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use EzSystems\Behat\Browser\Page\Browser;
use EzSystems\Behat\Browser\Page\Page;
use EzSystems\Behat\Browser\Locator\CSSLocator;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\RightMenu;
use PHPUnit\Framework\Assert;

class AdminUpdateItemPage extends Page
{
    /** @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\RightMenu */
    protected $rightMenu;

    public function __construct(Browser $browser, RightMenu $rightMenu)
    {
        parent::__construct($browser);
        $this->rightMenu = $rightMenu;
    }

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
        $formButtons = $this->context->findAllElements($this->fields['button'], $this->getHTMLPage()->find($this->getLocator('mainFormSection')));
        $filteredButtons = array_values(array_filter($formButtons, function ($element) use ($label) { return $element->getText() === $label; }));

        $filteredButtons[$indexOfButton]->click();
    }

    public function verifyIsLoaded(): void
    {
        $this->rightMenu->verifyIsLoaded();
        Assert::assertTrue($this->getHTMLPage()->find($this->getLocator('formElement'))->isVisible());
    }

    protected function specifyLocators(): array
    {
        return [
            new CSSLocator('formElement', '.form-group'),
            new CSSLocator('mainFormSection', 'form'),
            new CSSLocator('closeButton', '.ez-content-edit-container__close'),
            new CSSLocator('button', 'button'),
        ];
    }
}
