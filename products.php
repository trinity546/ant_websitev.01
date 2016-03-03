<?php

require_once('init.php');
loadScripts();

    $data = array("status" => "not set!");

    if(Utils::isGET()) {
        $pm = new ProductManager();
        $rows = $pm->listProducts();

        $html = "";
        //var_dump($rows)fdsafdsa;
        //echo $rows == 0;
        if($rows == null) {
            echo Messages::getAllMessagesHTMLList();
            return;
        }
        foreach($rows as $row) {
            $sku = $row['SKU'];
            $price = $row['item_price'];
            $desc = $row['description'];
            $img = $row['path'];
            $name = $row['name'];
            $html .= "
               <div class='row'>
                    <div class='col-md-7'>
                        <img data-sku-path='$sku' src='$img'  class='img-responsive img-hover'>
                    </div>
                    <div class='col-md-5'>
                        <h3 data-sku-name='$sku'>$name</h3>
                        <p data-sku-desc='$sku'>$desc</p>
                        <input data-sku-qty='$sku' type='number' value='1' min='1' max='10' step='1'/>
                        <h4 data-sku-price='$sku'>$price</h4>
                        <a ><input data-sku-add='$sku'  type='button' class='startCart btn btn-primary' value='Add To Cart '/></a>      
                </div>  
                </div>
                <hr>
                      ";
          
        }

        echo $html;
        return;

    } else {
        $data = array("status" => "error", "msg" => "Only GET allowed.");

    }

    echo json_encode($data, JSON_FORCE_OBJECT);



?>