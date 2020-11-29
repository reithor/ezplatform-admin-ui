<?php


namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Table;


use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Element\NodeElement;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
use EzSystems\Behat\Browser\Page\Browser;

class TableRow extends Component
{
    /**
     * @var NodeElement
     */
    private $element;

    public function __construct(Browser $browser, NodeElement $element)
    {
        parent::__construct($browser);
        $this->element = $element;
    }

    public function goToItem(): void
    {
        $this->element->find($this->getLocator('link'))->click();
    }

    public function select(): void
    {
        $this->element->find($this->getLocator('checkbox'))->click();
    }

    public function edit(): void
    {
        $this->element->find($this->getLocator('edit'))->click();
    }

    public function verifyIsLoaded(): void
    {
    }

    protected function specifyLocators(): array
    {
        return [
            new VisibleCSSLocator('link', '.ez-table__cell a'),
            new VisibleCSSLocator('checkbox', 'input[type=checkbox]'),
            new VisibleCSSLocator('edit', '.ez-icon-edit'),
        ];
    }
}