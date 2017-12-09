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

class AddToCart implements ObserverInterface
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
        if (!$this->_helper->isAddtocartEnabled()){
            return;
        }
        $_item = $observer->getQuoteItem();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $_product = $objectManager->get('Magento\Catalog\Model\Product')->load($_item->getProductId());

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
                        'quantity': '%s'
                    });",
            $this->_block->escapeJsQuote($_product->getSku()),
            $this->_block->escapeJsQuote($_product->getName()),
            $this->_block->escapeJsQuote($slash_separated_category),
            $_item->getPrice(),
            $_item->getQty()

        );
        $event = $observer->getEvent();
        switch($event->getName()){
            case 'checkout_cart_product_add_after':
                $action = 'add';
                break;
            case 'sales_quote_remove_item':
                $action = 'remove';
                break;
        }
        $result[] = sprintf(
            " ga('ec:setAction', '".$action."');"// add to cart action.
        );

        $result[] = "ga('send', 'event', 'UX', 'click', 'add to cart');";
       // echo $result;exit;// Send data using an event.
        $oldData = $this->_catalogSession->getAddToCart();
        $newData = implode("\n", $result);
        $this->_catalogSession->setAddToCart($oldData.$newData);
        return;
    }
}
