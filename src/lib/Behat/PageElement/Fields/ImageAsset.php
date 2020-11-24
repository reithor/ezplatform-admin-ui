<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use EzSystems\Behat\Browser\Page\Browser;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Notification;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\UniversalDiscoveryWidget;
use PHPUnit\Framework\Assert;

class ImageAsset extends Image
{
    /**
     * @var UniversalDiscoveryWidget
     */
    private $universalDiscoveryWidget;
    /**
     * @var Notification
     */
    private $notification;

    public function __construct(Browser $browser, UniversalDiscoveryWidget $universalDiscoveryWidget, Notification $notification)
    {
        parent::__construct($browser);
        $this->universalDiscoveryWidget = $universalDiscoveryWidget;
        $this->notification = $notification;
    }

    private const IMAGE_ASSET_NOTIFICATION_MESSAGE = 'The image has been published and can now be reused';

    public function setValue(array $parameters): void
    {
        // close notification about new draft created successfully if it's still visible
        if ($this->notification->isVisible()) {
            $this->notification->verifyAlertSuccess();
            $this->notification->closeAlert();
        }

        parent::setValue($parameters);

        $this->notification->verifyAlertSuccess();
        Assert::assertEquals(self::IMAGE_ASSET_NOTIFICATION_MESSAGE, $this->notification->getMessage());
    }

    public function selectFromRepository(string $path): void
    {
        $this->getHTMLPage()
            ->find(CSSSelector::combine($this->parentSelector, $this->getSelector('selectFromRepoButton')))
            ->click();
        $this->universalDiscoveryWidget->verifyIsLoaded();
        $this->universalDiscoveryWidget->selectContent($path);
        $this->universalDiscoveryWidget->confirm();
    }

    public function specifySelectors(): array
    {
        return array_merge(
            parent::specifySelectors(),
            [new CSSSelector('selectFromRepoButton', '.ez-data-source__btn-select'),]
        );
    }

    public function getFieldTypeIdentifier(): string
    {
        return 'ezimageasset';
    }
}
