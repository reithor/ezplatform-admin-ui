<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use EzSystems\Behat\Browser\Context\OldBrowserContext;

abstract class NonEditableField extends EzFieldElement
{
    public const EXPECTED_NON_EDITABLE_TEXT = 'This Field Type is not editable';

    public function __construct(OldBrowserContext $context, string $locator, string $label)
    {
        parent::__construct($context, $locator, $label);
        $this->fields['valueSelector'] = sprintf('%s %s', $this->fields['fieldContainer'], '.non-editable');
    }

    public function setValue(array $parameters): void
    {
        throw new \Exception('Field is not editable!');
    }

    public function getValue(): array
    {
        return [$this->getHTMLPage()->find($this->getSelector('valueSelector'))->getText()];
    }
}
