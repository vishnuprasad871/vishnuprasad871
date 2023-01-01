<?php


namespace SuperWebShare\Magento\Block;

use Magento\Cms\Block\Page;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;


/**
 * Class SuperWebShare
 * @package SuperWebShare\Magento\Block
 */
class SuperWebShare extends Template
{
   
    /**
     * SuperWebShare constructor.
     *
     * @param Context $context
     * @param HelperData $helperData
     * @param Page $page
     * @param array $data
     */
    public function __construct(
        Context $context,
        Page $page,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->_page       = $page;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    public function getButtonColor(){
        return $this->scopeConfig->getValue(
            'superwebshare/general/button_color',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getTextColor(){
        return $this->scopeConfig->getValue(
            'superwebshare/general/button_text_color',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        
    }
    public function getButtonText(){
        return $this->scopeConfig->getValue(
            'superwebshare/general/button_text',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        
    }
    public function getSVGText(){
        return $this->scopeConfig->getValue(
            'superwebshare/general/svg_text',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        
    }

    
    
   
}