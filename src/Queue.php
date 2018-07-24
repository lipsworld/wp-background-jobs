<?php
/**
 * Created by PhpStorm.
 * User: serge
 * Date: 24/07/2018
 * Time: 16:17
 */

namespace SergeLiatko\WPBackgroundJobs;

/**
 * Class Queue
 *
 * @package SergeLiatko\WPBackgroundJobs
 */
class Queue {

	/**
	 * Contains queue name.
	 * 
	 * @var string $name
	 */
	protected $name;

	/**
	 * Queue constructor.
	 *
	 * @param string $name
	 */
	public function __construct( $name = '' ) {
		$this->setName( $name );
		add_action( $this->getName(), array( $this, 'execute' ), 10, 2 );
	}

	/**
	 * Returns queue name.
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets queue name.
	 * 
	 * @param string $name
	 *
	 * @return Queue
	 */
	public function setName( $name = '' ) {
		$name = sanitize_key( $name );
		if ( empty( $name ) ) {
			$name = sanitize_key( get_class( $this ) );
		}
		$this->name = $name;

		return $this;
	}

	/**
	 * Executes scheduled job from the queue.
	 * 
	 * @param callable $job
	 * @param array    $args
	 */
	public function execute( $job, array $args = array() ) {
		if ( is_callable( $job ) ) {
			call_user_func_array( $job, $args );
		}
	}

	/**
	 * Adds job to the queue.
	 * 
	 * @param callable $job
	 * @param array    $args
	 *
	 * @return bool
	 */
	public function add( $job, array $args = array() ) {
		return ! ( false === wp_schedule_single_event( time(), $this->getName(), array( $job, $args ) ) );
	}
}
