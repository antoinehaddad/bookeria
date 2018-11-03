<?php
namespace App\Twig;

use Twig\Extension\RuntimeExtensionInterface;

class AppRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // this simple example doesn't define any dependency, but in your own
        // extensions, you'll need to inject services using this constructor
    }

    public function countryNameFilter($countryCode)
    {
       
        return \Symfony\Component\Intl\Intl::getRegionBundle()->getCountryName($countryCode);
    }
}