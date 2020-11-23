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
    public function verifyAlertSuccess(): void
    {
        Assert::assertTrue(
            $this->getHTMLPage()
                ->setTimeout(20)
                ->find($this->getSelector('successAlert'))
                ->isVisible(),
            'Success alert not found.'
        );
    }

    public function verifyAlertFailure(): void
    {
        Assert::assertTrue(
            $this->getHTMLPage()
                ->setTimeout(20)
                ->find($this->getSelector('failureAlert'))
                ->isVisible(),
            'Failure alert not found.'
        );
    }

    public function getMessage(): string
    {
        return $this->getHTMLPage()->find($this->getSelector('alertMessage'))->getText();
    }

    public function closeAlert(): void
    {
        $alerts = $this->getHTMLPage()->findAll($this->getSelector('alert'));

        if ($alerts->any()) {
            $alerts->single()->click();
        }
    }

    public function isVisible(): bool
    {
        $elements =  $this->getHTMLPage()->findAll($this->getSelector('alert'));

        return $elements->any() ? $elements->single()->isVisible() : false;
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertTrue(
            $this
                ->getHTMLPage()
                ->find($this->getSelector('alert'))
                ->isVisible()
        );
    }

    protected function specifySelectors(): array
    {
        return [
            new CSSSelector('alert', '.ez-notifications-container .alert.show'),
            new CSSSelector('alertMessage', '.ez-notifications-container .alert.show span:nth-of-type(2)'),
            new CSSSelector('successAlert', '.alert-success'),
            new CSSSelector('failureAlert', '.alert-danger'),
            new CSSSelector('closeAlert', 'button.close'),
        ];
    }
}
