<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement;

use Behat\Mink\Element\NodeElement;
use EzSystems\EzPlatformAdminUi\Behat\Helper\UtilityContext;

class ContentTypePicker extends Element
{
    public const ELEMENT_NAME = 'ContentTypePicker';
    public $fields;

    public function __construct(UtilityContext $context)
    {
        parent::__construct($context);
        $this->fields = [
            'contentTypeSelector' => '.form-check-label',
        ];
    }

    public function select(string $contentType): void
    {
        $this->context->getElementByText($contentType, $this->fields['contentTypeSelector'])->click();
    }

    public function isContentTypeVisible(string $contentTypeName): bool
    {
        return $this->context->getElementByText($contentTypeName, $this->fields['contentTypeSelector']) !== null;
    }

    public function getDisplayedContentTypes(): array
    {
        return array_map(function (NodeElement $element) {
            return $element->getText();
        }, $this->context->findAllElements($this->fields['contentTypeSelector']));
    }
}
