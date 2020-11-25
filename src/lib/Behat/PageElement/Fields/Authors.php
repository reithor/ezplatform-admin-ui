<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use EzSystems\Behat\Browser\Locator\CSSLocator;
use PHPUnit\Framework\Assert;

class Authors extends FieldTypeComponent
{
    public function setValue(array $parameters): void
    {
        $name = $parameters['name'];
        $email = $parameters['email'];

        $nameSelector = $this->parentSelector->withDescendant($this->getLocator('nameFieldInput'));
        $emailSelector = $this->parentSelector->withDescendant($this->getLocator('emailFieldInput'));

        $this->getHTMLPage()->find($nameSelector)->setValue($name);
        $this->getHTMLPage()->find($emailSelector)->setValue($email);
    }

    public function getValue(): array
    {
        $nameSelector = $this->parentSelector->withDescendant($this->getLocator('nameFieldInput'));
        $emailSelector = $this->parentSelector->withDescendant($this->getLocator('emailFieldInput'));

        return [
            'name' => $this->getHTMLPage()->find($nameSelector)->getValue(),
            'email' => $this->getHTMLPage()->find($emailSelector)->getValue(),
        ];
    }

    public function verifyValueInEditView(array $value): void
    {
        $expectedName = $value['name'];
        $expectedEmail = $value['email'];

        $actualFieldValues = $this->getValue();
        Assert::assertEquals(
            $expectedName,
            $actualFieldValues['name'],
            sprintf('Field %s has wrong value', $value['label'])
        );

        Assert::assertEquals(
            $expectedEmail,
            $actualFieldValues['email'],
            sprintf('Field %s has wrong value', $value['label'])
        );
    }

    public function verifyValueInItemView(array $values): void
    {
        Assert::assertEquals(
            sprintf('%s <%s>', $values['name'], $values['email']),
            $this->getHTMLPage()->find($this->parentSelector)->getText(),
            'Field has wrong value'
        );
    }

    public function getFieldTypeIdentifier(): string
    {
        return 'ezauthor';
    }

    public function specifyLocators(): array
    {
        return [
            new CSSLocator('nameFieldInput', '.ez-data-source__field--name input'),
            new CSSLocator('emailFieldInput', '.ez-data-source__field--email input'),
            new CSSLocator('fieldValueInContentItemView', '.ez-content-field-value'),
        ];
    }
}
