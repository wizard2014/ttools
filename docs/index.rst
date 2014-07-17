TTools documentation
====================

TTools (Twitter Tools) Library aims to make life easier for twitter app developers, providing a simple workflow for authentication, while maintaining a high-level of flexibility for various types of applications.

.. toctree::
   :maxdepth: 2

   getting_started
   basic_singleuser
   basic_multiuser
   making_requests
   example_silex
   example_symfony
   example_laravel
   app_creation
   twilex

Requirements
------------
TTools only requires the php5-curl extension.

Installing
----------

Installation can be easily made through `Composer <https://getcomposer.org/>`_. Add `ttools/ttools <https://packagist.org/packages/ttools/ttools>`_ to your composer.json::

    {
        "require": {
            "ttools/ttools": "2.1.*"
        }
    }


After running `composer install/update` you will be able to use TTools in your application.