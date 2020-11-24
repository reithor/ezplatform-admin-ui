<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use eZ\Publish\API\Repository\Exceptions\NotImplementedException;
use EzSystems\Behat\Browser\Selector\CSSSelector;

abstract class NonEditableField extends FieldTypeComponent
{
    public const EXPECTED_NON_EDITABLE_TEXT = 'This Field Type is not editable';

    public function setValue(array $parameters): void
    {
        throw new NotImplementedException('Field is not editable!');
    }

    public function getValue(): array
    {
        $valueSelector = CSSSelector::combine(
            "%s %s",
            $this->parentSelector,
            $this->getSelector('valueSelector')
        );

        return [$this->getHTMLPage()->find($valueSelector)->getText()];
    }

    public function specifySelectors(): array
    {
        return [
            new CSSSelector('valueSelector', '.non-editable'),
        ];
    }
}
