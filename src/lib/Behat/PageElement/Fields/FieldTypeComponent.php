<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use PHPUnit\Framework\Assert;

abstract class FieldTypeComponent extends Component implements FieldTypeComponentInterface
{
    /**
     * @var CSSSelector
     */
    protected $parentSelector;

    public function setValue(array $parameters): void
    {
        $fieldSelector = CSSSelector::combine("%s %s", $this->parentSelector, $this->getSelector('fieldInput'));

        $value = $parameters['value'];
        $this->getHTMLPage()->find($fieldSelector)->setValue($value);
    }

    public function getValue(): array
    {
        $fieldSelector = CSSSelector::combine("%s %s", $this->parentSelector, $this->getSelector('fieldInput'));
        $value = $this->getHTMLPage()->find($fieldSelector)->getValue();

        return [$value];
    }

    public function verifyValueInItemView(array $values): void
    {
        Assert::assertEquals(
            $values['value'],
            $this->getHTMLPage()->find($this->parentSelector)->getText(),
            'Field has wrong value'
        );
    }
    public function setParentContainer(CSSSelector $selector): void
    {
        $this->parentSelector = $selector;
    }

    abstract public function getFieldTypeIdentifier(): string;

    public function verifyValueInEditView(array $value): void
    {
        Assert::assertEquals(
            $value['value'],
            $this->getValue()[0]
        );
    }

    public function verifyIsLoaded(): void
    {
    }
}
