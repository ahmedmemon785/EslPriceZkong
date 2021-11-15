<?php


class EslItem
{

    private $sellPrice;
    private $discountedPrice;
    private $stock;
    private $title;
    private $itemCode;

    /**
     * @return mixed
     */
    public function getItemCode()
    {
        return $this->itemCode;
    }
    private $barCode;
    private $attrCategory;
    private $attrName;



    /**
     * EslItem constructor.
     */
    public function __construct($itemCode)
    {

        $this->itemCode=$itemCode;
    }
    /**
     * @return mixed
     */
    public function getSellPrice()
    {
        return $this->sellPrice;
    }
    /**
     * @param mixed $sellPrice
     */
    public function setSellPrice($sellPrice): void
    {
        $this->sellPrice = $sellPrice;
    }
    /**
     * @return mixed
     */
    public function getDiscountedPrice()
    {
        return $this->discountedPrice;
    }
    /**
     * @param mixed $discountedPrice
     */
    public function setDiscountedPrice($discountedPrice): void
    {
        $this->discountedPrice = $discountedPrice;
    }
    /**
     * @return mixed
     */
    public function getStock()
    {
        return $this->stock;
    }
    /**
     * @param mixed $stock
     */
    public function setStock($stock): void
    {
        $this->stock = $stock;
    }
    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;



    }
    public function setItemDefaults($item){
        $item->barCode=$this->itemCode;
        $item->attrCategory='default';
        $item->attrName='default';
        return $item;

    }
    public function updateSku($token=''){
        $item=new stdClass();

        if(empty($this->title) || empty($this->sellPrice))
            return 'Item price and title are neccessary to run update';

        if(!empty($this->discountedPrice)){
            $item->memberPrice=$this->discountedPrice;
        }
        if(!empty($this->title)){
            $item->itemTitle=$this->title;
        }
        if(!empty($this->stock)){
            $item->stock1=$this->stock;
        }
        if(!empty($this->sellPrice)){
            $item->price=$this->sellPrice;
        }
        if(!empty($this->itemCode)){
            $item->productCode=$this->itemCode;
        }

        $item=$this->setItemDefaults($item);
        $item=json_encode($item);
        $arr='{
                "agencyId": "'.$this->agency_id.'",
                "merchantId": "'.$this->merchant_id.'",
                "unitName": 0,
                "itemList": ['.$item.']
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
                "agencyId": "1558577702698",
                "merchantId": "1632468509994",
                "unitName": 0,
                "itemList": ['.$item.']
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: '.$token
            ),
        ));

        $response = curl_exec($curl);
die($response);
        curl_close($curl);
        die($response);
        echo $response;
    }


}