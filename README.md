Media Exposer
=============

The __Media Exposer__ is a PHP (5.3+) library that helps you expose your
media files to the clients of your web application.

Principle
---------

When you develop a web application, you always need to set up a strategy
to serve your medias to the clients. This is the problem this library tries
to help you solve.

The __Exposer__ is the main entry point of the library. It is responsible
for returning the right source or the right path for the given media and
options. To do that, it iterates over the registered resolvers to find the
first one supporting the given media and options. The source or path generation
is delegated to it. So the first thing to do when you want to set up an
exposer is to register resolvers. When you register a resolver, you give
it a priority.

All resolvers implement the _Resolver_ interface. They indicate if they
support a given media and options. But this only interface is not sufficient
so a proper resolver must also implement either the _SourceResolver_ interface
or the _PathResolver_ (or both).

Quick Exemple
-------------

Enough discussion, let's try it.

As an exemple, will take the following simple image model:

```php
<?php

class FooImage
{
    private $filename;

    public function getFilename()
    {
        return $this->filename;
    }
}
```

Then, we create an associated resolver:

```php
<?php

use MediaExposer\PathResolver,
    MediaExposer\SourceResolver;

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
        return SourceResolver::TYPE_RELATIVE; // or SourceResolver::TYPE_ABSOLUTE
    }
}
```

Finally, you can register it in your exposer:

```php
<?php

use MediaExposer\Exposer;

$exposer = new Exposer('http://the-host');
$exposer->addResolver(new FooImageResolver(), 10);
```

The first `Exposer`'s argument is called `$baseUrl`. It's only required
if you want to generate absolute URLs with relative `SourceResolver` instances.
If so, it will be prepended to the relative source returned by the resolver
to turn it absolute.
