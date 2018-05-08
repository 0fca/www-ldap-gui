<?php 
    final class GroupModel{
        private $groupName;
        private $desc;
        private $userList = array();

        public function __construct($name, $desc, $userArray){
            $this->groupName = $name;
            $this->desc = $desc;
            $this->userList = $userArray;
        }

        public function getName(){
            return $this->groupName;
        }

        public function getDesc(){
            return $this->desc;
        }

        public function getUserList(){
            return $this->userList;
        }
    }
?>