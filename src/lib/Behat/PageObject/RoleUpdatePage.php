<?php


namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use Behat\Mink\Session;
use Exception;
use EzSystems\Behat\Browser\Page\Browser;
use EzSystems\Behat\Browser\Locator\CSSLocator;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\RightMenu;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\UniversalDiscoveryWidget;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\AdminUpdateItemPage;
use FriendsOfBehat\SymfonyExtension\Mink\MinkParameters;

class RoleUpdatePage extends AdminUpdateItemPage
{
    /**
     * @var UniversalDiscoveryWidget
     */
    private $universalDiscoveryWidget;

    public function __construct(Browser $browser, RightMenu $rightMenu, UniversalDiscoveryWidget $universalDiscoveryWidget)
    {
        parent::__construct($browser, $rightMenu);
        $this->universalDiscoveryWidget = $universalDiscoveryWidget;
    }

    private $itemTypeToLabelMapping = [
        'users' => 'Select Users',
        'groups' => 'Select User Groups',
    ];

    public function selectLimitationValues(string $selectName, array $values): void
    {
        try
        {
            $baseElement = $this->getHTMLPage()->findAll($this->getLocator('limitationField'))->getByChildElementText($this->getLocator('labelSelector'), $selectName);
            $currentlySelectedElementsCount = count($baseElement->findAll($this->getLocator('limitationDropdownOptionRemove')));

            for ($i = 0; $i < $currentlySelectedElementsCount; ++$i) {
                $this->
                    getHTMLPage()->
                    findAll($this->getLocator('limitationField'))->getByChildElementText($this->getLocator('labelSelector'), $selectName)->
                    find($this->getLocator('limitationDropdownOptionRemove'))->
                    click();
            }
        }
        catch (Exception $e) {
            // no need to remove current selection
        }

        $this->
            getHTMLPage()->findAll($this->getLocator('limitationField'))->getByChildElementText($this->getLocator('labelSelector'), $selectName)->
            find($this->getLocator('limitationDropdown'))->
            click();

        foreach ($values as $value) {
            $this->getHTMLPage()->findAll($this->getLocator('limitationDropdownOption'))->getByText($value)->click();
        }

        $this->
            getHTMLPage()->findAll($this->getLocator('limitationField'))->getByChildElementText($this->getLocator('labelSelector'), $selectName)->
            find($this->getLocator('limitationDropdown'))->
            click();
    }

    public function specifyLocators(): array
    {
        return [
            new CSSLocator('limitationField', '.ez-update-policy__action-wrapper'),
            new CSSLocator('limitationDropdown', '.ez-custom-dropdown__selection-info'),
            new CSSLocator('limitationDropdownOption', 'ul:not(.ez-custom-dropdown__items--hidden) .ez-custom-dropdown__item'),
            new CSSLocator('limitationDropdownOptionRemove', '.ez-custom-dropdown__remove-selection'),
            new CSSLocator('labelSelector', '.ez-label'),
        ];
    }

    public function assign(array $items, string $itemType)
    {
        $this->clickButton($this->itemTypeToLabelMapping[$itemType]);
        $this->universalDiscoveryWidget->verifyIsLoaded();

        foreach ($items as $item) {
            $this->universalDiscoveryWidget->selectContent($item['path']);
        }

        $this->universalDiscoveryWidget->confirm();
    }
}
