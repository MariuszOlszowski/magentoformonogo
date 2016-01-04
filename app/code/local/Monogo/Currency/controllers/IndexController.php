<?php

class Monogo_Currency_IndexController extends Mage_Core_Controller_Front_Action
{
    public function postAction()
    {        
        $this->loadLayout();
        $Form = Mage::getModel('Monogo_Currency/post');
        
        ///$this->getChild('content')->setHtml('asd');
        
        if( $Form->isFilled() ) {
            $Currency = (float) str_replace( ',', '.', $_POST['Currency'] );
            list( $KursK, $KursS ) = $Form->getCurrency();
            $this->getLayout()->getBlock('content')->setText( $Form->getResult( round($Currency * $KursS, 2), round($Currency * $KursK, 2) ) );
        }
        
        $this->renderLayout();
    }
}