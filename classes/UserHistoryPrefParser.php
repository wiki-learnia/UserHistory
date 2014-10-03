<?php

    class UserHistoryPrefParser {
        
        public static function getUserHistoryTitles() {
            $prefString = trim($this->user->getOption('userHistory'));
            $prefSplittet = preg_split("/\r\n|\r/", $prefString);
            $return = array();
            foreach($prefSplitted as $split) {
                $split = trim($split);
                if(strlen($split) > 0) {
                    if($split[0] == '*') {
                        $return[] = substr($split, 1);
                    }
                }
            }
            return $return;
        }
    }
    
?>