<?php

class Monogo_Currency_Model_Post extends Mage_Core_Model_Abstract {
    protected function _construct()  {
        $this->_init('Monogo_Currency/post');
    }
    
    public function isFilled() {
        
        return isSet( $_POST['Currency'] );
    }
    
    public function getResult( $K, $S ) {
        return 'Koszt kupna: ' . $K . '; Zysk ze sprzedaży: ' . $S;
    }
    
    public function getCurrency() {
        $GET_ADDR = 'http://www.nbp.pl/kursy/xml/dir.txt';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$GET_ADDR);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $ADDR = curl_exec($ch);			 
        curl_close($ch);

        $ADDR = substr(file_Get_contents( 'http://www.nbp.pl/kursy/xml/dir.txt' ), 3, 11 );

        $SITE = 'http://www.nbp.pl/kursy/xml/' . $ADDR . '.xml';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$SITE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $XML = curl_exec($ch);			 
        curl_close($ch);

        $oXML = new SimpleXMLElement($XML);
        $KursK = $KursS = 0;

        foreach( $oXML as $Element ){
            if( $Element->nazwa_waluty == 'dolar amerykański' ) {
                $KursK = (float) str_replace( ',', '.', $Element->kurs_kupna);
                $KursS = (float) str_replace( ',', '.', $Element->kurs_sprzedazy);
                break;
            }
        }
        
        return array( $KursK, $KursS );
    }
}