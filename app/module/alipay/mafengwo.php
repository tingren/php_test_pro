<?php
class Alipay_Mafengwo extends ModelMultiMongo {
    public function __construct($config) {
        $this -> table = "infos";
        $this -> init($config);
    }

}
