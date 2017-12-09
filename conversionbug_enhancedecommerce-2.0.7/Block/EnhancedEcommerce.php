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
class EnhancedEcommerce extends \Magento\Framework\View\Element\Template
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
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    protected $_storeManager;

    /**
     * @param \Magento\Catalog\Model\Session $catalogSession
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Session $catalogSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \ConversionBug\EnhancedEcommerce\Helper\Data $helper,
        array $data = []

    )
    {
        parent::__construct($context, $data);
        $this->_catalogSession = $catalogSession;
        $this->_storeManager = $storeManager;
        $this->_helper = $helper;
    }
    public function getHelper(){
        return $this->_helper;
    }
    public function getStoreManager(){
        return $this->_storeManager;
    }
    public function setSessionData($key, $value)
    {
        return $this->_catalogSession->setData($key, $value);
    }

    public function getSessionData($key, $remove = false)
    {
        return $this->_catalogSession->getData($key, $remove);
    }

    public function getCatalogSession()
    {
        return $this->_catalogSession;
    }
}