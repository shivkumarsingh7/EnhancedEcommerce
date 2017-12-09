<?php
/**
 *
 * @category   Marketing
 * @package    ConversionBug_EnhancedEcommerce
 * @author     shiv kumar singh <shivam.kumar@conversionbug.com>
 * @website    http://www.conversionbug.com/
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace ConversionBug\EnhancedEcommerce\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class Rule implements ObserverInterface
{
    /**
     * @var \ConversionBug\Onesignal\Helper\Data $helper
     */
    protected $_helper;

    /**
     * @var \Magento\Catalog\Model\Session $catalogSession
     */
    protected $_catalogSession;
    /**
     * @var \ConversionBug\EnhancedEcommerce\Block\EnhancedEcommerce $block
     */
    protected $_block;

    /**
     * @param \ConversionBug\EnhancedEcommerce\Helper\Data $helper
     * @param \Magento\Catalog\Model\Session $catalogSession
     */
    public function __construct(
        \ConversionBug\EnhancedEcommerce\Helper\Data $helper,
        \Magento\Catalog\Model\Session $catalogSession,
        \ConversionBug\EnhancedEcommerce\Block\EnhancedEcommerce $block

    ) {
        $this->_helper = $helper;
        $this->_catalogSession = $catalogSession;
        $this->_block = $block;
    }

    /**
     * Send coupon data to ga.
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_helper->isCouponAppliedEnabled()){
            return;
        }

        $_rule = $observer->getRule();

        if (empty($_rule)) {
            return;
        }
        $result = [];

        //$result[] = "ga('require', 'ec', 'ec.js');";

        /**
         *

        Promotion Data

        Represents information about a promotion that has been viewed. It is referred to a promoFieldObject and contains the following values:
        Key 	    ValueType 	Required 	Description
        id 	        text 	    Yes* 	    The promotion ID (e.g. PROMO_1234). *Either this field or name must be set.
        name 	    text 	    Yes* 	    The name of the promotion (e.g. Summer Sale). *Either this field or id must be set.
        creative 	text 	    No 	        The creative associated with the promotion (e.g. summer_banner2).
        position 	text 	    No 	        The position of the creative (e.g. banner_slot_1).
         */


        /*
         * ga('ec:addPromo', {               // Promo details provided in a promoFieldObject.
              'id': 'PROMO_1234',             // Promotion ID. Required (string).
              'name': 'Summer Sale',          // Promotion name (string).
              'creative': 'summer_banner2',   // Creative (string).
              'position': 'banner_slot1'      // Position  (string).
            });
         *
         */

        $result[] = sprintf(
            "ga('ec:addPromo', {
                        'id': '%s',
                        'name': '%s',
                        'position':'COUPON APPLIED'
                    });",
            $this->_block->escapeJsQuote($_rule->getCode()),
            $this->_block->escapeJsQuote($_rule->getName())
        );

        // Send the promo_click action with an event.
        $result[] = sprintf(
            "ga('ec:setAction','promo_click');"
        );

        $result[] = "ga('send', 'event', 'Internal Promotions', 'click', 'COUPON APPLIED');";

        $newData = implode("\n", $result);
        $this->_catalogSession->setCouponApplied($newData);
        return;
    }
}
