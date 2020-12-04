<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use EzSystems\Behat\Browser\Element\NodeElement;
use EzSystems\Behat\Browser\Locator\XPathLocator;
use EzSystems\Behat\Browser\Page\Browser;
use EzSystems\Behat\Browser\Page\Page;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
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

    public function fillFieldWithValue(string $fieldName, $value): void
    {
        $this->getField($fieldName)->setValue($value);
    }

    public function clickButton(string $label): void
    {
        $this->getHTMLPage()
            ->findAll($this->getLocator('button'))
            ->getByText($label)
            ->click();
    }

    public function verifyIsLoaded(): void
    {
        $this->rightMenu->verifyIsLoaded();
        Assert::assertTrue($this->getHTMLPage()->find($this->getLocator('formElement'))->isVisible());
    }

    protected function specifyLocators(): array
    {
        return [
            new VisibleCSSLocator('formElement', '.form-group'),
            new VisibleCSSLocator('closeButton', '.ez-content-edit-container__close'),
            new VisibleCSSLocator('button', 'button'),
            new VisibleCSSLocator('field', '.form-group'),
            new VisibleCSSLocator('fieldInput', 'input'),
        ];
    }

    private function getField(string $fieldName): NodeElement
    {
        return $this->getHTMLPage()
            ->findAll(new XPathLocator('input', '//label/..'))
            ->getByText($fieldName)
            ->find(new VisibleCSSLocator('input', 'input'));
    }
}
