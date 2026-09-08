<?php

namespace Arts\LicenseManager\Managers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Abstract class BaseManager
 *
 * This class serves as a base for managers of the plugin.
 *
 * @package Arts\LicenseManager\Managers
 * @since 1.0.0
 */
abstract class BaseManager {
	/**
	 * Arguments for the manager.
	 *
	 * @var array
	 */
	protected $args;

	/**
	 * Array of text strings used by the manager.
	 *
	 * @var array
	 */
	protected $strings;

	/**
	 * Other managers used by the current manager.
	 *
	 * @var \stdClass
	 */
	protected $managers;

	/**
	 * Constructor for the BaseManager class.
	 *
	 * @param array $args    Arguments for the manager.
	 * @param array $strings Array of text strings used by the manager.
	 */
	public function __construct( $args = array(), $strings = array() ) {
		$this->args     = $args;
		$this->strings  = $strings;
		$this->managers = new \stdClass();
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
	 * @return BaseManager Returns the current instance for method chaining.
	 */
	protected function add_managers( $managers ) {
		foreach ( get_object_vars( $managers ) as $key => $manager ) {
			// Prevent adding self to the managers property to avoid infinite loop.
			if ( $manager !== $this ) {
				$this->managers->$key = $manager;
			}
		}

		return $this;
	}

	/**
	 * Copy an arg into the matching property only when present, leaving the declared default intact otherwise.
	 *
	 * @param string $property The name of the property to initialize.
	 *
	 * @return BaseManager Returns the current instance for method chaining.
	 */
	protected function init_property( $property ) {
		if ( isset( $this->args[ $property ] ) ) {
			$this->$property = $this->args[ $property ];
		}

		return $this;
	}

	/**
	 * Like init_property(), but only copies when the arg is a non-empty array so an empty
	 * or malformed value can't clobber a default.
	 *
	 * @param string $property The name of the property to initialize.
	 *
	 * @return BaseManager Returns the current instance for method chaining.
	 */
	protected function init_array_property( $property ) {
		if ( isset( $this->args[ $property ] ) && is_array( $this->args[ $property ] ) && ! empty( $this->args[ $property ] ) ) {
			$this->$property = $this->args[ $property ];
		}

		return $this;
	}
}
