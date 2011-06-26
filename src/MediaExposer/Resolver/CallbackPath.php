<?php

namespace MediaExposer\Resolver;

use MediaExposer\PathResolver;

/**
 * Callback path resolver
 *
 * It uses the configured $callbackSupports callback for the "supports" method
 * and the configured $callbackGetPath for the "getPath" method. Both of the
 * callbacks will be calleds with two arguments: the model and its options.
 *
 * @package MediaExposer
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @author  Antoine Hérault <antoine.herault@gmail.com>
 */
class CallbackPath implements PathResolver
{
    /**
     * Constructor
     *
     * @param  callable $callbackSupports
     * @param  callable $callbackGetPath
     */
    public function __construct($callbackSupports, $callbackGetPath)
    {
        $this->callbackSupports = $callbackSupports;
        $this->callbackGetPath = $callbackGetPath;
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
    public function getPath($media, array $options = array())
    {
        return (string) call_user_func_array(
            $this->callbackGetPath,
            array($media, $options)
        );
    }
}
