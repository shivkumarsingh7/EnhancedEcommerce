<?php
namespace ConversionBug\EnhancedEcommerce\Helper;

/**
 * Utility Helper
 *
 * @category   Marketing
 * @package    ConversionBug_EnhancedEcommerce
 * @author     shiv kumar singh <shivam.kumar@conversionbug.com>
 * @website    http://www.conversionbug.com/
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Data extends \Magento\GoogleAnalytics\Helper\Data
{

	const XML_CONFIG_PATH_ENHANCEDECOMMERCE_ENABLED = 'google/analytics/enable_enhanced_ecommerce';
	const XML_CONFIG_PATH_ENHANCEDECOMMERCE_PRODUCTDETAIL_ENABLED = 'google/analytics/enable_product_detail';
	const XML_CONFIG_PATH_ENHANCEDECOMMERCE_ADDTOCART_ENABLED = 'google/analytics/enable_addtocart';
	const XML_CONFIG_PATH_ENHANCEDECOMMERCE_REMOVEFROMCART_ENABLED = 'google/analytics/enable_removefromcart';
	const XML_CONFIG_PATH_ENHANCEDECOMMERCE_PRODUCTLIST_ENABLED = 'google/analytics/enable_productlist';
	const XML_CONFIG_PATH_ENHANCEDECOMMERCE_COUPONAPPLIED_ENABLED = 'google/analytics/enable_couponapplied';
	const XML_CONFIG_PATH_ENHANCEDECOMMERCE_UPSELL_ENABLED = 'google/analytics/enable_upsell';
	const XML_CONFIG_PATH_ENHANCEDECOMMERCE_RELATED_ENABLED = 'google/analytics/enable_related';

	const ENHANCEDECOMMERCE_ENABLED = 1;
	/**
	 * Check if enabled
	 *
	 * @return string|1|0
	 */
	public function isEnabled()
	{
		if($this->isGoogleAnalyticsAvailable()){
			return $this->scopeConfig->getValue(
				self::XML_CONFIG_PATH_ENHANCEDECOMMERCE_ENABLED,
				\Magento\Store\Model\ScopeInterface::SCOPE_STORE
			);
		}
		return 0;
	}

	/**
	 * Check if enabled for product detail page
	 *
	 * @return string|1|0
	 */
	public function isProductDetailEnabled()
	{
		if($this->isEnabled()){
			return $this->scopeConfig->getValue(
				self::XML_CONFIG_PATH_ENHANCEDECOMMERCE_PRODUCTDETAIL_ENABLED,
				\Magento\Store\Model\ScopeInterface::SCOPE_STORE
			);
		}
		return 0;
	}

	/**
	 * Check if enabled when product is adding to cart
	 *
	 * @return string|1|0
	 */
	public function isAddtocartEnabled()
	{
		if($this->isEnabled()){
			return $this->scopeConfig->getValue(
				self::XML_CONFIG_PATH_ENHANCEDECOMMERCE_ADDTOCART_ENABLED,
				\Magento\Store\Model\ScopeInterface::SCOPE_STORE
			);
		}
		return 0;
	}

	/**
	 * Check if enabled when product is removed from cart
	 *
	 * @return string|1|0
	 */
	public function isRemovetocartEnabled()
	{
		if($this->isEnabled()){
			return $this->scopeConfig->getValue(
				self::XML_CONFIG_PATH_ENHANCEDECOMMERCE_REMOVEFROMCART_ENABLED,
				\Magento\Store\Model\ScopeInterface::SCOPE_STORE
			);
		}
		return 0;
	}

	/**
	 * Check if enabled for product list and search page
	 *
	 * @return string|1|0
	 */
	public function isProductListEnabled()
	{
		if($this->isEnabled()){
			return $this->scopeConfig->getValue(
				self::XML_CONFIG_PATH_ENHANCEDECOMMERCE_PRODUCTLIST_ENABLED,
				\Magento\Store\Model\ScopeInterface::SCOPE_STORE
			);
		}
		return 0;
	}

	/**
	 * Check if enabled when coupon applied in cart/checkout
	 *
	 * @return string|1|0
	 */
	public function isCouponAppliedEnabled()
	{
		if($this->isEnabled()){
			return $this->scopeConfig->getValue(
				self::XML_CONFIG_PATH_ENHANCEDECOMMERCE_COUPONAPPLIED_ENABLED,
				\Magento\Store\Model\ScopeInterface::SCOPE_STORE
			);
		}
		return 0;
	}

	/**
	 * Check if enabled for product upsell
	 *
	 * @return string|1|0
	 */
	public function isUpsellEnabled()
	{
		if($this->isEnabled()){
			return $this->scopeConfig->getValue(
				self::XML_CONFIG_PATH_ENHANCEDECOMMERCE_UPSELL_ENABLED,
				\Magento\Store\Model\ScopeInterface::SCOPE_STORE
			);
		}
		return 0;
	}

	/**
	 * Check if enabled for related upsell
	 *
	 * @return string|1|0
	 */
	public function isRelatedEnabled()
	{
		if($this->isEnabled()){
			return $this->scopeConfig->getValue(
				self::XML_CONFIG_PATH_ENHANCEDECOMMERCE_RELATED_ENABLED,
				\Magento\Store\Model\ScopeInterface::SCOPE_STORE
			);
		}
		return 0;
	}
}