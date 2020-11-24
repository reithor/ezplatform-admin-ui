<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use Behat\Mink\Session;
use EzSystems\Behat\Browser\Context\OldBrowserContext;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use PHPUnit\Framework\Assert;

class TextLine extends FieldTypeComponent
{
    public function specifySelectors(): array
    {
        return [
                new CSSSelector('fieldInput', 'input'),
            ];
    }

    public function getFieldTypeIdentifier(): string
    {
        return 'ezstring';
    }
}
