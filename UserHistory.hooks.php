<?php
    /**
     * ArticleRecommend extension
     *
     * @file
     * @ingroup Extensions
     * @copyright 2012-2013 wiki-learnia.org
     * @license 
     */

class UserHistoryHooks {

    /**
     * Adding our moudle for CSS and JS
     * and updating the user's history
     *
     * This is attached to the BeforePageDisplay hook
     *
     * @param $out OutputPage
     * @param $skin Skin
     */
    public static function uhBeforePageDisplay( &$out, &$skin ) {
        
        // updating user's history
        if( $skin->getUser()->isLoggedIn() == 1 &&
            $out->isArticle() &&
            !($out->getTitle()->isMainPage() || $out->getTitle()->getNamespace() == NS_USER)
            ) {
            
            $history = new UserHistoryUpdater($skin);
            $history->updateUserHistory();
        }        
        return true;
    }

    /**
     * Adding our recommendation slider to the sidebar
     *
     * This is attached to the SkinBuildSidebar hook
     *
     * @param $skin Skin
     * @param $bar
     */
    public static function uhSkinBuildSidebar( $skin, &$bar ) {
        global $wgUhShowInSidebar, $wgUhTranslateTitle;
        if($wgUhShowInSidebar) {
            $historyOutput = new UserHistoryOutput($skin);
            if($wgUhTranslateTitle) {
                $bar[$skin->msg('uh-sidebarTitle')->text()] = $historyOutput->getHTML();
            } else {
                $bar['user history'] = $historyOutput->getHTML();
            }
        }
        return true;
    }

    /**
     * Adding options for a learn target and history
     *
     * This is attached to the GetPreferences hook
     *
     * @param $user User
     * @param $preferences
     */
    public static function uhGetPreferences( $user, &$preferences ) {
        global $wgHiddenPrefs;

        $sp = new SpecialPage('UserHistoryControl');
        if(preg_match('/^1\.23.*/', SpecialVersion::getVersion())) {
            $link = $sp->getPageTitle()->getFullUrl();
        } else {
            $link = $sp->getTitle()->getFullUrl();
        }
        $preferences['userHistory_link'] = array(
            'type' => 'info',
            'label-message' => 'uh-setting-his',
            'section' => 'misc',
            'default' => '<a href="'.$link.'">'.wfMsg('uh-special-title').'</a>',
            'raw' => true,
        );

		return true;
    }
}

?>