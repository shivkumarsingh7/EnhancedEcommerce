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

class Upsell implements ObserverInterface
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
     * Set block product variable
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        if (!$this->_helper->isUpsellEnabled()){
            return;
        }
        //$event = $observer->getEvent();
        $itemCollection = $observer->getCollection();

        if (empty($itemCollection)) {
            return;
        }

        $result = [];
        //$result[] = "ga('require', 'ec', 'ec.js');";
        /*
         *
         *
            // The impression from a upsell Products section.
            ga('ec:addImpression', {            // Provide product details in an impressionFieldObject.
              'id': 'P12345',                   // Product ID (string).
              'name': 'Android Warhol T-Shirt', // Product name (string).
              'category': 'Apparel/T-Shirts',   // Product category (string).
              'brand': 'Google',                // Product brand (string).
              'variant': 'Black',               // Product variant (string).
              'list': 'Related Products',       // Product list (string).
              'position': 1                     // Product position (number).
            });
         */
        $count = 1;
        foreach ($itemCollection as $_product) {

            $categories = $_product->getCategoryCollection()
                ->addAttributeToSelect('name');
            $_categoryName = array();
            foreach ($categories as $category) {
                $_categoryName[] = $category->getName();
            }
            $slash_separated_category = implode("/", $_categoryName);
            $result[] = sprintf(
                "ga('ec:addImpression', {
                        'id': '%s',
                        'name': '%s',
                        'category':'%s',
                        'price': '%s',
                        'list': 'Upsell Products',
                        'position': %s
                    });",
                $this->_block->escapeJsQuote($_product->getSku()),
                $this->_block->escapeJsQuote($_product->getName()),
                $this->_block->escapeJsQuote($slash_separated_category),
                $_product->getFinalPrice(),
                $count
            );
            $count++;
        }
        $result[] = sprintf("ga('send', 'pageview');");
        $this->_catalogSession->setUpsellScript( implode("\n", $result));
    }
}
