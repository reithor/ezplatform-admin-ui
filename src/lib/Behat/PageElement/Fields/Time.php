<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use EzSystems\Behat\Browser\Page\Browser;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\DateAndTimePopup;
use PHPUnit\Framework\Assert;

class Time extends FieldTypeComponent
{
    private const VALUE_TIME_FORMAT = 'G:i';

    /**
     * @var DateAndTimePopup
     */
    private $dateAndTimePopup;

    public function __construct(Browser $browser, DateAndTimePopup $dateAndTimePopup)
    {
        parent::__construct($browser);
        $this->dateAndTimePopup = $dateAndTimePopup;
    }

    public function setValue(array $parameters): void
    {
        $fieldSelector = CSSSelector::combine("%s %s", $this->parentSelector, $this->getSelector('fieldInput'));

        $this->getHTMLPage()->find($fieldSelector)->click();

        $time = explode(':', $parameters['value']);

        $this->dateAndTimePopup->setTime($time[0], $time[1]);

        // This click is closing the date and time picker, to finally ensure that value is set up.
        $this->getHTMLPage()->find($this->parentSelector)->click();

        $expectedTimeValue = date_format(date_create($parameters['value']), self::VALUE_TIME_FORMAT);
        $actualTimeValue = date_format(date_create($this->getHTMLPage()->find($fieldSelector)->getValue()), self::VALUE_TIME_FORMAT);

        Assert::assertEquals($expectedTimeValue, $actualTimeValue);
    }

    public function verifyValueInItemView(array $values): void
    {
        $actualTimeValue = date_format(date_create($this->getHTMLPage()->find($this->getSelector('fieldContainer'))->getText()), self::VALUE_TIME_FORMAT);
        $expectedTimeValue = date_format(date_create($values['value']), self::VALUE_TIME_FORMAT);
        Assert::assertEquals(
            $expectedTimeValue,
            $actualTimeValue,
            'Field has wrong value'
        );
    }

    protected function specifySelectors(): array
    {
        return [
            new CSSSelector('fieldInput', '.ez-data-source__input-wrapper input'),
        ];
    }

    public function getFieldTypeIdentifier(): string
    {
        return 'eztime';
    }
}
