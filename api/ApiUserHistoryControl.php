<?php

	class ApiUserHistoryControl extends ApiBase {

		const UH_API_REMOVE_ALL = 0;
		const UH_API_REMOVE_SINGLE = 1;

		private $msgs;
		private $params;
		private $result = array();

		public function __construct( $query, $moduleName ) {
			parent::__construct( $query, $moduleName, '' );
		}

		//
		// PUBLIC
		//

		public function execute() {

	        $uhType = intval($this->getMain()->getVal('type'));
			$this->params = $this->extractRequestParams();

			switch($uhType) {
				case self::UH_API_REMOVE_ALL:
					$this->removeAll();
					break;
				case self::UH_API_REMOVE_SINGLE:
					$this->removeSingle($this->getMain()->getVal('entry'));
					break;
				default:
					break;
			}

			//$this->result = array('result' => $this->params);*/
			$this->getResult()->addValue( null, $this->getModuleName(), $this->result );
		}

		//
		// PRIVATE
		//

		private function removeAll() {
			global $wgUser;
			$uh = new UserHistoryAPI($wgUser);
			$uh->clearHistory();
			if($uh->length() == 0) {
				$this->result = array(
					'code' => 200);
			} else {
				$this->result = array(
					'code' => 400);
			}
		}

		private function removeSingle($title) {
			global $wgUser;
			$uh = new UserHistoryAPI($wgUser);
			$before = $uh->length();
			$uh->removeItemByTitle($title);

			if($uh->length() < $before) {
				$this->result = array(
					'code' => 200);
			} else {
				$this->result = array(
					'code' => 400);
			}
		}
	}

?>