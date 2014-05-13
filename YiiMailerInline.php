<?php

require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'CssToInlineStyles'.DIRECTORY_SEPARATOR.'CssToInlineStyles.php';

class YiiMailerInline extends YiiMailer implements IApplicationComponent  {

    public $defaults;
    public $managementAddress;
    
    private $_initialized=false;
    
    public function init()
    {
            
        if(isset($this->defaults['bcc'])){
            $this->setBcc($this->defaults['bcc']);
        }
        
        if(isset($this->defaults['from']) && isset($this->defaults['fromName'])){
            $this->setFrom($this->defaults['from'], $this->defaults['fromName']);            
        }
            
        $this->_initialized=true;
    }
    
    public function getIsInitialized()
    {
            return $this->_initialized;
    }    

    public function render() {
        parent::render();
        
        $inliner = new \TijsVerkoyen\CssToInlineStyles\CssToInlineStyles($this->Body, file_get_contents(Yii::app()->theme->getBasePath()."/css/mail.css"));
        
        $this->Body = $inliner->convert();
        
    }
    
}
