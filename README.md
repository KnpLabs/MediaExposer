Media Exposer
=============

The __Media Exposer__ is a PHP (5.3+) library that helps you expose your media files to the public of your web application.

By the way, if you think about a better name for the library, email me!

Principle
---------

When you develop a web application, you always need to set up a strategy to deserve your medias to the clients.
This is the problem this library try to help you solve.

The __Exposer__ is the main entry point of the library.
It is responsible of returning the right source or the right path for the given media and options.
To do that, it iterates over the registered resolvers to find the first one supporting the given media and options.
The source or path generation is delegated to it.
So the first thing to do when you want to set up an exposer is to register resolvers.
When you register a resolver, you associate it a priority.

The resolvers all implement the _Resolver_ interface which make them responsible of indicating whether they support a given media and options.
But this only interface is not sufficient so a proper resolver must either implement the _SourceResolver_ interface or the _PathResolver_ once (or both).

Quick Exemple
-------------

Enough discussion, let's try it.

As exemple, we will take the following simple image model:

    <?php

    class FooImage
    {
        private $filename;

        public function getFilename()
        {
            return $this->filename;
        }
    }

Then, we will create an associated resolver:

    <?php

    use Mediator\PathResolver;

    class FooImageResolver implements PathResolver, SourceResolver
    {
        /**
         * {@inheritDoc}
         */
        public function supports($media, array $options)
        {
            return $media instanceof FooImage;
        }

        /**
         * {@inheritDoc}
         */
        public function getPath($media, array $options)
        {
            return '/path/to/the/images/directory/' . $media->getFilename();
        }

        /**
         * {@inheritDoc}
         */
        public function getSource($media, array $options)
        {
            return '/media/foo/' . $media->getFilename();
        }

        /**
         * {@inheritDoc}
         */
        public function getSourceType($media, array $options)
        {
            return SourceResolver::TYPE_RELATIVE;
        }
    }

Finally, you can register it in your exposer:

    <?php

    use Mediator\Exposer;

    $exposer = new Exposer('http://the-host');
    $exposer->addResolver(new FooImageResolver(), 10);
