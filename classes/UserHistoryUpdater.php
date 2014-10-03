<?php
    class UserHistoryUpdater {
        private $recentArticles = '';
        private $currentTitle   = '';
        private $maxEntries     = 10;
        private $splitter       = '';
        private $skin           = '';
        
        /*** PUBLIC ***/
        
        /**
         * call this first
         *
         * @param $skin Skin
         */
        public function __construct($skin) {
            global $uhListSplitter, $uhMaxEntries;
            
            $this->splitter = $uhListSplitter;
            $this->maxEntries = $uhMaxEntries;
            $this->skin = $skin;
            
            $this->getDBRecentArticles();
            $this->getCurrentArticleTitle();
        }

        /**
         * updating the users history
         */
        public function updateUserHistory() {
            
            // is the current an existing article
            if($this->isCurrentValidArticle()) {
                // how many entries do we have?
                if(count($this->recentArticles) > 0) {
                    // article already in history?
                    $position = array_search($this->currentTitle, $this->recentArticles);
                    if($position !== false) {
                        $this->removeHistoryItem($position);
                    }
                    while(UserHistoryAPI::countHistoryItems($this->skin->getUser()) >= $this->maxEntries) {
                            $this->removeHistoryItem(0);
                    }
                    $this->addHistoryItem($this->currentTitle);
                } else {
                    // there is no entry
                    $this->addHistoryItem($this->currentTitle);
                }
            }
        }
        
        /*** get / set ***/
        
        /**
         * request history from user
         */
        public function getRecentArticles() {
            return $this->recentArticles;
        }
        
        /*** PRIVATE ***/
        
        /**
         * request history from user
         */
        private function getDBRecentArticles() {
            $this->recentArticles = UserHistoryAPI::getHistoryEntries($this->skin->getUser());
        }
        
        /**
         * request article title, keep it db safe
         */
        private function getCurrentArticleTitle() {
            $this->currentTitle = $this->skin->getTitle()->getDBkey();
        }
        
        /**
         * appending the current article title db key to the history
         */
        private function appendArticle() {
            $this->recentArticles .= $this->currentTitle;
        }
        
        /**
         * appending a splitter and the current article title db key to the history
         */
        private function addHistoryItem($title) {
            UserHistoryAPI::addHistoryItem($this->skin->getUser(), $title);
        }

        private function removeHistoryItem($position) {
            UserHistoryAPI::removeHistoryItem($this->skin->getUser(), $position);
        }

        private function isCurrentValidArticle() {
            $title = Title::newFromText($this->currentTitle);
            return $title->exists();
        }
    }
?>