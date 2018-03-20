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
class Experiment {
	
    /**
     * Return an array of all available saved experients
     * @author Mitchell M.
     * @return array of stimuli
     * @version 0.5.0
     */
	public static function loadExperiments() {}
	
	
    /**
     * Builds and saves experiment based on input $data
     * @author Mitchell M.
     * @return crated experiment
     * @version 0.5.0
     */
	public function createExperiment($data) {}
	
    /**
     * Deletes experiment based on input id $data
     * @author Mitchell M.
     * @version 0.5.0
     */
	public function deleteExperiment($data) {}
	
    /**
     * Updates experiment based on input $id and $changes
     * @author Mitchell M.
     * @return updated experiment
     * @version 0.5.0
     */
	public function updateExperiment($id,$changes) {}
	
    /**
     * Creates runnable experiment by pairing a stimuli set with an experiment based on $stimID with $experimentID
     * @author Mitchell M.
     * @return runnable experiment configuration
     * @version 0.5.0
     */
	public function createRunnable($stimID,$experimentID) {}

}