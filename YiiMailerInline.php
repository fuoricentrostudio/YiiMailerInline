<?php

Yii::setPathOfAlias('TijsVerkoyen', Yii::getPathOfAlias('ext').DIRECTORY_SEPARATOR.'YiiMailerInline');

Yii::import('ext.YiiMailerInline.YiiMailer');
Yii::import('TijsVerkoyen.CssToInlineStyles.CssToInlineStyles');

class YiiMailerInline extends YiiMailer implements IApplicationComponent  {

    public $defaults;
    public $managementAddress;
    public $cssFileName = 'mail.css';
    public $cssFilePath;

    private $_initialized=false;

    public function init()
    {

        if(isset($this->defaults['bcc'])){
            $this->setBcc($this->defaults['bcc']);
        }

        if(!$this->cssFilePath){
            $this->cssFilePath = Yii::app()->theme->getBasePath().DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR;
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
        
        $return = parent::render();
        
        $this->inline();
        
        return $return;

    }
    
    public function inline(){
        
        try{
            
            $inliner = new \TijsVerkoyen\CssToInlineStyles\CssToInlineStyles($this->Body, file_get_contents($this->cssFilePath.$this->cssFileName));
            $this->Body = $inliner->convert();
            
            return true;
            
        } catch (Exception $e){
            Yii::trace('Cannot convert CSS to inline. Error:'.$e);
        }
        
        return false;
    }
   

}