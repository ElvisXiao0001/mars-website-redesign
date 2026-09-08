<?php

namespace Arts\NoticeManager\Managers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Abstract class BaseManager
 *
 * This class serves as a base for managers of the plugin.
 *
 * @package Arts\NoticeManager\Managers
 */
abstract class BaseManager {
	/**
	 * Arguments for the manager.
	 *
	 * @var array
	 */
	protected $args;

	/**
	 * Other managers used by the current manager.
	 *
	 * @var \stdClass|null
	 */
	protected $managers;

	/**
	 * Constructor for the BaseManager class.
	 *
	 * @param array $args Arguments for the manager.
	 */
	public function __construct( $args = array() ) {
		$this->args = $args;
	}

	/**
	 * Initialize the manager with other managers.
	 *
	 * @param \stdClass $managers Other managers used by the current manager.
	 */
	public function init( $managers ) {
		$this->add_managers( $managers );
	}

	/**
	 * Add other managers to the current manager.
	 *
	 * @param \stdClass $managers Other managers used by the current manager.
	 */
	protected function add_managers( $managers ) {
		if ( ! isset( $this->managers ) ) {
			$this->managers = new \stdClass();
		}

		foreach ( (array) $managers as $key => $manager ) {
			// Prevent adding self to the managers property to avoid infinite loop.
			if ( $manager !== $this ) {
				$this->managers->$key = $manager;
			}
		}

		return $this;
	}
}
