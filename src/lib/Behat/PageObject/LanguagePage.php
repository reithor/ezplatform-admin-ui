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
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables\SimpleTable;
use PHPUnit\Framework\Assert;

class LanguagePage extends Page
{
    /**
     * @var \EzSystems\EzPlatformAdminUi\Behat\PageElement\AdminList
     */
    public $adminList;

    /**
     * @var string
     */
    private $expectedLanguageName;

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
        return '/language/view'; //TODO: load language
    }

    public function getName(): string
    {
        return 'Language';
    }

    public function setExpectedLanguageName(string $languageName)
    {
        $this->expectedLanguageName = $languageName;
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertEquals(
            'Language',
            $this->getHTMLPage()->find($this->getSelector('pageTitle'))->getText()
        );

        $this->adminList->verifyIsLoaded();
    }

    protected function specifySelectors(): array
    {
        return [
            new CSSSelector('pageTitle', '.ez-header h1'),
        ];
    }
}
