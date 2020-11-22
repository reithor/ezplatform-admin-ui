<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement;

use EzSystems\Behat\API\ContentData\FieldTypeNameConverter;
use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Selector\CSSSelector;

class ContentField extends Component
{
    public const FIELD_TYPE_CLASS_REGEX = '/ez[a-z]*-field/';

    private function verifyFieldHasValue(string $label, array $fieldData)
    {
        throw new \Exception('kaj ja je...');

        $fieldIndex = $this->context->getElementPositionByText(sprintf('%s:', $label), $this->fields['fieldName']);
        $fieldLocator = sprintf(
            '%s %s',
            sprintf($this->fields['nthFieldContainer'], $fieldIndex),
            $this->fields['fieldValue']
        );

        if (array_key_exists('fieldType', $fieldData)) {
            $fieldType = $fieldData['fieldType'];
        } else {
            $fieldClass = $this->context->findElement(sprintf('%s %s', $fieldLocator, $this->fields['fieldValueContainer']))->getAttribute('class');
            $fieldTypeIdentifier = $this->getFieldTypeIdentifier($fieldClass);
            $fieldType = FieldTypeNameConverter::getFieldTypeNameByIdentifier($fieldTypeIdentifier);
        }

        $fieldElement = ElementFactory::createElement($this->context, $fieldType, $fieldLocator, $label);
        $fieldElement->verifyValueInItemView($fieldData);
    }

    private function getFieldTypeIdentifier(string $fieldClass): string
    {
        throw new \Exception('a gdzie to...');

        if (strpos($fieldClass, 'ez-table') !== false) {
            return 'ezmatrix';
        }

        if ($fieldClass === '') {
            return 'ezboolean';
        }

        preg_match($this::FIELD_TYPE_CLASS_REGEX, $fieldClass, $matches);
        $matchedValue = explode('-', $matches[0])[0];

        return $matchedValue;
    }

    public function verifyIsLoaded(): void
    {
    }

    public function getName(): string
    {
        return 'Content field';
    }

    public function getFieldValue(string $label)
    {
        return $this->verifyFieldHasValue();
    }

    protected function specifySelectors(): array
    {
        return [
            new CSSSelector('nthFieldContainer', 'div.ez-content-field:nth-of-type(%s)'),
            new CSSSelector('fieldName', '.ez-content-field-name'),
            new CSSSelector('fieldValue', '.ez-content-field-value'),
            new CSSSelector('fieldValueContainer', ':first-child'),
        ];
    }
}
