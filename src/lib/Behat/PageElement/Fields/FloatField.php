<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;

class FloatField extends FieldTypeComponent
{
    public function specifyLocators(): array
    {
        return [
            new VisibleCSSLocator('fieldInput', 'input'),
        ];
    }

    public function getFieldTypeIdentifier(): string
    {
        return 'ezfloat';
    }
}
