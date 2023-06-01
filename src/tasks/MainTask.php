<?php

declare(strict_types=1);

namespace MyApp\Tasks;

use Phalcon\Cli\Task;

class MainTask extends Task
{
    public function mainAction()
    {
        // main action
    }

    public function placeOrderAction($uid, $product_id, $quantity)
    {
        // place new order here
        $sql = "INSERT INTO `orders`(`uid`, `pid`, `quantity`) VALUES ('$uid','$product_id','$quantity')";
        $result = $this->db->execute($sql);
        if ($result) {
            echo "Order Placed";
        } else {
            echo "There was some error";
        }
    }

    public function recommendAction($uid)
    {
        $sql = "SELECT * FROM `orders` WHERE `uid` = '$uid'";
        $orders = $this->db->fetchAll(
            $sql,
            \Phalcon\Db\Enum::FETCH_ASSOC
        );
        $products = [];
        foreach ($orders as $value) {
            $products[] = $value['pid'];
        }
        // find all the categories from which user has purchased products
        $categories = [];
        foreach ($products as $value) {
            $sql = "SELECT `category` FROM `products` WHERE `prod_id` = '$value'";
            $result = $this->db->fetchAll(
                $sql,
                \Phalcon\Db\Enum::FETCH_ASSOC
            );
            foreach ($result as $value) {
                $categories[] = $value['category'];
            }
        }
        // display all the items as per the category of purchase
        foreach ($categories as $value) {
            $sql = "SELECT `title` FROM `products` WHERE `category` = '$value'";
            $result = $this->db->fetchAll(
                $sql,
                \Phalcon\Db\Enum::FETCH_ASSOC
            );
            foreach ($result as $purchasedCategory) {
                print_r($purchasedCategory['title']);
                echo PHP_EOL;
            }
        }
    }
}
