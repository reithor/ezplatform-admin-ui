<?php


namespace src\lib\Behat\PageObject;


use EzSystems\Behat\API\ContentData\FieldTypeNameConverter;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\AdminUpdateItemPage;
use PHPUnit\Framework\Assert;

class ContentTypeUpdatePage extends AdminUpdateItemPage
{
    private const NEW_FIELD_TITLE_PATTERN = 'New FieldDefinition (%s)';

    public function fillFieldWithValue(string $fieldName, $value, ?string $containerName = null): void
    {
        $newContainerName = sprintf(self::NEW_FIELD_TITLE_PATTERN, FieldTypeNameConverter::getFieldTypeIdentifierByName($containerName));
        $fieldElement = $this->getField($fieldName, $newContainerName);
        $fieldElement->setValue($value);
    }

    public function getFieldDefinitionContainerLocator(string $containerName): string
    {
        $containerIndex = $this->context->getElementPositionByText($containerName, $this->fields['fieldDefinitionName']);

        return sprintf($this->fields['fieldDefinitionContainer'], $containerIndex);
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

    public function expandFieldDefinition(string $fieldName): void
    {
        $container = $this->context->findElement($this->getFieldDefinitionContainerLocator(sprintf($this::NEW_FIELD_TITLE_PATTERN, FieldTypeNameConverter::getFieldTypeIdentifierByName($fieldName))));
        Assert::assertNotNull($container, sprintf('Definition for field %s not found', $fieldName));

        if (strpos($container->getAttribute('class'), $this->fields['fieldCollapsed']) !== false) {
            $container->find('css', $this->fields['fieldDefinitionToggler'])->click();
        }
    }

    public function specifyLocators(): array
    {
        return array_merge(parent::specifyLocators(), [
            new VisibleCSSLocator('fieldTypesList', '#ezplatform_content_forms_contenttype_update_fieldTypeSelection'),
            new VisibleCSSLocator('addFieldDefinition', 'ezplatform_content_forms_contenttype_update_addFieldDefinition'),
            new VisibleCSSLocator('fieldDefinitionContainer', '.ez-card--toggle-group:nth-child(%s)'),
            new VisibleCSSLocator('fieldDefinitionName', '.ez-card--toggle-group .ez-card__header .form-check-label'),
            new VisibleCSSLocator('fieldBody', 'ez-card__body'),
            new VisibleCSSLocator('fieldCollapsed', 'ez-card--collapsed'),
            new VisibleCSSLocator('fieldDefinitionToggler', '.ez-card__body-display-toggler'),
        ]);
    }

}
