<?php
    namespace Hcode;

    use Rain\Tpl;

    class Page {

        private $tpl;
        private $options;
        private $defauts = [
            "data" => []
        ];

        public function __construct( $opts = array() ) {

            $this->options = array_merge( $this->defauts, $opts ); 

            // config
            $server = $_SERVER["DOCUMENT_ROOT"] . "/loja/"; // so pq to usando o localhost...
            $config = array(
                "tpl_dir"       => $server . "/views/",
                "cache_dir"     => $server . "/views-cache/",
                "debug"         => false // set to false to improve the speed
            );

            Tpl::configure( $config );

            $this->tpl = new Tpl;

            $this->setData( $this->options["data"] );

            $this->tpl->draw("header");

        }

        private function setData( $data = array() ) {

            foreach ($data as $key => $value) {
                $this->tpl->assign($key, $value);
            }

        }

        public function setTpl( $name, $data = array(), $returnHTML = false ) {

            $this->setData($data);

            return $this->tpl->draw( $name, $returnHTML );

        }

        public function __destruct() {
            $this->tpl->draw("footer");
        }

    }
?>