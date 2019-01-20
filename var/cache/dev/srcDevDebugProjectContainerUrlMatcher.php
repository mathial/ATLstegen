<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class srcDevDebugProjectContainerUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($pathinfo)
    {
        $allow = $allowSchemes = array();
        if ($ret = $this->doMatch($pathinfo, $allow, $allowSchemes)) {
            return $ret;
        }
        if ($allow) {
            throw new MethodNotAllowedException(array_keys($allow));
        }
        if (!in_array($this->context->getMethod(), array('HEAD', 'GET'), true)) {
            // no-op
        } elseif ($allowSchemes) {
            redirect_scheme:
            $scheme = $this->context->getScheme();
            $this->context->setScheme(key($allowSchemes));
            try {
                if ($ret = $this->doMatch($pathinfo)) {
                    return $this->redirect($pathinfo, $ret['_route'], $this->context->getScheme()) + $ret;
                }
            } finally {
                $this->context->setScheme($scheme);
            }
        } elseif ('/' !== $pathinfo) {
            $pathinfo = '/' !== $pathinfo[-1] ? $pathinfo.'/' : substr($pathinfo, 0, -1);
            if ($ret = $this->doMatch($pathinfo, $allow, $allowSchemes)) {
                return $this->redirect($pathinfo, $ret['_route']) + $ret;
            }
            if ($allowSchemes) {
                goto redirect_scheme;
            }
        }

        throw new ResourceNotFoundException();
    }

    private function doMatch(string $rawPathinfo, array &$allow = array(), array &$allowSchemes = array()): ?array
    {
        $allow = $allowSchemes = array();
        $pathinfo = rawurldecode($rawPathinfo) ?: '/';
        $context = $this->context;
        $requestMethod = $canonicalMethod = $context->getMethod();

        if ('HEAD' === $requestMethod) {
            $canonicalMethod = 'GET';
        }

        switch ($trimmedPathinfo = '/' !== $pathinfo && '/' === $pathinfo[-1] ? substr($pathinfo, 0, -1) : $pathinfo) {
            default:
                $routes = array(
                    '/matchs' => array(array('_route' => 'matchs', '_controller' => 'App\\Controller\\MatchsController::index'), null, null, null, false),
                    '/matchs/new' => array(array('_route' => 'matchs_new', '_controller' => 'App\\Controller\\MatchsController::new'), null, null, null, false),
                    '/players/new' => array(array('_route' => 'player_new', '_controller' => 'App\\Controller\\PlayerController::new'), null, null, null, false),
                    '/rankings' => array(array('_route' => 'rankings', '_controller' => 'App\\Controller\\RankingsController::index'), null, null, null, false),
                    '/rankings/generate' => array(array('_route' => 'rankings_generate_test', '_controller' => 'App\\Controller\\RankingsController::generateTest'), null, null, null, false),
                    '/rankings/view' => array(array('_route' => 'rankings_view', '_controller' => 'App\\Controller\\RankingsController::viewRanking'), null, null, null, false),
                    '/simulator' => array(array('_route' => 'simulator', '_controller' => 'App\\Controller\\RankingsController::simulator'), null, null, null, false),
                    '/' => array(array('_route' => 'site', '_controller' => 'App\\Controller\\SiteController::index'), null, null, null, false),
                );

                if (!isset($routes[$trimmedPathinfo])) {
                    break;
                }
                list($ret, $requiredHost, $requiredMethods, $requiredSchemes, $hasTrailingSlash) = $routes[$trimmedPathinfo];

                if ('/' !== $pathinfo && $hasTrailingSlash !== ('/' === $pathinfo[-1])) {
                    return null;
                }

                $hasRequiredScheme = !$requiredSchemes || isset($requiredSchemes[$context->getScheme()]);
                if ($requiredMethods && !isset($requiredMethods[$canonicalMethod]) && !isset($requiredMethods[$requestMethod])) {
                    if ($hasRequiredScheme) {
                        $allow += $requiredMethods;
                    }
                    break;
                }
                if (!$hasRequiredScheme) {
                    $allowSchemes += $requiredSchemes;
                    break;
                }

                return $ret;
        }

        $matchedPathinfo = $pathinfo;
        $regexList = array(
            0 => '{^(?'
                    .'|/matchs/list/([^/]++)/(\\d+)(*:34)'
                    .'|/players/(?'
                        .'|view/(\\d+)(*:63)'
                        .'|list/([^/]++)/(\\d+)(*:89)'
                        .'|update/(\\d+)(*:108)'
                    .')'
                    .'|/rankings/view(?:/([^/]++))?(*:145)'
                    .'|/_error/(\\d+)(?:\\.([^/]++))?(*:181)'
                .')(?:/?)$}sD',
        );

        foreach ($regexList as $offset => $regex) {
            while (preg_match($regex, $matchedPathinfo, $matches)) {
                switch ($m = (int) $matches['MARK']) {
                    default:
                        $routes = array(
                            34 => array(array('_route' => 'matchs_list', '_controller' => 'App\\Controller\\MatchsController::list'), array('maxpage', 'page'), null, null, false),
                            63 => array(array('_route' => 'player_view', '_controller' => 'App\\Controller\\PlayerController::view'), array('id'), null, null, false),
                            89 => array(array('_route' => 'players_list', '_controller' => 'App\\Controller\\PlayerController::list'), array('maxpage', 'page'), null, null, false),
                            108 => array(array('_route' => 'player_update', '_controller' => 'App\\Controller\\PlayerController::updatePlayerAction'), array('id'), null, null, false),
                            145 => array(array('_route' => 'rankings_view_id', 'id' => null, '_controller' => 'App\\Controller\\RankingsController::viewRanking'), array('id'), null, null, false),
                            181 => array(array('_route' => '_twig_error_test', '_controller' => 'twig.controller.preview_error::previewErrorPageAction', '_format' => 'html'), array('code', '_format'), null, null, false),
                        );

                        list($ret, $vars, $requiredMethods, $requiredSchemes, $hasTrailingSlash) = $routes[$m];

                        if ('/' !== $pathinfo && $hasTrailingSlash !== ('/' === $pathinfo[-1])) {
                            return null;
                        }

                        foreach ($vars as $i => $v) {
                            if (isset($matches[1 + $i])) {
                                $ret[$v] = $matches[1 + $i];
                            }
                        }

                        $hasRequiredScheme = !$requiredSchemes || isset($requiredSchemes[$context->getScheme()]);
                        if ($requiredMethods && !isset($requiredMethods[$canonicalMethod]) && !isset($requiredMethods[$requestMethod])) {
                            if ($hasRequiredScheme) {
                                $allow += $requiredMethods;
                            }
                            break;
                        }
                        if (!$hasRequiredScheme) {
                            $allowSchemes += $requiredSchemes;
                            break;
                        }

                        return $ret;
                }

                if (181 === $m) {
                    break;
                }
                $regex = substr_replace($regex, 'F', $m - $offset, 1 + strlen($m));
                $offset += strlen($m);
            }
        }
        if ('/' === $pathinfo && !$allow && !$allowSchemes) {
            throw new Symfony\Component\Routing\Exception\NoConfigurationException();
        }

        return null;
    }
}
