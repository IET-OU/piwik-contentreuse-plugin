<?php
/**
 * Extending Piwik for the JISC Track OER project.
 * 
 * @link http://track.olnet.org
 * @license
 * @copyright 2012 The Open University.
 * @author N.D.Freear, 23 August 2012.
 *
 * @category Piwik_Plugins
 * @package Piwik_TrackOER
 */
//require_once PIWIK_INCLUDE_PATH .'/core/Tracker/Visit.php';

/**
 *
 * @package Piwik_TrackOER
 */
class Piwik_ContentReuse extends Piwik_Plugin
{
	public function getInformation()
	{
		return array(
			'description' =>
			'* A plugin to extend Piwik_Tracker_Visit for content re-use [JISC Track OER project]',
			#Piwik_Translate('CoreAdminHome_PluginDescription'),
			'homepage' => 'http://track.olnet.org/',
			'author' => 'IET at The Open University',
			'author_homepage' => 'http://iet.open.ac.uk/',
			'version' => '0.1',
			'translationAvailable' => false,
			'TrackerPlugin' => true,
		);
	}

	public function getListHooksRegistered()
	{
		return array(
			'Tracker.getNewVisitObject' => 'getNewVisitObject',
		);
	}

	/**
	 * @param Piwik_Event_Notification $notification  notification object
	 */
	function getNewVisitObject( $notification )
	{
		$visit = &$notification->getNotificationObject();

		$visit = new Reuse_Tracker_Visit();

		// ?
		//$visit = new Piwik_Tracker_Visit( self::$forcedIpString, self::$forcedDateTime );
		//$visit->setForcedVisitorId(self::$forcedVisitorId);
	}

}


class Reuse_Tracker_Visit extends Piwik_Tracker_Visit
{
	function setRequest($requestArray)
	{
		//$this->request = $requestArray;

		parent::setRequest($requestArray);

		// When the 'url' parameter is not given (but the referrer may be given), assume we are in the 'Content Reuse' mode.
		// The URL can default to the Referer, which will be in this case
		// the URL of the page containing the Simple Image beacon
		if(/*empty($this->request['urlref'])
			&&*/ empty($this->request['url']))
		{
			$this->request['url'] = @$_SERVER['HTTP_REFERER'];

			// Debugging.
			Piwik_Common::sendHeader('X-'.__CLASS__.': 1');
			#Piwik_Common::sendHeader('X-urlref: '. $this->request['urlref']);
		}
	}
}
