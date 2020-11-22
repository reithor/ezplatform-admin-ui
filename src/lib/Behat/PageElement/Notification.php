<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement;

use Exception;
use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Element\NodeElement;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use PHPUnit\Framework\Assert;

/** Element that describes user notification bar, that appears on the bottom of the screen */
class Notification extends Component
{
    /** @var NodeElement */
    private $notificationElement;

    public function verifyAlertSuccess(): void
    {
        $this->setAlertElement();

        Assert::assertTrue(
            $this->notificationElement->hasClass($this->getSelector('successAlert')),
            'Success alert not found.'
        );
    }

    public function verifyAlertFailure(): void
    {
        $this->setAlertElement();

        Assert::assertTrue(
            $this->notificationElement->hasClass($this->getSelector('failureAlert')),
            'Failure alert not found.'
        );
    }

    public function getMessage(): string
    {
        $this->setAlertElement();

        try {
            return $this->getHTMLPage()->find($this->getSelector('alertMessage'))->getText();
        } catch (Exception $e) {
            Assert::fail('Notification alert not found, no message can be fetched.');
        }
    }

    public function closeAlert(): void
    {
        if ($this->isVisible()) {
            $this->setAlertElement();

            $this->notificationElement->find($this->getSelector('closeAlert'))->click();

            $this->getHTMLPage()->waitUntil(function () {
                return !$this->isVisible();
            });
        }
    }

    public function isVisible(): bool
    {
        return $this->getHTMLPage()->setTimeout(1)->find($this->getSelector('alert'))->isVisible();
    }

    private function setAlertElement(): void
    {
        if (!isset($this->notificationElement)) {
            $this->notificationElement = $this->getHTMLPage()->find($this->getSelector('alert'));
        }
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertTrue(
            $this
                ->getHTMLPage()
                ->setTimeout(20)
                ->find($this->getSelector('alert'))
        );


        $this->setAlertElement();
    }

    public function getName(): string
    {
        return 'Notification';
    }

    protected function specifySelectors(): array
    {
        return [
            new CSSSelector('alert', '.ez-notifications-container .alert.show'),
            new CSSSelector('alertMessage', '.ez-notifications-container .alert.show span:nth-of-type(2)'),
            new CSSSelector('successAlert', 'alert-success'),
            new CSSSelector('failureAlert', 'alert-danger'),
            new CSSSelector('closeAlert', 'button.close'),
        ];
    }
}
