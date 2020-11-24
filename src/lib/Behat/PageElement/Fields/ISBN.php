<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use EzSystems\Behat\Browser\Context\OldBrowserContext;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use PHPUnit\Framework\Assert;

class ISBN extends FieldTypeComponent
{
    public function getFieldTypeIdentifier(): string
    {
        return 'ezisbn';
    }

    public function specifySelectors(): array
    {
        return [
            new CSSSelector('fieldInput', 'input'),
        ];
    }
}
