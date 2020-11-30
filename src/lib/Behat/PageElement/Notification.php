<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement;

use Exception;
use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Element\NodeElement;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
use PHPUnit\Framework\Assert;

/** Element that describes user notification bar, that appears on the bottom of the screen */
class Notification extends Component
{
    public function verifyAlertSuccess(): void
    {
        Assert::assertTrue(
            $this->getHTMLPage()
                ->setTimeout(20)
                ->find($this->getLocator('successAlert'))
                ->isVisible(),
            'Success alert not found.'
        );
    }

    public function verifyAlertFailure(): void
    {
        Assert::assertTrue(
            $this->getHTMLPage()
                ->setTimeout(20)
                ->find($this->getLocator('failureAlert'))
                ->isVisible(),
            'Failure alert not found.'
        );
    }

    public function getMessage(): string
    {
        return $this->getHTMLPage()->find($this->getLocator('alertMessage'))->getText();
    }

    public function closeAlert(): void
    {
        $closeButtons = $this->getHTMLPage()->findAll($this->getLocator('closeAlert'));

        foreach($closeButtons as $closeButton) {
            $closeButton->click();
        }
    }

    public function isVisible(): bool
    {
        $elements =  $this->getHTMLPage()->findAll($this->getLocator('alert'));

        return $elements->any() ? $elements->single()->isVisible() : false;
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertTrue(
            $this
                ->getHTMLPage()
                ->setTimeout(20)
                ->find($this->getLocator('alert'))
                ->isVisible()
        );
    }

    public function verifyMessage(string $expectedMessage)
    {
        $this->getHTMLPage()->find($this->getLocator('alertMessage'))->assert()->textEquals($expectedMessage);
    }

    protected function specifyLocators(): array
    {
        return [
            new VisibleCSSLocator('alert', '.ez-notifications-container .alert.show'),
            new VisibleCSSLocator('alertMessage', '.ez-notifications-container .alert.show span:nth-of-type(2)'),
            new VisibleCSSLocator('successAlert', '.alert-success'),
            new VisibleCSSLocator('failureAlert', '.alert-danger'),
            new VisibleCSSLocator('closeAlert', 'button.close'),
        ];
    }
}
