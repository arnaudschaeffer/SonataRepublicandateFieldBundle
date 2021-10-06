# Sonata Republicandate Field Bundle

Sonata Republicandate Field Bundle allow you to define text field which will store date in french republican format  


## Installation

Install the package with:

```console
composer require aschaeffer/sonata-republicandate-field-bundle
```

If you're *not* using Symfony Flex, you'll also need to enable the `Aschaeffer\SonataRepublicandateFieldBundle\AschaefferSonataRepublicandateFieldBundle` in your `AppKernel.php` file.

## Usage

In your entity, add `RepublicandateField` annotation on a string property that will store the string. Set which property of the entity stores the corresponding datetime 

```php
<?php

class User {

   /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected \DateTime $birthday;
       
    /**
     * @ORM\Column(type="string", nullable=true)
     * @RepublicandateField(gregorianDate="birthday")
     */
    protected string $birthdayRepublican;
}
```

By doing this, on a Sonata Admin view, if you choose a gregorian date that exists in the republican calendar, the republican date string will be persisted in the database. On the other hand, if no gregorian date is set AND a republican date is defined, then the datetime will be persisted in the gregorian field.