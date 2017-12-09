<?php
namespace ConversionBug\EnhancedEcommerce\Block;

/**
 *
 * @category   Marketing
 * @package    ConversionBug_EnhancedEcommerce
 * @author     shiv kumar singh <shivam.kumar@conversionbug.com>
 * @website    http://www.conversionbug.com/
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Related extends \Magento\Catalog\Block\Product\ProductList\Related
{

    /**
     * @var \ConversionBug\Onesignal\Helper\Data $helper
     */
    protected $_helper;

    /**
     * @param \Magento\Catalog\Model\Session $catalogSession
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Checkout\Model\ResourceModel\Cart $checkoutCart,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\Module\Manager $moduleManager,
        \ConversionBug\EnhancedEcommerce\Helper\Data $helper,
        array $data = []
    )
    {
        $this->_helper = $helper;
        parent::__construct($context,$checkoutCart,$catalogProductVisibility,$checkoutSession,$moduleManager,$data);

    }

    public function toHtml()
    {
       $html = parent::toHtml();

       if ( $this->_helper->isRelatedEnabled()) {
            $html .= $this->getRelatedProductDetailCode();
       }
       return $html;
    }

    /**
     * Render information about specific product
     *
     * @link https://developers.google.com/analytics/devguides/collection/analyticsjs/enhanced-ecommerce#product-data
     *
     * @return string|void
     */
    public function getRelatedProductDetailCode()
    {

        if (empty($this->_itemCollection)) {
            return;
        }

        $result = [];
        $result[] = "<script>
//<![CDATA[";
        $result[] = "ga('require', 'ec', 'ec.js');";
        /*
         *
         *
            // The impression from a Related Products section.
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
        foreach ($this->_itemCollection as $_product) {

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
                        'list': 'Related Products',
                        'position': %s
                    });",
                $this->escapeJsQuote($_product->getSku()),
                $this->escapeJsQuote($_product->getName()),
                $this->escapeJsQuote($slash_separated_category),
                $_product->getFinalPrice(),
                $count
            );

            $count++;
        }
        $result[] = sprintf("ga('send', 'pageview');");
        $result[] = "//]]>
</script>";
        return implode("\n", $result);
    }

}