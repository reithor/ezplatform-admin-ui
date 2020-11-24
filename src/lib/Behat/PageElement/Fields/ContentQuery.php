<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use EzSystems\Behat\Browser\Element\NodeElement;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use PHPUnit\Framework\Assert;

class ContentQuery extends NonEditableField
{
    public const ELEMENT_NAME = 'Content query';

    public function verifyValueInItemView(array $values): void
    {
        $expecteditems = explode(',', $values['value']);
        $actualItems = $this->getValueInItemView();
        $commonItems = array_intersect($expecteditems, $actualItems);

        Assert::assertEquals([], array_diff($expecteditems, $commonItems));
    }

    private function getValueInItemView(): array
    {
        $itemSelector = CSSSelector::combine("%s %s", $this->parentSelector, $this->getSelector('queryResultItem'));

        return $this->getHTMLPage()->findAll($itemSelector)->map(function (NodeElement $element) {
            return $element->getText();
        });
    }

    public function specifySelectors(): array
    {
        return array_merge(
            parent::specifySelectors(),
            [new CSSSelector('queryResultItem', 'p a')],
        );
    }

    public function getFieldTypeIdentifier(): string
    {
        return 'ezcontentquery';
    }
}
