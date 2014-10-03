<?php
    class UserHistoryAPI {
        
        private $user              = '';
        private $recentArticles    = '';
        private $recentArticlesArr = array();
        
        /*** PUBLIC ***/
        
        /**
         * constructor
         *
         * @param $user User
         */
        public function __construct($user) {
            $this->user = $user;
            
            $this->getRecentArticles();
            $this->parseUserOption();
        }
        
        /**
         * getting the user history
         * 
         * @retun array DB-Keys
         */
        public static function getHistoryEntries($user) {
            $api = new self($user);
            return $api->recentArticlesArr;
        }

        public static function addHistoryItem($user, $title) {
            $current = $user->getOption('userHistory');
            $user->setOption('userHistory', $current."\r*".$title);
            $user->saveSettings();
        }

        public static function removeHistoryItem($user, $position) {
            $api = new self($user);
            $api->removeItem($position);
            $api->saveHistory();
        }

        public static function countHistoryItems($user) {
            $api = new self($user);
            return count($api->recentArticlesArr);
        }

        public function removeItemByTitle($title) {
            foreach ($this->recentArticlesArr as $k => $v) {
                if($v == $title) {
                    $this->removeItem($k);
                    $this->saveHistory();
                }
            }
        }

        public function length() {
            return count($this->recentArticlesArr);
        }

        public function clearHistory() {
            $this->recentArticlesArr = array();
            $this->saveHistory();
        }
        
        /*** PRIVATE ***/
        
        /**
         * request history from user
         */
        private function getRecentArticles() {
            $this->recentArticles = trim($this->user->getOption('userHistory'));
        }

        /**
         *
         */
        private function parseUserOption() {
            $prefSplitted = preg_split("/\r\n|\r/", $this->recentArticles);
            $return = array();
            foreach($prefSplitted as $split) {
                $split = trim($split);
                if(strlen($split) > 0) {
                    if($split[0] == '*') {
                        $return[] = substr($split, 1);
                    }
                }
            }
            $this->recentArticlesArr = $return;
        }

        private function removeItem($position) {
            unset($this->recentArticlesArr[$position]);
        }

        private function saveHistory() {
            $this->makeString();
            $this->user->setOption('userHistory', $this->recentArticles);
            $this->user->saveSettings();
        }

        private function makeString() {
            $end = '';
            foreach($this->recentArticlesArr as $item) {
                if($end !== '') $end .= "\r";
                $end .= "*".$item;
            }
            $this->recentArticles = $end;
        }
    }
?>