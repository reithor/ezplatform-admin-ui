<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use EzSystems\Behat\Browser\Element\NodeElement;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
use PHPUnit\Framework\Assert;

class Keywords extends FieldTypeComponent
{
    private $setKeywordsValueScript = <<<SCRIPT
const SELECTOR_TAGGIFY = '.ez-data-source__taggify';
const taggifyContainer = document.querySelector(SELECTOR_TAGGIFY);
const taggify = new window.Taggify({
    containerNode: taggifyContainer,
    displayLabel: false,
    displayInputValues: false,
});

const tags = [%s];
var list = tags.map(function (item) {
    return {id: item, label: item};
});

taggify.updateTags(list);
SCRIPT;

    public function setValue(array $parameters): void
    {
        $parsedValue = implode(',', array_map(
            static function (string $element) {
                return sprintf('"%s"', trim($element));
            }, explode(',', $parameters['value'])
        ));

        $this->browser->getSession()->getDriver()->executeScript(sprintf($this->setKeywordsValueScript, $parsedValue));
    }

    public function verifyValueInItemView(array $values): void
    {
        $expectedValues = $this->parseValueString($values['value']);

        $actualValues = $this->getHTMLPage()
            ->findAll($this->parentLocator->withDescendant($this->getLocator('keywordItem')))
            ->map(static function (NodeElement $element) {
                return $element->getText();
            });
        sort($actualValues);

        Assert::assertEquals($expectedValues, $actualValues);
    }

    private function parseValueString(string $value): array
    {
        $parsedValues = [];

        foreach (explode(',', $value) as $singleValue) {
            $parsedValues[] = trim($singleValue);
        }

        sort($parsedValues);

        return $parsedValues;
    }

    public function specifyLocators(): array
    {
        return [
            new VisibleCSSLocator('fieldInput', 'input'),
            new VisibleCSSLocator('keywordItem', '.ez-keyword__item'),
        ];
    }

    public function getFieldTypeIdentifier(): string
    {
        return 'ezkeyword';
    }
}
