<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use EzSystems\Behat\Browser\Context\OldBrowserContext;
use EzSystems\Behat\Browser\Page\Page;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\AdminList;
use EzSystems\Behat\Browser\Factory\ElementFactory;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables\SimpleTable;
use PHPUnit\Framework\Assert;

class ObjectStatePage extends Page
{
    /** @var string */
    private $objectStateName;

    /**
     * @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\AdminList
     */
    public $adminList;

    public function startEditingSelf(string $itemName): void
    {
        $this->adminList->table->clickEditButton($itemName);
    }

    public function verifyItemAttribute(string $label, string $value): void
    {
        Assert::assertEquals(
            $value,
            $this->adminList->table->getTableCellValue($label),
            sprintf('Attribute "%s" has wrong value.', $label)
        );
    }

    protected function getRoute(): string
    {
        return '/state/state'; // TODO: add object state id here
    }

    public function getName(): string
    {
        return 'Object state';
    }

    public function setExpectedObjectStateName(string $objectStateName)
    {
        $this->objectStateName = $objectStateName;
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertEquals(
            'Object state: %s',
            $this->getHTMLPage()->find($this->getLocator('pageTitle'))->getText()
        );

        $this->adminList->verifyIsLoaded();
    }

    protected function specifyLocators(): array
    {
        return [
            new VisibleCSSLocator('pageTitle', '.ez-header h1'),
        ];
    }
}
