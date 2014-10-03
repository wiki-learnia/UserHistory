<?php

    class UserHistoryOutput {
        
        private $html = '';
        private $skin = '';
        private $articleKeys = '';
        
        /*** PUBLIC ***/
        
        /**
         * constructor
         *
         * @param $skin Skin
         */
        public function __construct($skin) {
            $this->skin = $skin;
            
            $this->requestArticleKeys();
            $this->makeHTML();
        }
        
        /*** get / set ***/
        
        /**
         * get's the HTML code
         *
         * @return String
         */
        public function getHTML() {
            return $this->html;
        }
        
        /*** PRIVATE ***/
        
        /**
         * request the articles from API
         */
        private function requestArticleKeys() {
            $this->articleKeys = UserHistoryAPI::getHistoryEntries($this->skin->getUser());
        }
        
        /**
         * build up the HTML code
         */
        private function makeHTML() {
            $html = '';
            $html .= '<ul class="userHistory">'."\n";
            foreach($this->articleKeys as $key) {
                $title = Title::makeTitle(NS_MAIN, $key);
                $html .= ' <li name="'.$title->getDBKey().'"><a href="'.$title->getFullUrl().'">'.$title->getText().'</a></li>'."\n";
            }
            $html .= '</ul>'."\n";
            $this->html = $html;
        }
    }
    
?>