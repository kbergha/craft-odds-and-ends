<?php

namespace spicyweb\oddsandends;

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use craft\events\PluginEvent;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Dashboard;
use craft\services\Fields;
use craft\services\Plugins;
use spicyweb\oddsandends\fields\Ancestors as AncestorsField;
use spicyweb\oddsandends\fields\AuthorInstructions as AuthorInstructionsField;
use spicyweb\oddsandends\fields\CategoriesMultipleGroups as CategoriesMultipleGroupsField;
use spicyweb\oddsandends\fields\CategoriesSearch as CategoriesSearchField;
use spicyweb\oddsandends\fields\DisabledCategories as DisabledCategoriesField;
use spicyweb\oddsandends\fields\DisabledDropdown as DisabledDropdownField;
use spicyweb\oddsandends\fields\DisabledEntries as DisabledEntriesField;
use spicyweb\oddsandends\fields\DisabledLightswitch as DisabledLightswitchField;
use spicyweb\oddsandends\fields\DisabledNumber as DisabledNumberField;
use spicyweb\oddsandends\fields\DisabledPlainText as DisabledPlainTextField;
use spicyweb\oddsandends\fields\DisabledProducts as DisabledProductsField;
use spicyweb\oddsandends\fields\DisabledVariants as DisabledVariantsField;
use spicyweb\oddsandends\fields\EntriesSearch as EntriesSearchField;
use spicyweb\oddsandends\fields\Grid as GridField;
use spicyweb\oddsandends\fields\ProductsSearch as ProductsSearchField;
use spicyweb\oddsandends\fields\VariantsSearch as VariantsSearchField;
use spicyweb\oddsandends\fields\Width as WidthField;
use spicyweb\oddsandends\models\Settings;
use spicyweb\oddsandends\widgets\RollYourOwn as RollYourOwnWidget;
use yii\base\Event;

/**
 * Class Tools
 *
 * @package spicyweb\oddsandends
 * @author Spicy Web <plugins@spicyweb.com.au>
 * @author Supercool
 * @since 2.0.0
 */
class Tools extends Plugin
{
    /**
     * @var Tools The plugin instance.
     */
    public static ?Tools $plugin = null;

    /**
     * @inheritdoc
     */
    public string $minVersionRequired = '2.2.0';

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        self::$plugin = $this;

        // Register our fields
        Event::on(
            Fields::className(),
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function(RegisterComponentTypesEvent $event) {
                $event->types[] = AuthorInstructionsField::class;
                $event->types[] = DisabledLightswitchField::class;
                $event->types[] = DisabledPlainTextField::class;
                $event->types[] = DisabledNumberField::class;
                $event->types[] = DisabledEntriesField::class;
                $event->types[] = DisabledCategoriesField::class;
                $event->types[] = DisabledDropdownField::class;
                $event->types[] = EntriesSearchField::class;
                $event->types[] = CategoriesSearchField::class;
                $event->types[] = CategoriesMultipleGroupsField::class;
                $event->types[] = WidthField::class;
                $event->types[] = AncestorsField::class;
                $event->types[] = GridField::class;

                $pluginsService = Craft::$app->getPlugins();
                if ($pluginsService->isPluginInstalled('commerce') && $pluginsService->isPluginEnabled('commerce')) {
                    $event->types[] = DisabledProductsField::class;
                    $event->types[] = DisabledVariantsField::class;
                    $event->types[] = ProductsSearchField::class;
                    $event->types[] = VariantsSearchField::class;
                }
            }
        );

        // Register the Roll Your Own Widget
        Event::on(
            Dashboard::className(),
            Dashboard::EVENT_REGISTER_WIDGET_TYPES,
            function(RegisterComponentTypesEvent $event) {
                $event->types[] = RollYourOwnWidget::class;
            }
        );

        Craft::info(Craft::t('tools', '{name} plugin loaded', ['name' => $this->name]), __METHOD__);
    }

    /**
     * @inheritdoc
     */
    protected function createSettingsModel(): ?Model
    {
        return new Settings();
    }
}
