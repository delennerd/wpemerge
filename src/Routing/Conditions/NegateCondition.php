<?php

namespace WPEmerge\Routing\Conditions;

use WPEmerge\Requests\Request;

/**
 * Negate another condition's result.
 *
 * @codeCoverageIgnore
 */
class NegateCondition implements ConditionInterface {
	/**
	 * Condition to negate.
	 *
	 * @var ConditionInterface
	 */
	protected $condition = [];

	/**
	 * Constructor.
	 *
	 * @param mixed $condition
	 */
	public function __construct( $condition ) {
		if ( is_a( $condition, ConditionInterface::class ) ) {
			$this->condition = $condition;
		} else {
			$this->condition = call_user_func( [ConditionFactory::class, 'make'], func_get_args() );
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function isSatisfied( Request $request ) {
		return ! $this->condition->isSatisfied( $request );
	}

	/**
	 * {@inheritDoc}
	 */
	public function getArguments( Request $request ) {
		return $this->condition->getArguments( $request );
	}
}