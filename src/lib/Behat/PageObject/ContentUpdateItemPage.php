<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use EzSystems\Behat\Browser\Page\Browser;
use EzSystems\Behat\Browser\Page\Page;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields\FieldTypeComponent;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\RightMenu;
use PHPUnit\Framework\Assert;

class ContentUpdateItemPage extends Page
{
    /**
     * @var RightMenu
     */
    private $rightMenu;

    private $pageTitle;
    /**
     * @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields\FieldTypeComponent[]
     */
    private $fieldTypeComponents;

    public function __construct(
        Browser $browser,
        RightMenu $rightMenu,
        iterable $fieldTypeComponents
    )
    {
        parent::__construct($browser);
        $this->rightMenu = $rightMenu;
        $this->fieldTypeComponents = iterator_to_array($fieldTypeComponents);
    }

    public function verifyIsLoaded(): void
    {
        if ($this->pageTitle) {
            Assert::assertEquals(
                $this->pageTitle,
                $this->getHTMLPage()->find($this->getSelector('pageTitle'))->getText()
            );
        }

        $this->rightMenu->verifyIsLoaded();
        Assert::assertTrue($this->getHTMLPage()->find($this->getSelector('formElement'))->isVisible());
    }

    public function setExpectedPageTitle(string $title)
    {
        $this->pageTitle = $title;
    }

    public function getName(): string
    {
        return 'Content Update';
    }

    public function fillFieldWithValue($label, array $value)
    {
        $this->getField($label)->setValue($value);
    }

    public function close()
    {
        $this->getHTMLPage()->find($this->getSelector('closeButton'))->click();
    }

    protected function specifySelectors(): array
    {
        return [
            new CSSSelector('pageTitle', '.ez-content-edit-page-title__title'),
            new CSSSelector('formElement', '[name=ezplatform_content_forms_content_edit]'),
            new CSSSelector('closeButton', '.ez-content-edit-container__close'),
            new CSSSelector('fieldLabel', '.ez-field-edit__label-wrapper label.ez-field-edit__label, .ez-field-edit__label-wrapper legend, .ez-card > .card-body > div > div > legend'),
            new CSSSelector('nthField', '.ez-card .card-body > div > div:nth-of-type(%s)'),
            new CSSSelector('noneditableFieldClass', 'ez-field-edit--eznoneditable'),
            new CSSSelector('fieldOfType', '.ez-field-edit--%s'),
        ];
    }

    protected function getRoute(): string
    {
        throw new \Exception('This page cannot be opened on its own!');
    }

    public function getField(string $fieldName): FieldTypeComponent
    {
        $fieldLocator = new CSSSelector('', sprintf($this->getSelector('nthField')->getSelector(), $this->getFieldPosition($fieldName)));
        $fieldtypeIdentifier = $this->getFieldtypeIdentifier($fieldLocator, $fieldName);

        foreach ($this->fieldTypeComponents as $fieldTypeComponent)
        {
            if ($fieldTypeComponent->getFieldTypeIdentifier() === $fieldtypeIdentifier) {
                $fieldTypeComponent->setParentContainer($fieldLocator);

                return $fieldTypeComponent;
            }
        }
    }

    protected function getFieldPosition(string $fieldName): int
    {
        $fieldElements = $this->getHTMLPage()->findAll($this->getSelector('fieldLabel'));
        $fieldPosition = 1;

        foreach ($fieldElements as $fieldElement)
        {
            if ($fieldElement->getText() === $fieldName) {
                return $fieldPosition;
            }

            ++$fieldPosition;
        }

        Assert::fail(sprintf('Field %s not found.', $fieldName));
    }

    public function verifyFieldHasValue(string $label, array $fieldData): void
    {
        $this->getField($label)->verifyValueInEditView($fieldData);
    }

    private function getFieldtypeIdentifier(CSSSelector $fieldLocator, string $fieldName): string
    {
        $isEditable = !$this->getHTMLPage()
            ->find($fieldLocator)
            ->hasClass($this->getSelector('noneditableFieldClass')->getSelector());

        if (!$isEditable) {
            return strtolower($fieldName);
        }

        $fieldClass = $this->getHTMLPage()->find($fieldLocator)->getAttribute('class');
        preg_match('/ez-field-edit--ez[a-z]*/', $fieldClass, $matches);

        return explode('--', $matches[0])[1];
    }
}
