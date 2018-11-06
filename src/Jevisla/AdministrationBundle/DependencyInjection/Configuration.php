<?php
/**
 * This file is part of the Symfony 3.4.15 -coding-standard (phpcs standard).
 *
 * PHP version 7.1.9
 *
 * @category PHP
 *
 * @author   Patrick Maina <demosthene33@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @see     https://github.com/djoos/Symfony2-coding-standard
 */

namespace Jevisla\AdministrationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and
 * Merges configuration from your app/config files.
 *
 * PHP version 7.1.9
 *
 * @category PHP
 *
 * @author   Patrick Maina <demosthene33@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @see     http://symfony.com/doc/current/cookbook/bundles/configuration.html
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     *
     * @return Objet $treeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('jevisla_administration');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
