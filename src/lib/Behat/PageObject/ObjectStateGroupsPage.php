<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use EzSystems\Behat\Browser\Context\OldBrowserContext;
use EzSystems\Behat\Browser\Page\Page;
use EzSystems\Behat\Browser\Factory\ElementFactory;
use EzSystems\Behat\Browser\Locator\CSSLocator;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\AdminList;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables\LinkedListTable;
use PHPUnit\Framework\Assert;

class ObjectStateGroupsPage extends Page
{
    public function verifyItemAttribute(string $label, string $value, string $itemName): void
    {
        Assert::assertEquals(
            $value,
            $this->adminList->table->getTableCellValue($itemName, $label),
            sprintf('Attribute "%s" of item "%s" has wrong value.', $label, $itemName)
        );
    }

    public function startEditingItem(string $itemName): void
    {
        $this->adminList->table->clickEditButton($itemName);
    }

    public function startCreatingItem(): void
    {
        $this->adminList->clickPlusButton();
    }

    protected function getRoute(): string
    {
        return '/state/groups';
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertEquals(
            'Object state groups',
            $this->getHTMLPage()->find($this->getLocator('pageTitle'))->getText()
        );

        $this->adminList->verifyIsLoaded();
    }

    public function getName(): string
    {
        return 'Object state groups';
    }

    protected function specifyLocators(): array
    {
        return [
            new CSSLocator('pageTitle', '.ez-header h1'),
        ];
    }
}
