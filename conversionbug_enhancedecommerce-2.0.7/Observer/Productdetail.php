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

class Productdetail implements ObserverInterface
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
     * Render information about specific product
     *
     * @link https://developers.google.com/analytics/devguides/collection/analyticsjs/enhanced-ecommerce#product-data
     *
     * @return string|void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_helper->isProductDetailEnabled()){
            return;
        }
        $_product = $observer->getProduct();

        if (empty($_product)) {
            return;
        }
        $result = [];

        //$result[] = "ga('require', 'ec', 'ec.js');";

        /**
         *Product Data
         *
         * Product data represents individual products that were viewed, added to the shopping cart, etc. It is                 referred to as a productFieldObject and contains the following values:
         *
         * Key          Value Type          Required            Description
         * id           text                Yes*                The product ID or SKU (e.g. P67890). *Either this                                                                    field or name must be set.
         *
         * name         text                Yes*                The name of the product (e.g. Android T-Shirt).                                                                      *Either this field or id must be set.
         *
         * brand        text                No                  The brand associated with the product (e.g. Google).
         *
         *
         * category     text                No                  The category to which the product belongs (e.g.                                                                       Apparel). Use / as a delimiter to specify up to                                                                      5-levels of hierarchy (e.g. Apparel/Men/T-Shirts).
         *
         *
         * variant      text                No                  The variant of the product (e.g. Black).
         *
         * price        currency            No                  The price of a product (e.g. 29.20).
         *
         * quantity     integer             No                  The quantity of a product (e.g. 2).
         *
         * coupon       text                No                  The coupon code associated with a product (e.g.                                                                      SUMMER_SALE13).
         *
         * position     integer             No                  The product's position in a list or collection (e.g. 2).
         */

        /*              'brand': %s,
                    'variant':'%s',
                    'quantity':'%s',
                    'coupon':'%s',
                    'position':'%s',*/
        $categories = $_product->getCategoryCollection()
            ->addAttributeToSelect('name');
        $_categoryName = array();
        foreach($categories as $category) {
            $_categoryName[] = $category->getName();
        }
        $slash_separated_category = implode("/", $_categoryName);
        $result[] = sprintf(
            "ga('ec:addProduct', {
                        'id': '%s',
                        'name': '%s',
                        'category':'%s',
                        'price': '%s',
                    });",
            $this->_block->escapeJsQuote($_product->getSku()),
            $this->_block->escapeJsQuote($_product->getName()),
            $this->_block->escapeJsQuote($slash_separated_category),
            $_product->getFinalPrice()
        );

        $result[] = sprintf(
            "ga('ec:setAction', 'detail');"// Detail action.
        );

        $result[] = "ga('send', 'pageview');";
        //print_r($result);exit;
        $this->_catalogSession->setProductScript(implode("\n", $result));
    }
}
