<?php



//require "phpseclib/Crypt/RSA.php";

class Esl
{

    private $user;
    private $password;
    private $merchant_id;
    private $agency_id;

    public function __construct($username, $password,$merchant_id,$agency_id)
    {
        $this->user = $username;
        $this->password = $password;
        $this->merchant_id = $merchant_id;
        $this->agency_id = $agency_id;
    }


    public function updateEslItem(EslItem $item){

       echo $this->updateSku($item);
    }
    public function updateSku(EslItem $eslItem){
        $stdItem=new stdClass();

        if(empty($eslItem->getTitle()) || empty($eslItem->getSellPrice()))
            return 'Item price and title are neccessary to run update';

        if(!empty($eslItem->getDiscountedPrice())){
            $stdItem->memberPrice=$eslItem->getDiscountedPrice();
        }
        if(!empty($eslItem->getTitle())){
            $stdItem->itemTitle=$eslItem->getTitle();
        }
        if(!empty($eslItem->getStock())){
            $stdItem->stock1=$eslItem->getStock();
        }
        if(!empty($eslItem->getSellPrice())){
            $stdItem->price=$eslItem->getSellPrice();
        }
        if(!empty($eslItem->getItemCode())){
            $stdItem->productCode=$eslItem->getItemCode();
        }

        $stdItem=$eslItem->setItemDefaults($stdItem);
        $stdItem=json_encode($stdItem);
        $arr='{
                "agencyId": "'.$this->agency_id.'",
                "merchantId": "'.$this->merchant_id.'",
                "unitName": 0,
                "itemList": ['.$stdItem.']
            }';
//        echo($arr);
//        die();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'esl.zkong.com:9999/item/batchImportItem',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "agencyId": "'.$this->agency_id.'",
                "merchantId": "'.$this->merchant_id.'",
                "unitName": 0,
                "itemList": ['.$stdItem.']
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: '.$this->getToken()
            ),
        ));

        $response = curl_exec($curl);
        die($response);
        curl_close($curl);
        die($response);
        echo $response;
    }

    public function getErpPublicKey()
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'esl.zkong.com:9999/user/getErpPublicKey',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',

        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);
        if ($response) {
            return $response->data ?? '';
        }
        return false;

    }

    public function getToken()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'esl.zkong.com:9999/user/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "account" :"'.$this->user.'",
                "loginType":"3",
                "password": "'.$this->encryptedPassword().'"
               }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);
        if ($response) {
            return $response->data->token ?? false;
        }
    }

    public function encryptedPassword()
    {
        $publicKey = $this->getErpPublicKey();
//        $key = RSA::loadFormat('PKCS1', $publicKey);
        $password = $this->password;
//       $res= openssl_public_encrypt('Daniyal888',  $result, $publicKey, 'OPENSSL_PKCS1_PADDING');
//        $key = PublicKeyLoader::load($publicKey);
//        var_dump( $key);
        return 'bRrPQYAO+k909XrbZcZXugEJtGIWW6LSXy5hId0xZy8A+fuZmK6qCEpUcfwG8Isate7rSCfXOahK4A8/lYINIotudD4Ao6LU1fZCVI/ZskUVaq0Oa206ernA4L/t/JGcCTaFJU8JD/M6QgE317yiC/UXeBvhRhHKK9xEPpc73Ko=';

    }
}