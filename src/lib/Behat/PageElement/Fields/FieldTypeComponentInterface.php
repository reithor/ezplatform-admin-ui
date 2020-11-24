<?php

namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use EzSystems\Behat\Browser\Selector\CSSSelector;

interface FieldTypeComponentInterface
{
    public function setValue(array $parameters): void;

    public function getValue(): array;

    public function verifyValueInItemView(array $values): void;

    public function verifyValueInEditView(array $values): void;

    public function getFieldTypeIdentifier(): string;

    public function setParentContainer(CSSSelector $selector): void;
}