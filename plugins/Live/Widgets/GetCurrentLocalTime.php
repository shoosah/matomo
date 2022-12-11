<?php
/**
 * Matomo - free/libre analytics platform
 *
 * @link https://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\Live\Widgets;

use DateTime;
use Piwik\Site;
use DateTimeZone;
use Piwik\Common;
use Piwik\Widget\Widget;
use Piwik\Widget\WidgetConfig;

class GetCurrentLocalTime extends Widget
{
    /**
     * @var string 
     */
    private static $timezone;

    /**
     * @return void
     */
    public static function configure(WidgetConfig $config): void
    {
        /**
         * Set the category the widget belongs to. You can reuse any existing widget category or define
         * your own category.
         */
        $config->setCategoryId('General_Visitors');

        /**
         * Set the subcategory the widget belongs to. If a subcategory is set, the widget will be shown in the UI.
         */
        $config->setSubcategoryId('General_Overview');

        /**
         * Set the name of the widget belongs to.
         */
        $config->setName('Live_CurrentLocalTime');

        /**
         * Set the order of the widget. The lower the number, the earlier the widget will be listed within a category.
         */
        $config->setOrder(99);

        /**
         * Set timezone for the current website
         */
        $idSite = Common::getRequestVar('idSite', 0, 'int');

        if (empty($idSite)) {
            return;
        }

        self::$timezone = Site::getTimezoneFor($idSite);
    }

    /**
     * This method renders the "current local time in website's timezone" time widget.
     * If you are for example based in LA, 
     * and the site is configured to be in NY, 
     * then this method renders the current time in NY.
     *
     * @return string
     */
    public function render(): string
    {
        $dateTime = new DateTime();
        $timeZone = new DateTimeZone(self::$timezone);
        $dateTime->setTimezone($timeZone);

        return $this->renderTemplate(
            'getcurrentLocalTimeTemplate.twig', 
            [
                'now' => $dateTime->format('Y/m/d H:i:s')
            ]
        );
    }
}