<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables;

use EzSystems\Behat\Browser\Context\OldBrowserContext;

class SubitemsGridList extends ItemsList
{
    public const ELEMENT_NAME = 'Subitems grid list';

    public function __construct(OldBrowserContext $context, string $containerLocator)
    {
        parent::__construct($context, $containerLocator);
        $this->fields['listElement'] = $containerLocator . ' .c-grid-view-item';
    }

    public function clickListElement(string $name): void
    {
        $this->context->getElementByText($name, $this->fields['listElement'])->click();
    }

    public function canBeSorted(): bool
    {
        return false;
    }
}
