<?php

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Psr\Log\LoggerInterface;

/**
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class srcDevDebugProjectContainerUrlGenerator extends Symfony\Component\Routing\Generator\UrlGenerator
{
    private static $declaredRoutes;
    private $defaultLocale;

    public function __construct(RequestContext $context, LoggerInterface $logger = null, string $defaultLocale = null)
    {
        $this->context = $context;
        $this->logger = $logger;
        $this->defaultLocale = $defaultLocale;
        if (null === self::$declaredRoutes) {
            self::$declaredRoutes = array(
        'matchs' => array(array(), array('_controller' => 'App\\Controller\\MatchsController::index'), array(), array(array('text', '/matchs')), array(), array()),
        'matchs_list' => array(array('maxpage', 'page'), array('_controller' => 'App\\Controller\\MatchsController::list'), array('page' => '\\d+'), array(array('variable', '/', '\\d+', 'page'), array('variable', '/', '[^/]++', 'maxpage'), array('text', '/matchs/list')), array(), array()),
        'matchs_new' => array(array(), array('_controller' => 'App\\Controller\\MatchsController::new'), array(), array(array('text', '/matchs/new')), array(), array()),
        'player_view' => array(array('id'), array('_controller' => 'App\\Controller\\PlayerController::view'), array('id' => '\\d+'), array(array('variable', '/', '\\d+', 'id'), array('text', '/players/view')), array(), array()),
        'players_list' => array(array('maxpage', 'page'), array('_controller' => 'App\\Controller\\PlayerController::list'), array('page' => '\\d+'), array(array('variable', '/', '\\d+', 'page'), array('variable', '/', '[^/]++', 'maxpage'), array('text', '/players/list')), array(), array()),
        'player_new' => array(array(), array('_controller' => 'App\\Controller\\PlayerController::new'), array(), array(array('text', '/players/new')), array(), array()),
        'player_update' => array(array('id'), array('_controller' => 'App\\Controller\\PlayerController::updatePlayerAction'), array('id' => '\\d+'), array(array('variable', '/', '\\d+', 'id'), array('text', '/players/update')), array(), array()),
        'rankings' => array(array(), array('_controller' => 'App\\Controller\\RankingsController::index'), array(), array(array('text', '/rankings')), array(), array()),
        'rankings_generate_test' => array(array(), array('_controller' => 'App\\Controller\\RankingsController::generateTest'), array(), array(array('text', '/rankings/generate')), array(), array()),
        'rankings_view' => array(array(), array('_controller' => 'App\\Controller\\RankingsController::viewRanking'), array(), array(array('text', '/rankings/view')), array(), array()),
        'rankings_view_id' => array(array('id'), array('id' => null, '_controller' => 'App\\Controller\\RankingsController::viewRanking'), array(), array(array('variable', '/', '[^/]++', 'id'), array('text', '/rankings/view')), array(), array()),
        'simulator' => array(array(), array('_controller' => 'App\\Controller\\RankingsController::simulator'), array(), array(array('text', '/simulator')), array(), array()),
        'site' => array(array(), array('_controller' => 'App\\Controller\\SiteController::index'), array(), array(array('text', '/')), array(), array()),
        '_twig_error_test' => array(array('code', '_format'), array('_controller' => 'twig.controller.preview_error::previewErrorPageAction', '_format' => 'html'), array('code' => '\\d+'), array(array('variable', '.', '[^/]++', '_format'), array('variable', '/', '\\d+', 'code'), array('text', '/_error')), array(), array()),
    );
        }
    }

    public function generate($name, $parameters = array(), $referenceType = self::ABSOLUTE_PATH)
    {
        $locale = $parameters['_locale']
            ?? $this->context->getParameter('_locale')
            ?: $this->defaultLocale;

        if (null !== $locale && (self::$declaredRoutes[$name.'.'.$locale][1]['_canonical_route'] ?? null) === $name && null !== $name) {
            unset($parameters['_locale']);
            $name .= '.'.$locale;
        } elseif (!isset(self::$declaredRoutes[$name])) {
            throw new RouteNotFoundException(sprintf('Unable to generate a URL for the named route "%s" as such route does not exist.', $name));
        }

        list($variables, $defaults, $requirements, $tokens, $hostTokens, $requiredSchemes) = self::$declaredRoutes[$name];

        return $this->doGenerate($variables, $defaults, $requirements, $tokens, $parameters, $name, $referenceType, $hostTokens, $requiredSchemes);
    }
}
