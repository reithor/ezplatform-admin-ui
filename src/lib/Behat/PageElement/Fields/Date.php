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

class Date extends FieldTypeComponent
{
    private const DATE_FORMAT = 'm/d/Y';
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
        $this->dateAndTimePopup->setDate(date_create($parameters['value']), self::DATE_FORMAT);
    }

    public function getValue(): array
    {
        $fieldSelector = CSSSelector::combine("%s %s", $this->parentSelector, $this->getSelector('fieldInput'));
        $value = $this->getHTMLPage()->find($fieldSelector)->getText();

        return [$value];
    }

    public function verifyValueInItemView(array $values): void
    {
        $expectedDateTime = date_create($values['value']);
        $actualDateTime = date_create($this->getHTMLPage()->find($this->parentSelector)->getText());
        Assert::assertEquals(
            $expectedDateTime,
            $actualDateTime,
            'Field has wrong value'
        );
    }

    public function specifySelectors(): array
    {
        return [
            new CSSSelector('fieldInput', 'input.flatpickr-input.ez-data-source__input'),
        ];
    }

    public function getFieldTypeIdentifier(): string
    {
        return 'ezdate';
    }
}
