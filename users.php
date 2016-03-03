<?php

require_once('init.php');
loadScripts();

    $data = array("status" => "not set!");

    if(Utils::isPOST()) {
        // post means either to delete or add a user
        $parameters = new Parameters("POST");

        $action = $parameters->getValue('action');
        $sku = $parameters->getValue('sku');

        //$data = array("action" => $action, "sku" => $sku);
        if($action == 'delete' && !empty($sku)) {

            $um = new UserManager();
            $um->deleteUser($sku);
            $data = array("status" => "success", "msg" => "User '$sku' deleted.");
            echo json_encode($data, JSON_FORCE_OBJECT);
            return;

        } else if($action == 'update' && !empty($sku)) {
            $newFirstName = $parameters->getValue('newFirstName');

            if(!empty($newFirstName)) {

                $um = new UserManager();
                $count = $um->updateUserFirstName($sku, $newFirstName);
                if($count > 0) {
                    $data = array("status" => "success", "msg" =>
                        "User '$sku' updated with new first name ('$newFirstName').");
                } else {
                    $data = array("status" => "fail", "msg" =>
                        "User '$sku' was NOT updated with new first name ('$newFirstName').");
                }
            } else {
                $data = array("status" => "fail", "msg" =>
                    "New user name must be present - value was '$newFirstName' for '$sku'.");

            }
            echo json_encode($data, JSON_FORCE_OBJECT);
            return;

        } else if($action == 'add') {
            $newFirstName = $parameters->getValue('newFirstName');
            $newLastName = $parameters->getValue('newLastName');
            $newsku = $parameters->getValue('newsku');

            if(!empty($newFirstName) && !empty($newLastName) && !empty($newsku)) {
                $data = array("status" => "success", "msg" => "User added.");
                $um = new UserManager();
                $um->addUser($newFirstName, $newLastName, $newsku);

            } else {
                $data = array("status" => "fail", "msg" => "First name, last name, and user name cannot be empty.");
            }
            echo json_encode($data, JSON_FORCE_OBJECT);
            return;

        }else {
            $data = array("status" => "fail", "msg" => "Action not understood.");
        }

        echo json_encode($data, JSON_FORCE_OBJECT);
        return;

    } else if(Utils::isGET()) {
        // get means get the list of users
        $um = new UserManager();
        $rows = $um->listUsers();
        $html = "";
        if($rows != null) {

            foreach($rows as $row) {
                $sku = $row['sku'];
                $name = $row['name'];
                $desc = $row['description'];
                $path = $row['path'];
                $price = $row['item_price'];
                $html .= "<tr>
                  <td class='sku'><span>$sku</span></td>
                  <td class='name'><span>$name</span></td>
                  <td class='desc'><span>$desc</span></td>
                  <td class='path'><span>$path</span></td>
                  <td class='price'><span>$price</span></td>
                  <td><input id='d-$sku' class='delete' type='button' value='Delete'/></td>
                  <td><input id='u-$sku' class='update' type='button' value='Update'/></td>
                  </tr>";
            }
            echo $html;

        } else {
            // shouldn't be but ...
            echo 'The returned rows is "null". No user table perhaps?';
        }

        return;

    } else {
        $data = array("status" => "error", "msg" => "Only GET and POST allowed.");
        echo json_encode($data, JSON_FORCE_OBJECT);
    }



?>
