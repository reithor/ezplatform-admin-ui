<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement;

use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Context\OldBrowserContext;
use EzSystems\Behat\Browser\Element\Element;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use PHPUnit\Framework\Assert;

/** Element that describes upper menu (Content, Admin, Page and theirs children) */
class UpperMenu extends Component
{
    public function goToTab(string $tabName): void
    {
        $this->context->getElementByText($tabName, $this->fields['menuButton'])->click();
    }

    public function goToDashboard(): void
    {
        $this->getHTMLPage()->find($this->getSelector('dashboardLink'))->click();
    }

    public function goToSubTab(string $tabName): void
    {
        $this->context->waitUntil(5, function () use ($tabName) {
            return $this->context->getElementByText($tabName, $this->fields['submenuButton']) !== null;
        });

        $this->context->getElementByText($tabName, $this->fields['submenuButton'])->click();
    }

    public function getNotificationsCount(): int
    {
        return (int) $this->getHTMLPage()->find($this->getSelector('pendingNotificationsCount'))->getAttribute('data-count');
    }

    public function chooseFromUserDropdown(string $option): void
    {
        $this->getHTMLPage()->find($this->getSelector('userSettingsToggle'))->click();
        $this->context->getElementByText($option, $this->fields['userSettingsItem'])->click();
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertTrue($this->getHTMLPage()->find($this->getSelector('menuButton'))->isVisible());
    }

    public function getName(): string
    {
        return 'Upper menu';
    }

    protected function specifySelectors(): array
    {
        return [
            new CSSSelector('menuButton', '.ez-main-nav .nav-link'),
            new CSSSelector('submenuButton', '.ez-main-sub-nav .nav-link'),
            new CSSSelector('dashboardLink', '.navbar-brand'),
            new CSSSelector('pendingNotificationsCount', '.ez-user-menu__name-wrapper .n-pending-notifications'),
            new CSSSelector('userSettingsToggle', '.ez-user-menu__name-wrapper'),
            new CSSSelector('userSettingsItem', '.ez-user-menu__item'),
        ];
    }
}
