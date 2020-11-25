<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use eZ\Publish\API\Repository\Exceptions\NotImplementedException;
use EzSystems\Behat\Browser\Locator\CSSLocator;

abstract class NonEditableField extends FieldTypeComponent
{
    public const EXPECTED_NON_EDITABLE_TEXT = 'This Field Type is not editable';

    public function setValue(array $parameters): void
    {
        throw new NotImplementedException('Field is not editable!');
    }

    public function getValue(): array
    {
        $valueSelector = $this->parentSelector->withDescendant($this->getLocator('valueSelector'));

        return [$this->getHTMLPage()->find($valueSelector)->getText()];
    }

    public function specifyLocators(): array
    {
        return [
            new CSSLocator('valueSelector', '.non-editable'),
        ];
    }
}
