<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use EzSystems\Behat\Browser\Element\NodeElement;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use PHPUnit\Framework\Assert;
use Exception;

class RichText extends FieldTypeComponent
{
    private $setAlloyEditorValueScript = 'CKEDITOR.instances.%s.setData(\'%s\')';
    private $insertAlloyEditorValueScript = 'CKEDITOR.instances.%s.insertText(\'%s\')';
    private $executeAlloyEditorScript = 'CKEDITOR.instances.%s.execCommand(\'%s\')';
    protected $richtextId;
    protected const ALLOWED_STYLE_OPTIONS = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'pre'];
    protected const ALLOWED_MOVE_OPTIONS = ['up', 'down'];

    public function setValue(array $parameters): void
    {
        $this->getFieldInput();
        $this->browser->getSession()->getDriver()->executeScript(
            sprintf($this->setAlloyEditorValueScript, $this->richtextId, $parameters['value'])
        );
    }

    public function getValue(): array
    {
        $fieldInput = $this->getFieldInput();

        return [$fieldInput->getText()];
    }

    public function openElementsToolbar(): void
    {
        $this->getHTMLPage()->find($this->getSelector('addButton'))->click();
        usleep(200 * 1000); // wait until the transition animations ends
        Assert::assertTrue($this->getHTMLPage()->find($this->getSelector('toolbarButton'))->isVisible());
    }

    public function changeStyle(string $style): void
    {
        if (!in_array($style, self::ALLOWED_STYLE_OPTIONS)) {
            throw new Exception(sprintf('Unsupported style: %s', $style));
        }

        $this->getHTMLPage()->find($this->getSelector('styleDropdown'))->click();

        $blockStyleSelector = CSSSelector::combine(
            $this->getSelector('blockstyle')->getSelector(),
            new CSSSelector('', $style),
        );

        $this->getHTMLPage()->find($blockStyleSelector)->click();
    }

    public function insertNewLine(): void
    {
        $this->getFieldInput();
        $this->browser->getSession()->getDriver()->executeScript(
            sprintf($this->executeAlloyEditorScript, $this->richtextId, 'enter')
        );
    }

    public function insertLine($value, $style = ''): void
    {
        $this->getFieldInput();
        $this->browser->getSession()->getDriver()->executeScript(
            sprintf($this->insertAlloyEditorValueScript, $this->richtextId, $value)
        );

        if ($style === '') {
            return;
        }

        $this->changeStyle($style);
        $selector = CSSSelector::combine(
            '%s %s', $this->getSelector('fieldInput'), new CSSSelector('', $style)
        );
        Assert::assertContains(
            sprintf('%s%s</%s>', $value, '<br>', $style),
            $this->getHTMLPage()->find($selector)->getOuterHtml()
        );
    }

    private function getFieldInput(): NodeElement
    {
        $fieldInput = $this->getHTMLPage()->find($this->getSelector('fieldInput'));
        $this->richtextId = $fieldInput->getAttribute('id');

        return $fieldInput;
    }

    public function addUnorderedList(array $listElements): void
    {
        $this->getFieldInput();
        $this->openElementsToolbar();
        $this->getHTMLPage()->find($this->getSelector('unorderedListButton'))->click();

        foreach ($listElements as $listElement) {
            $this->insertLine($listElement);

            if ($listElement !== end($listElements)) {
                $this->insertNewLine();
            }
        }

        $actualListElements = $this->getHTMLPage()->findAll($this->getSelector('unorderedListElement'));
        $listElementsText = [];
        foreach ($actualListElements as $actualListElement) {
            $listElementsText[] = $actualListElement->getText();
        }

        Assert::assertEquals($listElements, $listElementsText);
    }

    public function clickEmbedInlineButton(): void
    {
        $this->getHTMLPage()->find($this->getSelector('embedInlineButton'))->click();
    }

    public function clickEmbedButton(): void
    {
        $this->getHTMLPage()->find($this->getSelector('embedButton'))->click();
    }

    public function equalsEmbedInlineItem($itemName): bool
    {
        return $itemName === $this->getHTMLPage()->find($this->getSelector('embedInlineTitle'))->getText();
    }

    public function equalsEmbedItem($itemName): bool
    {
        return $itemName === $this->getHTMLPage()->find($this->getSelector('embedTitle'))->getText();
    }

    public function moveElement($direction): void
    {
        if (!in_array($direction, self::ALLOWED_MOVE_OPTIONS)) {
            throw new Exception(sprintf('Unsupported direction: %s', $direction));
        }

        $moveSelector = CSSSelector::combine(
            $this->getSelector('moveButton'), new CSSSelector('', $direction)
        );
        $this->getHTMLPage()->find($moveSelector)->click();
    }

    protected function specifySelectors(): array
    {
        return [
            new CSSSelector('fieldInput', '.ez-data-source__richtext'),
            new CSSSelector('textarea', 'textarea'),
            new CSSSelector('embedInlineButton', '.ez-btn-ae--embed-inline'),
            new CSSSelector('embedButton', '.ez-btn-ae--embed'),
            new CSSSelector('addButton', '.ae-button-add'),
            new CSSSelector('embedTitle', '.cke_widget_ezembed .ez-embed-content__title'),
            new CSSSelector('embedInlineTitle', '.cke_widget_ezembedinline .ez-embed-content__title'),
            new CSSSelector('unorderedListButton', '.ez-btn-ae--unordered-list'),
            new CSSSelector('unorderedListElement', '.ez-data-source__richtext ul li'),
            new CSSSelector('styleDropdown', '.ae-toolbar-element'),
            new CSSSelector('blockStyle', '.ae-listbox li %s'),
            new CSSSelector('moveButton', '.ez-btn-ae--move-%s'),
            new CSSSelector('toolbarButton', '.ae-toolbar .ez-btn-ae'),
        ];
    }

    public function getFieldTypeIdentifier(): string
    {
        return 'ezrichtext';
    }
}
