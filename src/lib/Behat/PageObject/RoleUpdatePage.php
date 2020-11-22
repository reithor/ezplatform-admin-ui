<?php


namespace src\lib\Behat\PageObject;


use Behat\Mink\Session;
use Exception;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\UniversalDiscoveryWidget;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\AdminUpdateItemPage;
use FriendsOfBehat\SymfonyExtension\Mink\MinkParameters;

class RoleUpdatePage extends AdminUpdateItemPage
{
    /**
     * @var UniversalDiscoveryWidget
     */
    private $universalDiscoveryWidget;

    public function __construct(Session $session, MinkParameters $minkParameters, UniversalDiscoveryWidget $universalDiscoveryWidget)
    {
        parent::__construct($session, $minkParameters);
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
            $baseElement = $this->getHTMLPage()->findAll($this->getSelector('limitationField'))->getByChildElementText($this->getSelector('labelSelector'), $selectName);
            $currentlySelectedElementsCount = count($baseElement->findAll($this->getSelector('limitationDropdownOptionRemove')));

            for ($i = 0; $i < $currentlySelectedElementsCount; ++$i) {
                $this->
                    getHTMLPage()->
                    findAll($this->getSelector('limitationField'))->getByChildElementText($this->getSelector('labelSelector'), $selectName)->
                    find($this->getSelector('limitationDropdownOptionRemove'))->
                    click();
            }
        }
        catch (Exception $e) {
            // no need to remove current selection
        }

        $this->
            getHTMLPage()->findAll($this->getSelector('limitationField'))->getByChildElementText($this->getSelector('labelSelector'), $selectName)->
            find($this->getSelector('limitationDropdown'))->
            click();

        foreach ($values as $value) {
            $this->getHTMLPage()->findAll($this->getSelector('limitationDropdownOption'))->getByText($value)->click();
        }

        $this->
            getHTMLPage()->findAll($this->getSelector('limitationField'))->getByChildElementText($this->getSelector('labelSelector'), $selectName)->
            find($this->getSelector('limitationDropdown'))->
            click();
    }

    public function specifySelectors(): array
    {
        return [
            new CSSSelector('limitationField', '.ez-update-policy__action-wrapper'),
            new CSSSelector('limitationDropdown', '.ez-custom-dropdown__selection-info'),
            new CSSSelector('limitationDropdownOption', 'ul:not(.ez-custom-dropdown__items--hidden) .ez-custom-dropdown__item'),
            new CSSSelector('limitationDropdownOptionRemove', '.ez-custom-dropdown__remove-selection'),
            new CSSSelector('labelSelector', '.ez-label'),
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