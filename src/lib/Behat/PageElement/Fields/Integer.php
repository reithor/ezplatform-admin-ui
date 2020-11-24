<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use EzSystems\Behat\Browser\Selector\CSSSelector;

class Integer extends FieldTypeComponent
{
    public function specifySelectors(): array
    {
        return [
            new CSSSelector('fieldInput', 'input'),
        ];
    }

    public function getFieldTypeIdentifier(): string
    {
        return 'ezinteger';
    }
}
