<?php
    /**
     * UserHistory extension
     *
     * @file
     * @ingroup Extensions
     * @copyright 2012-2013 wiki-learnia.org
     * @license 
     */
     
    // Textfeld aus den Einstellungen zeilenweise auslesen
    // dazu einen Miniparser schreiben, der das tut
    // gleichzeitig den Anfang jeder Zeile überprüfen, ob ein Startsymbol da ist
    

    /* Configuration */
    
    global $uhListSplitter;
    $uhListSplitter = '$$';
    
    $uhMaxEntries = 10;
    
    $wgUhTranslateTitle = true;
    
    $wgUhShowInSidebar = true;

    $wgDefaultUserOptions['userHistory'] = '';
    
    /* Setup */

    $wgExtensionCredits['other'][] = array(
        'path' => __FILE__,
        'name' => 'UserHistory',
        'author' => 'Felix Beuster',
        'version' => '0.1',
        'url' => 'http://wiki-learnia.org',
        'descriptionmsg' => 'uh-extDescr',
    );
    
    $dir = dirname(__FILE__).'/';
    
    // Localisation
    $wgExtensionMessagesFiles['UserHistory'] = $dir . 'UserHistory.i18n.php';
    $wgExtensionMessagesFiles['UserHistoryAlias'] = $dir . 'UserHistory.alias.php';

    // class loading
    $wgAutoloadClasses['UserHistoryUpdater'] = $dir.'/classes/UserHistoryUpdater.php';
    $wgAutoloadClasses['UserHistoryOutput'] = $dir.'/classes/UserHistoryOutput.php';
    $wgAutoloadClasses['UserHistoryAPI'] = $dir.'/classes/UserHistoryAPI.php';
    $wgAutoloadClasses['SpecialUserHistoryControl'] = $dir.'/classes/UserHistoryControl.php';
    $wgAutoloadClasses['ApiUserHistoryControl']     = $dir. '/api/ApiUserHistoryControl.php';
    
    // hook integration
    $wgAutoloadClasses['UserHistoryHooks'] = $dir.'UserHistory.hooks.php';
    $wgHooks['BeforePageDisplay'][] = 'UserHistoryHooks::uhBeforePageDisplay';
    $wgHooks['SkinBuildSidebar'][] = 'UserHistoryHooks::uhSkinBuildSidebar';
    $wgHooks['GetPreferences'][] = 'UserHistoryHooks::uhGetPreferences';

    // add special page
    $wgSpecialPages['UserHistoryControl']      = 'SpecialUserHistoryControl';
    $wgSpecialPageGroups['UserHistoryControl'] = 'other';

    // api registration
    $wgAPIModules['uh'] = 'ApiUserHistoryControl';

    // Modules
    $wgResourceUh = array(
        'localBasePath' => dirname( __FILE__ ).'/modules',
        'remoteExtPath' => 'UserHistory/modules',
        'group' => 'ext.uh'
    );
    $wgResourceModules['ext.uhControl'] = array(
        'scripts' => 'ext.uhControl.js',
        'styles' => 'ext.uhControl.css',
        /*'messages' => array(
            'wlsn-config-connect-text',
            'wlsn-config-connection-released')*/
    ) + $wgResourceUh;

?>