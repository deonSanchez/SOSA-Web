<?php
/**
 * Stimulus
 *
 * @category   Class
 * @package    com.SOSA-Web.classes.Stimulus
 * @author     Mitchell M. <mm11096@georgiasouthern.edu>
 * @version    Release: 0.5.0
 * @since      Class available since Release 0.50
 */
require_once __DIR__ . '/config.php';
class Stimulus {
	
    /**
     * Return an array of all available saved stimuli
     * @author Mitchell M.
     * @return array of stimuli
     * @version 0.5.0
     */
	public static function loadStimuli() {}
	
    /**
     * Builds and saves stimuli based on input $data
     * @author Mitchell M.
     * @return created stimulus
     * @version 0.5.0
     */
	public function createStimulus($data) {}
	
    /**
     * Deletes stimuli based on input id $data
     * @author Mitchell M.
     * @version 0.5.0
     */
	public function deleteStimulus($data) {}
	
    /**
     * Updates stimuli based on input $id and $changes
     * @author Mitchell M.
     * @return created stimulus
     * @version 0.5.0
     */
	public function updateStimulus($id,$changes) {}
	
	
}