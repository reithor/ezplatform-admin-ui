<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use EzSystems\Behat\Browser\Locator\CSSLocator;
use PHPUnit\Framework\Assert;

class URL extends FieldTypeComponent
{
    public function setValue(array $parameters): void
    {
        $this->setSpecificFieldValue('url', $parameters['url']);
        $this->setSpecificFieldValue('text', $parameters['text']);
    }

    public function setSpecificFieldValue(string $coordinateName, string $value): void
    {
        $fieldSelector = $this->parentSelector->withDescendant($this->getLocator($coordinateName));

        $this->getHTMLPage()->find($fieldSelector)->setValue($value);
    }

    public function getValue(): array
    {
        return [
            'url' => $this->getSpecificFieldValue('url'),
            'text' => $this->getSpecificFieldValue('text'),
            ];
    }

    public function getSpecificFieldValue(string $coordinateName): string
    {
        $fieldSelector = $this->parentSelector->withDescendant($this->getLocator($coordinateName));

        return $this->getHTMLPage()->find($fieldSelector)->getValue();
    }

    public function verifyValueInEditView(array $value): void
    {
        Assert::assertEquals(
            $value['url'],
            $this->getValue()['url'],
            sprintf('Field %s has wrong value', $value['label'])
        );
        Assert::assertEquals(
            $value['text'],
            $this->getValue()['text'],
            sprintf('Field %s has wrong value', $value['label'])
        );
    }

    public function verifyValueInItemView(array $values): void
    {
        Assert::assertEquals(
            $values['text'],
            $this->getHTMLPage()->find($this->parentSelector)->getText(),
            'Field has wrong value'
        );

        $urlSelector = $this->parentSelector->withDescendant(new CSSLocator('', 'a'));

        Assert::assertEquals(
            $values['url'],
            $this->getHTMLPage()->find($urlSelector)->getAttribute('href'),
            'Field has wrong value'
        );
    }

    protected function specifyLocators(): array
    {
        return [
            new CSSLocator('url', '#ezplatform_content_forms_content_edit_fieldsData_ezurl_value_link'),
            new CSSLocator('text', '#ezplatform_content_forms_content_edit_fieldsData_ezurl_value_text'),
        ];
    }

    public function getFieldTypeIdentifier(): string
    {
        return 'ezurl';
    }
}
