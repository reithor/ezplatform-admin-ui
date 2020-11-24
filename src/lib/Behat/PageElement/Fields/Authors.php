<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use EzSystems\Behat\Browser\Selector\CSSSelector;
use PHPUnit\Framework\Assert;

class Authors extends FieldTypeComponent
{
    public function setValue(array $parameters): void
    {
        $name = $parameters['name'];
        $email = $parameters['email'];

        $nameSelector = CSSSelector::combine("%s %s", $this->parentSelector, $this->getSelector('nameFieldInput'));
        $emailSelector = CSSSelector::combine("%s %s", $this->parentSelector, $this->getSelector('emailFieldInput'));

        $this->getHTMLPage()->find($nameSelector)->setValue($name);
        $this->getHTMLPage()->find($emailSelector)->setValue($email);
    }

    public function getValue(): array
    {
        $nameSelector = CSSSelector::combine("%s %s", $this->parentSelector, $this->getSelector('nameFieldInput'));
        $emailSelector = CSSSelector::combine("%s %s", $this->parentSelector, $this->getSelector('emailFieldInput'));

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

    public function specifySelectors(): array
    {
        return [
            new CSSSelector('nameFieldInput', '.ez-data-source__field--name input'),
            new CSSSelector('emailFieldInput', '.ez-data-source__field--email input'),
            new CSSSelector('fieldValueInContentItemView', '.ez-content-field-value'),
        ];
    }
}
