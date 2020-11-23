<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use EzSystems\Behat\Browser\Context\OldBrowserContext;
use EzSystems\Behat\Browser\Page\Page;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\AdminList;
use EzSystems\Behat\Browser\Factory\ElementFactory;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables\LinkedListTable;
use PHPUnit\Framework\Assert;

class RolesPage extends Page
{
    /**
     * @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\AdminList
     */
    public $adminList;

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

    public function startAssigningToItem(string $itemName): void
    {
        $this->adminList->clickAssignButton($itemName);
    }

    public function startCreatingItem(): void
    {
        $this->adminList->clickPlusButton();
    }

    protected function getRoute(): string
    {
        return '/role/list';
    }

    public function getName(): string
    {
        return 'Roles';
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertEquals(
            'Roles',
            $this->getHTMLPage()->find($this->getSelector('pageTitle'))->getText()
        );

        $this->adminList->verifyIsLoaded();

        $this->context->waitUntil($this->defaultTimeout, function () {
            return $this->adminList->table->getItemCount() > 0;
        });
    }

    protected function specifySelectors(): array
    {
        return [
            new CSSSelector('pageTitle', '.ez-header h1'),
        ];
    }
}
