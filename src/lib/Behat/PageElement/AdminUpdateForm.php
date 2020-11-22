<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement;

use EzSystems\Behat\API\ContentData\FieldTypeNameConverter;
use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields\DefaultFieldElement;
use PHPUnit\Framework\Assert;

class AdminUpdateForm extends Component
{
    private const NEW_FIELD_TITLE_PATTERN = 'New FieldDefinition (%s)';

    public function fillFieldWithValue(string $fieldName, $value, ?string $containerName = null): void
    {
        $newContainerName = (!$containerName) ? $containerName : sprintf(self::NEW_FIELD_TITLE_PATTERN, FieldTypeNameConverter::getFieldTypeIdentifierByName($containerName));
        $fieldElement = $this->getField($fieldName, $newContainerName);
        $fieldElement->setValue($value);
    }

    public function verifyFieldHasValue(string $fieldName, $value, ?string $containerName = null): void
    {
        $fieldElement = $this->getField($fieldName, $containerName);
        Assert::assertEquals(
            $value,
            $fieldElement->getValue(),
            'Field has wrong value'
        );
    }

    public function getField(string $fieldName, ?string $containerName = null): DefaultFieldElement
    {
        if ($containerName !== null) {
            $container = $this->getFieldDefinitionContainerLocator($containerName);
        } else {
            $container = $this->fields['mainFormSection'];
        }

        return ElementFactory::createElement($this->context, DefaultFieldElement::ELEMENT_NAME, $fieldName, $container);
    }

    public function selectFieldDefinition(string $fieldName): void
    {
        $this->context->findElement($this->fields['fieldTypesList'], $this->defaultTimeout)->selectOption($fieldName);
    }

    public function clickAddFieldDefinition(): void
    {
        $this->context->pressButton($this->fields['addFieldDefinition']);
    }

    public function verifyNewFieldDefinitionFormExists(string $fieldName): void
    {
        $form = $this->context->getElementByText(sprintf(self::NEW_FIELD_TITLE_PATTERN, FieldTypeNameConverter::getFieldTypeIdentifierByName($fieldName)), $this->fields['fieldDefinitionName']);
        if ($form === null) {
            throw new \Exception('Field definition not added to the form.');
        }
    }

    public function clickButton(string $label, int $indexOfButton = 0): void
    {
        $formButtons = $this->context->findAllElements($this->fields['button'], $this->context->findElement($this->fields['mainFormSection']));
        $filteredButtons = array_values(array_filter($formButtons, function ($element) use ($label) { return $element->getText() === $label; }));

        $filteredButtons[$indexOfButton]->click();
    }

    public function expandFieldDefinition(string $fieldName): void
    {
        $container = $this->context->findElement($this->getFieldDefinitionContainerLocator(sprintf($this::NEW_FIELD_TITLE_PATTERN, FieldTypeNameConverter::getFieldTypeIdentifierByName($fieldName))));
        Assert::assertNotNull($container, sprintf('Definition for field %s not found', $fieldName));

        if (strpos($container->getAttribute('class'), $this->fields['fieldCollapsed']) !== false) {
            $container->find('css', $this->fields['fieldDefinitionToggler'])->click();
        }
    }

    public function getFieldDefinitionContainerLocator(string $containerName): string
    {
        $containerIndex = $this->context->getElementPositionByText($containerName, $this->fields['fieldDefinitionName']);

        return sprintf($this->fields['fieldDefinitionContainer'], $containerIndex);
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertTrue($this->getHTMLPage()->find($this->getSelector('formElement'))->isVisible());
    }

    public function getName(): string
    {
        return 'Admin update form';
    }

    protected function specifySelectors(): array
    {
        return [
            new CSSSelector('formElement', '.form-group'),
            new CSSSelector('mainFormSection', 'form'),
            new CSSSelector('richTextSelector', '.ez-data-source__richtext'),
            new CSSSelector('fieldTypesList', '#ezplatform_content_forms_contenttype_update_fieldTypeSelection'),
            new CSSSelector('addFieldDefinition', 'ezplatform_content_forms_contenttype_update_addFieldDefinition'),
            new CSSSelector('fieldDefinitionContainer', '.ez-card--toggle-group:nth-child(%s)'),
            new CSSSelector('fieldDefinitionName', '.ez-card--toggle-group .ez-card__header .form-check-label'),
            new CSSSelector('fieldBody', 'ez-card__body'),
            new CSSSelector('fieldCollapsed', 'ez-card--collapsed'),
            new CSSSelector('fieldDefinitionToggler', '.ez-card__body-display-toggler'),
            new CSSSelector('closeButton', '.ez-content-edit-container__close'),
            new CSSSelector('button', 'button'),
        ];
    }
}
