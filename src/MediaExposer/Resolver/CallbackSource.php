<?php

namespace MediaExposer\Resolver;

use MediaExposer\SourceResolver;

/**
 * Callback source resolver
 *
 * It uses the configured $callbackSupports callback for the "supports" method,
 * the configured $callbackGetSource for the "getSource" method and the
 * configured $callbackGetSourceType for the "getSourceType" method. They all
 * will be calleds with two arguments: the model and its options.
 *
 * @package MediaExposer
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @author  Antoine Hérault <antoine.herault@gmail.com>
 */
class CallbackSource implements SourceResolver
{
    private $callbackSupports;
    private $callbackGetSource;
    private $callbackGetSourceType;

    /**
     * Constructor
     *
     * @param  callable $callbackSupports
     * @param  callable $callbackGetSource
     * @param  callable $callbackGetSourceType
     */
    public function __construct($callbackSupports, $callbackGetSource, $callbackGetSourceType)
    {
        $this->callbackSupports = $callbackSupports;
        $this->callbackGetSource = $callbackGetSource;
        $this->callbackGetSourceType = $callbackGetSourceType;
    }

    /**
     * {@inheritDoc}
     */
    public function supports($media, array $options = array())
    {
        return (boolean) call_user_func_array(
            $this->callbackSupports,
            array($media, $options)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getSource($media, array $options = array())
    {
        return (string) call_user_func_array(
            $this->callbackGetSource,
            array($media, $options)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getSourceType($media, array $options = array())
    {
        return (string) call_user_func_array(
            $this->callbackGetSourceType,
            array($media, $options)
        );
    }
}
