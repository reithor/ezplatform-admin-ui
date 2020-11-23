<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use PHPUnit\Framework\Assert;

abstract class FieldTypeComponent extends Component
{
    /**
     * @var CSSSelector
     */
    protected $parentSelector;

    abstract public function setValue(array $parameters): void;

    abstract public function getValue(): array;

    abstract public function verifyValueInItemView(array $values): void;

    public function setParentContainer(CSSSelector $selector): void
    {
        $this->parentSelector = $selector;
    }

    abstract function getFieldTypeIdentifier(): string;

    public function verifyValue($value): void
    {
        Assert::assertEquals(
            $value['value'],
            $this->getValue()[0]
        );
    }

    public function specifySelectors(): array
    {
        return [
            new CSSSelector('fieldLabel','.ez-field-edit__label-wrapper'),
            new CSSSelector('fieldData','.ez-field-edit__data'),
        ];
    }

    public function verifyIsLoaded(): void
    {
    }
}
