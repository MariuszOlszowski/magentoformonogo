<?php

class Monogo_Currency_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {   
        echo '<form action="" method="POST">
            <input type="Text" name="Currency" placeholder="Podaj wartość USD" />
            <input type="submit" value="Policz" />
        </form>';
        
        if( isSet( $_POST['Currency'] ) ) {
            $Currency = (float) $_POST['Currency'];
            
            $GET_ADDR = 'http://www.nbp.pl/kursy/xml/dir.txt';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$GET_ADDR);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            $ADDR = curl_exec($ch);			 
            curl_close($ch);
            
            $ADDR = substr(file_Get_contents( 'http://www.nbp.pl/kursy/xml/dir.txt' ), 3, 11 );
            
            $SITE = 'http://www.nbp.pl/kursy/xml/' . $ADDR . '.xml';
            
            echo 'Pobieranie danych z: ' . $SITE;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$SITE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            $XML = curl_exec($ch);			 
            curl_close($ch);

            $oXML = new SimpleXMLElement($XML);

            foreach($oXML as $Element ){
                if( $Element->nazwa_waluty == 'dolar amerykański' ) {
                    $KursK = (float) str_replace( ',', '.', $Element->kurs_kupna);
                    $KursS = (float) str_replace( ',', '.', $Element->kurs_sprzedazy);
                    
                    echo '<h1>'.$Element->nazwa_waluty.'</h1>';
                    echo 'Kurs kupno/sprzedaż: ' . $KursK .' / '. $KursS . '<br/>';
                    echo 'Koszt kupna ' . $Currency . ' USD - ' . round($Currency * $KursS, 2) . ' PLN<br/>' . 'Zysk ze sprzedaży ' . $Currency  . ' USD - ' . round($Currency  * $KursK, 2) . ' PLN';
                    break;
                }
            }   
        }
    }
}