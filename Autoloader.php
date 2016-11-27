<?php
namespace AgileStory\PodcastPublisher;

class Autoloader
{
    public static function load($className)
    {
        if (strlen($className) > 0 && strpos($className, __NAMESPACE__) !== false) {
            $trimmedClassName = substr($className, strlen(__NAMESPACE__) + 1);
            if (strlen($trimmedClassName) > 0) {
                require $trimmedClassName . '.php';
            }
        }
    }
}
