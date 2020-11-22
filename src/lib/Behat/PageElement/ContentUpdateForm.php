<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement;

use Behat\Mink\Session;
use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields\EzFieldElement;
use PHPUnit\Framework\Assert;

class ContentUpdateForm extends Component
{
    /** @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields\EzFieldElement[] */
    private $fieldTypeComponents;

    public function __construct(Session $session, array $fieldTypeComponents)
    {
        parent::__construct($session);
        $this->fieldTypeComponents = $fieldTypeComponents;
    }

    public function fillFieldWithValue(string $fieldName, array $value): void
    {
        $this->getField($fieldName)->setValue($value);
    }

    public function getField(string $fieldName): EzFieldElement
    {
        $fieldLocator = new CSSSelector('fieldLocator', sprintf($this->getSelector('nthField'), $this->getFieldPosition($fieldName)));
        $fieldtypeIdentifier = $this->getFieldtypeIdentifier($fieldLocator, $fieldName);

        foreach ($this->fieldTypeComponents as $fieldTypeComponent)
        {
            if ($fieldTypeComponent->getName() === $fieldtypeIdentifier) {
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

    public function verifyFieldHasValue(array $fieldData): void
    {
        $this->getField($fieldData['label'])->verifyValue($fieldData);
    }

    public function closeUpdateForm(): void
    {
        $this->getHTMLPage()->find($this->getSelector('closeButton'))->click();
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertTrue($this->getHTMLPage()->find($this->getSelector('formElement'))->isVisible());
    }

    public function getName(): string
    {
        return 'Content update form';
    }

    protected function specifySelectors(): array
    {
        return [
            new CSSSelector('formElement', '[name=ezplatform_content_forms_content_edit]'),
            new CSSSelector('closeButton', '.ez-content-edit-container__close'),
            new CSSSelector('fieldLabel', '.ez-field-edit__label-wrapper label.ez-field-edit__label, .ez-field-edit__label-wrapper legend, .ez-card > .card-body > div > div > legend'),
            new CSSSelector('nthField', '.ez-card .card-body > div > div:nth-of-type(%s)'),
            new CSSSelector('noneditableFieldClass', 'ez-field-edit--eznoneditable'),
            new CSSSelector('fieldOfType', '.ez-field-edit--%s'),
        ];
    }

    private function getFieldtypeIdentifier(CSSSelector $fieldLocator, string $fieldName): string
    {
        throw new \Exception('gdzie to jest uzywane...');

        $isEditable = !$this->getHTMLPage()->find($fieldLocator)->hasClass($this->getSelector('noneditableFieldClass'));
        if (!$isEditable) {
            return strtolower($fieldName);
        }

        $fieldClass = $this->getHTMLPage()->find($fieldLocator)->getAttribute('class');
        preg_match('/ez-field-edit--ez[a-z]*/', $fieldClass, $matches);

        return explode('--', $matches[0])[1];
    }
}
