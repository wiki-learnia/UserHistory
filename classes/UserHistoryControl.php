<?php

	class SpecialUserHistoryControl extends SpecialPage {

		private $uhEntries;


		public function __construct() {
			parent::__construct('UserHistoryControl');
		}

		//
		// PUBLIC
		//

		public function execute($par) {
			global $wgRequest, $wgOut, $wgUser;

			$this->setHeaders();

			$wgOut->addModules( 'ext.uhControl' );

			# Get request data from, e.g.
			$param = $wgRequest->getText('param');

			# set page title
			$wgOut->setPageTitle(wfMsg('uh-special-title'));

			$this->uhEntries = UserHistoryAPI::getHistoryEntries($wgUser);

			# make HTML magic
			$html = '';
			$html .= '<p>'.wfMsg('uh-special-info').'</p>'."\n";
			$html .= '<div class="uh">'."\n";
			if(UserHistoryAPI::countHistoryItems($wgUser) > 0) {
				$d1 = 'block';
				$d2 = 'none';
			} else {
				$d2 = 'block';
				$d1 = 'none';
			}
			foreach ($this->uhEntries as $k => $entry) {
				$title = Title::newFromDBKey($entry);
				$html .= ' <div class="uhEntry i" name="'.$title->getDBKey().'">'."\n";
				$html .= '  <i class="remove" title="'.wfMsg('uh-special-del-title').'">X</i>'."\n";
				$html .= '  '.$title->getText()."\n";
				$html .= ' </div>'."\n";
			}
			$html .= ' <div class="uhEntry removeAll" title="'.wfMsg('uh-special-del-all').'" style="display:'.$d1.'">'."\n";
			$html .= '  '.wfMsg('uh-special-del-all')."\n";
			$html .= ' </div>'."\n";
			$html .= ' <div class="uhEntry none" style="display:'.$d2.'">'."\n";
			$html .= '  '.wfMsg('uh-special-none')."\n";
			$html .= ' </div>'."\n";
			$html .= '</div>'."\n";

			$wgOut->addHTML($html);
		}

		//
		// PRIVATE
		//
	}

?>