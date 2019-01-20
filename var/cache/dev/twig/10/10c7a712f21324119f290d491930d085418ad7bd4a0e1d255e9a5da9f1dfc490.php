<?php

/* common/pager.html.twig */
class __TwigTemplate_36f0b58f8a09ca84ae90f2a75cc81ee647d21ed1ff154acc01ffbb95485e2cbe extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "common/pager.html.twig"));

        // line 1
        echo "<ul class=\"pagination\">

  ";
        // line 3
        $context["params"] = twig_split_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new Twig_Error_Runtime('Variable "app" does not exist.', 3, $this->source); })()), "request", array()), "uri", array()), "?");
        // line 5
        echo "  ";
        if (twig_get_attribute($this->env, $this->source, ($context["params"] ?? null), 1, array(), "array", true, true)) {
            // line 6
            echo "    ";
            $context["paramsUrl"] = ("?" . twig_get_attribute($this->env, $this->source, (isset($context["params"]) || array_key_exists("params", $context) ? $context["params"] : (function () { throw new Twig_Error_Runtime('Variable "params" does not exist.', 6, $this->source); })()), 1, array(), "array"));
            // line 8
            echo "  ";
        } else {
            // line 9
            echo "    ";
            $context["paramsUrl"] = "";
            // line 11
            echo "  ";
        }
        // line 12
        echo "
  <p>
    Number of elements per page 
    <a href=\"";
        // line 15
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath((isset($context["page_list"]) || array_key_exists("page_list", $context) ? $context["page_list"] : (function () { throw new Twig_Error_Runtime('Variable "page_list" does not exist.', 15, $this->source); })()), array("maxpage" => 10, "page" => 1)), "html", null, true);
        echo twig_escape_filter($this->env, (isset($context["paramsUrl"]) || array_key_exists("paramsUrl", $context) ? $context["paramsUrl"] : (function () { throw new Twig_Error_Runtime('Variable "paramsUrl" does not exist.', 15, $this->source); })()), "html", null, true);
        echo "\" class=\"";
        if (((isset($context["maxpage"]) || array_key_exists("maxpage", $context) ? $context["maxpage"] : (function () { throw new Twig_Error_Runtime('Variable "maxpage" does not exist.', 15, $this->source); })()) == 10)) {
            echo "active";
        }
        echo "\">10</a> - 
    <a href=\"";
        // line 16
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath((isset($context["page_list"]) || array_key_exists("page_list", $context) ? $context["page_list"] : (function () { throw new Twig_Error_Runtime('Variable "page_list" does not exist.', 16, $this->source); })()), array("maxpage" => 50, "page" => 1)), "html", null, true);
        echo twig_escape_filter($this->env, (isset($context["paramsUrl"]) || array_key_exists("paramsUrl", $context) ? $context["paramsUrl"] : (function () { throw new Twig_Error_Runtime('Variable "paramsUrl" does not exist.', 16, $this->source); })()), "html", null, true);
        echo "\" class=\"";
        if (((isset($context["maxpage"]) || array_key_exists("maxpage", $context) ? $context["maxpage"] : (function () { throw new Twig_Error_Runtime('Variable "maxpage" does not exist.', 16, $this->source); })()) == 50)) {
            echo "active";
        }
        echo "\">50</a> - 
    <a href=\"";
        // line 17
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath((isset($context["page_list"]) || array_key_exists("page_list", $context) ? $context["page_list"] : (function () { throw new Twig_Error_Runtime('Variable "page_list" does not exist.', 17, $this->source); })()), array("maxpage" => 100, "page" => 1)), "html", null, true);
        echo twig_escape_filter($this->env, (isset($context["paramsUrl"]) || array_key_exists("paramsUrl", $context) ? $context["paramsUrl"] : (function () { throw new Twig_Error_Runtime('Variable "paramsUrl" does not exist.', 17, $this->source); })()), "html", null, true);
        echo "\" class=\"";
        if (((isset($context["maxpage"]) || array_key_exists("maxpage", $context) ? $context["maxpage"] : (function () { throw new Twig_Error_Runtime('Variable "maxpage" does not exist.', 17, $this->source); })()) == 100)) {
            echo "active";
        }
        echo "\">100</a> - 
    <a href=\"";
        // line 18
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath((isset($context["page_list"]) || array_key_exists("page_list", $context) ? $context["page_list"] : (function () { throw new Twig_Error_Runtime('Variable "page_list" does not exist.', 18, $this->source); })()), array("maxpage" => 500, "page" => 1)), "html", null, true);
        echo twig_escape_filter($this->env, (isset($context["paramsUrl"]) || array_key_exists("paramsUrl", $context) ? $context["paramsUrl"] : (function () { throw new Twig_Error_Runtime('Variable "paramsUrl" does not exist.', 18, $this->source); })()), "html", null, true);
        echo "\" class=\"";
        if (((isset($context["maxpage"]) || array_key_exists("maxpage", $context) ? $context["maxpage"] : (function () { throw new Twig_Error_Runtime('Variable "maxpage" does not exist.', 18, $this->source); })()) == 500)) {
            echo "active";
        }
        echo "\">500</a> - 
    <a href=\"";
        // line 19
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath((isset($context["page_list"]) || array_key_exists("page_list", $context) ? $context["page_list"] : (function () { throw new Twig_Error_Runtime('Variable "page_list" does not exist.', 19, $this->source); })()), array("maxpage" => 1000, "page" => 1)), "html", null, true);
        echo twig_escape_filter($this->env, (isset($context["paramsUrl"]) || array_key_exists("paramsUrl", $context) ? $context["paramsUrl"] : (function () { throw new Twig_Error_Runtime('Variable "paramsUrl" does not exist.', 19, $this->source); })()), "html", null, true);
        echo "\" class=\"";
        if (((isset($context["maxpage"]) || array_key_exists("maxpage", $context) ? $context["maxpage"] : (function () { throw new Twig_Error_Runtime('Variable "maxpage" does not exist.', 19, $this->source); })()) == 1000)) {
            echo "active";
        }
        echo "\">1000</a> -
    <a href=\"";
        // line 20
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath((isset($context["page_list"]) || array_key_exists("page_list", $context) ? $context["page_list"] : (function () { throw new Twig_Error_Runtime('Variable "page_list" does not exist.', 20, $this->source); })()), array("maxpage" => "All", "page" => 1)), "html", null, true);
        echo twig_escape_filter($this->env, (isset($context["paramsUrl"]) || array_key_exists("paramsUrl", $context) ? $context["paramsUrl"] : (function () { throw new Twig_Error_Runtime('Variable "paramsUrl" does not exist.', 20, $this->source); })()), "html", null, true);
        echo "\" class=\"";
        if (((isset($context["maxpage"]) || array_key_exists("maxpage", $context) ? $context["maxpage"] : (function () { throw new Twig_Error_Runtime('Variable "maxpage" does not exist.', 20, $this->source); })()) == "All")) {
            echo "active";
        }
        echo "\">All</a> 
  </p>

  ";
        // line 24
        echo "  ";
        if (((isset($context["nbPages"]) || array_key_exists("nbPages", $context) ? $context["nbPages"] : (function () { throw new Twig_Error_Runtime('Variable "nbPages" does not exist.', 24, $this->source); })()) < 20)) {
            // line 25
            echo "    ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(range(1, (isset($context["nbPages"]) || array_key_exists("nbPages", $context) ? $context["nbPages"] : (function () { throw new Twig_Error_Runtime('Variable "nbPages" does not exist.', 25, $this->source); })())));
            foreach ($context['_seq'] as $context["_key"] => $context["p"]) {
                // line 26
                echo "      <li
      ";
                // line 27
                if (($context["p"] == (isset($context["page"]) || array_key_exists("page", $context) ? $context["page"] : (function () { throw new Twig_Error_Runtime('Variable "page" does not exist.', 27, $this->source); })()))) {
                    echo " class=\"active\"";
                }
                echo ">
        <a href=\"";
                // line 28
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath((isset($context["page_list"]) || array_key_exists("page_list", $context) ? $context["page_list"] : (function () { throw new Twig_Error_Runtime('Variable "page_list" does not exist.', 28, $this->source); })()), array("maxpage" => (isset($context["maxpage"]) || array_key_exists("maxpage", $context) ? $context["maxpage"] : (function () { throw new Twig_Error_Runtime('Variable "maxpage" does not exist.', 28, $this->source); })()), "page" => $context["p"])), "html", null, true);
                echo twig_escape_filter($this->env, (isset($context["paramsUrl"]) || array_key_exists("paramsUrl", $context) ? $context["paramsUrl"] : (function () { throw new Twig_Error_Runtime('Variable "paramsUrl" does not exist.', 28, $this->source); })()), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $context["p"], "html", null, true);
                echo "</a>
      </li>
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['p'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 31
            echo "  ";
        } else {
            // line 32
            echo "
    ";
            // line 33
            if (((isset($context["page"]) || array_key_exists("page", $context) ? $context["page"] : (function () { throw new Twig_Error_Runtime('Variable "page" does not exist.', 33, $this->source); })()) < 11)) {
                // line 34
                echo "      ";
                // line 35
                echo "      ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(1, 10));
                foreach ($context['_seq'] as $context["_key"] => $context["p"]) {
                    // line 36
                    echo "        <li
        ";
                    // line 37
                    if (($context["p"] == (isset($context["page"]) || array_key_exists("page", $context) ? $context["page"] : (function () { throw new Twig_Error_Runtime('Variable "page" does not exist.', 37, $this->source); })()))) {
                        echo " class=\"active\"";
                    }
                    echo ">
          <a href=\"";
                    // line 38
                    echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath((isset($context["page_list"]) || array_key_exists("page_list", $context) ? $context["page_list"] : (function () { throw new Twig_Error_Runtime('Variable "page_list" does not exist.', 38, $this->source); })()), array("maxpage" => (isset($context["maxpage"]) || array_key_exists("maxpage", $context) ? $context["maxpage"] : (function () { throw new Twig_Error_Runtime('Variable "maxpage" does not exist.', 38, $this->source); })()), "page" => $context["p"])), "html", null, true);
                    echo twig_escape_filter($this->env, (isset($context["paramsUrl"]) || array_key_exists("paramsUrl", $context) ? $context["paramsUrl"] : (function () { throw new Twig_Error_Runtime('Variable "paramsUrl" does not exist.', 38, $this->source); })()), "html", null, true);
                    echo "\">";
                    echo twig_escape_filter($this->env, $context["p"], "html", null, true);
                    echo "</a>
        </li>
      ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['p'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 41
                echo "
      <li><a>...</a></li>

      ";
                // line 45
                echo "      ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(((isset($context["nbPages"]) || array_key_exists("nbPages", $context) ? $context["nbPages"] : (function () { throw new Twig_Error_Runtime('Variable "nbPages" does not exist.', 45, $this->source); })()) - 2), (isset($context["nbPages"]) || array_key_exists("nbPages", $context) ? $context["nbPages"] : (function () { throw new Twig_Error_Runtime('Variable "nbPages" does not exist.', 45, $this->source); })())));
                foreach ($context['_seq'] as $context["_key"] => $context["p"]) {
                    // line 46
                    echo "        <li
        ";
                    // line 47
                    if (($context["p"] == (isset($context["page"]) || array_key_exists("page", $context) ? $context["page"] : (function () { throw new Twig_Error_Runtime('Variable "page" does not exist.', 47, $this->source); })()))) {
                        echo " class=\"active\"";
                    }
                    echo ">
          <a href=\"";
                    // line 48
                    echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath((isset($context["page_list"]) || array_key_exists("page_list", $context) ? $context["page_list"] : (function () { throw new Twig_Error_Runtime('Variable "page_list" does not exist.', 48, $this->source); })()), array("maxpage" => (isset($context["maxpage"]) || array_key_exists("maxpage", $context) ? $context["maxpage"] : (function () { throw new Twig_Error_Runtime('Variable "maxpage" does not exist.', 48, $this->source); })()), "page" => $context["p"])), "html", null, true);
                    echo twig_escape_filter($this->env, (isset($context["paramsUrl"]) || array_key_exists("paramsUrl", $context) ? $context["paramsUrl"] : (function () { throw new Twig_Error_Runtime('Variable "paramsUrl" does not exist.', 48, $this->source); })()), "html", null, true);
                    echo "\">";
                    echo twig_escape_filter($this->env, $context["p"], "html", null, true);
                    echo "</a>
        </li>
      ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['p'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 51
                echo "
    ";
            } elseif ((            // line 52
(isset($context["page"]) || array_key_exists("page", $context) ? $context["page"] : (function () { throw new Twig_Error_Runtime('Variable "page" does not exist.', 52, $this->source); })()) > ((isset($context["nbPages"]) || array_key_exists("nbPages", $context) ? $context["nbPages"] : (function () { throw new Twig_Error_Runtime('Variable "nbPages" does not exist.', 52, $this->source); })()) - 10))) {
                // line 53
                echo "
      ";
                // line 55
                echo "      ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(1, 3));
                foreach ($context['_seq'] as $context["_key"] => $context["p"]) {
                    // line 56
                    echo "        <li
        ";
                    // line 57
                    if (($context["p"] == (isset($context["page"]) || array_key_exists("page", $context) ? $context["page"] : (function () { throw new Twig_Error_Runtime('Variable "page" does not exist.', 57, $this->source); })()))) {
                        echo " class=\"active\"";
                    }
                    echo ">
          <a href=\"";
                    // line 58
                    echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath((isset($context["page_list"]) || array_key_exists("page_list", $context) ? $context["page_list"] : (function () { throw new Twig_Error_Runtime('Variable "page_list" does not exist.', 58, $this->source); })()), array("maxpage" => (isset($context["maxpage"]) || array_key_exists("maxpage", $context) ? $context["maxpage"] : (function () { throw new Twig_Error_Runtime('Variable "maxpage" does not exist.', 58, $this->source); })()), "page" => $context["p"])), "html", null, true);
                    echo twig_escape_filter($this->env, (isset($context["paramsUrl"]) || array_key_exists("paramsUrl", $context) ? $context["paramsUrl"] : (function () { throw new Twig_Error_Runtime('Variable "paramsUrl" does not exist.', 58, $this->source); })()), "html", null, true);
                    echo "\">";
                    echo twig_escape_filter($this->env, $context["p"], "html", null, true);
                    echo "</a>
        </li>
      ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['p'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 61
                echo "
      <li><a>...</a></li>

      ";
                // line 65
                echo "      ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(((isset($context["nbPages"]) || array_key_exists("nbPages", $context) ? $context["nbPages"] : (function () { throw new Twig_Error_Runtime('Variable "nbPages" does not exist.', 65, $this->source); })()) - 10), (isset($context["nbPages"]) || array_key_exists("nbPages", $context) ? $context["nbPages"] : (function () { throw new Twig_Error_Runtime('Variable "nbPages" does not exist.', 65, $this->source); })())));
                foreach ($context['_seq'] as $context["_key"] => $context["p"]) {
                    // line 66
                    echo "        <li
        ";
                    // line 67
                    if (($context["p"] == (isset($context["page"]) || array_key_exists("page", $context) ? $context["page"] : (function () { throw new Twig_Error_Runtime('Variable "page" does not exist.', 67, $this->source); })()))) {
                        echo " class=\"active\"";
                    }
                    echo ">
          <a href=\"";
                    // line 68
                    echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath((isset($context["page_list"]) || array_key_exists("page_list", $context) ? $context["page_list"] : (function () { throw new Twig_Error_Runtime('Variable "page_list" does not exist.', 68, $this->source); })()), array("maxpage" => (isset($context["maxpage"]) || array_key_exists("maxpage", $context) ? $context["maxpage"] : (function () { throw new Twig_Error_Runtime('Variable "maxpage" does not exist.', 68, $this->source); })()), "page" => $context["p"])), "html", null, true);
                    echo twig_escape_filter($this->env, (isset($context["paramsUrl"]) || array_key_exists("paramsUrl", $context) ? $context["paramsUrl"] : (function () { throw new Twig_Error_Runtime('Variable "paramsUrl" does not exist.', 68, $this->source); })()), "html", null, true);
                    echo "\">";
                    echo twig_escape_filter($this->env, $context["p"], "html", null, true);
                    echo "</a>
        </li>
      ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['p'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 71
                echo "
    ";
            } else {
                // line 73
                echo "

      ";
                // line 76
                echo "      ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(1, 3));
                foreach ($context['_seq'] as $context["_key"] => $context["p"]) {
                    // line 77
                    echo "        <li
        ";
                    // line 78
                    if (($context["p"] == (isset($context["page"]) || array_key_exists("page", $context) ? $context["page"] : (function () { throw new Twig_Error_Runtime('Variable "page" does not exist.', 78, $this->source); })()))) {
                        echo " class=\"active\"";
                    }
                    echo ">
          <a href=\"";
                    // line 79
                    echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath((isset($context["page_list"]) || array_key_exists("page_list", $context) ? $context["page_list"] : (function () { throw new Twig_Error_Runtime('Variable "page_list" does not exist.', 79, $this->source); })()), array("maxpage" => (isset($context["maxpage"]) || array_key_exists("maxpage", $context) ? $context["maxpage"] : (function () { throw new Twig_Error_Runtime('Variable "maxpage" does not exist.', 79, $this->source); })()), "page" => $context["p"])), "html", null, true);
                    echo twig_escape_filter($this->env, (isset($context["paramsUrl"]) || array_key_exists("paramsUrl", $context) ? $context["paramsUrl"] : (function () { throw new Twig_Error_Runtime('Variable "paramsUrl" does not exist.', 79, $this->source); })()), "html", null, true);
                    echo "\">";
                    echo twig_escape_filter($this->env, $context["p"], "html", null, true);
                    echo "</a>
        </li>
      ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['p'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 82
                echo "
      <li><a>...</a></li>

      ";
                // line 86
                echo "      ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(((isset($context["page"]) || array_key_exists("page", $context) ? $context["page"] : (function () { throw new Twig_Error_Runtime('Variable "page" does not exist.', 86, $this->source); })()) - 5), ((isset($context["page"]) || array_key_exists("page", $context) ? $context["page"] : (function () { throw new Twig_Error_Runtime('Variable "page" does not exist.', 86, $this->source); })()) + 5)));
                foreach ($context['_seq'] as $context["_key"] => $context["p"]) {
                    // line 87
                    echo "        <li
        ";
                    // line 88
                    if (($context["p"] == (isset($context["page"]) || array_key_exists("page", $context) ? $context["page"] : (function () { throw new Twig_Error_Runtime('Variable "page" does not exist.', 88, $this->source); })()))) {
                        echo " class=\"active\"";
                    }
                    echo ">
          <a href=\"";
                    // line 89
                    echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath((isset($context["page_list"]) || array_key_exists("page_list", $context) ? $context["page_list"] : (function () { throw new Twig_Error_Runtime('Variable "page_list" does not exist.', 89, $this->source); })()), array("maxpage" => (isset($context["maxpage"]) || array_key_exists("maxpage", $context) ? $context["maxpage"] : (function () { throw new Twig_Error_Runtime('Variable "maxpage" does not exist.', 89, $this->source); })()), "page" => $context["p"])), "html", null, true);
                    echo twig_escape_filter($this->env, (isset($context["paramsUrl"]) || array_key_exists("paramsUrl", $context) ? $context["paramsUrl"] : (function () { throw new Twig_Error_Runtime('Variable "paramsUrl" does not exist.', 89, $this->source); })()), "html", null, true);
                    echo "\">";
                    echo twig_escape_filter($this->env, $context["p"], "html", null, true);
                    echo "</a>
        </li>
      ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['p'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 92
                echo "
      <li><a>...</a></li>

      ";
                // line 96
                echo "      ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(((isset($context["nbPages"]) || array_key_exists("nbPages", $context) ? $context["nbPages"] : (function () { throw new Twig_Error_Runtime('Variable "nbPages" does not exist.', 96, $this->source); })()) - 2), (isset($context["nbPages"]) || array_key_exists("nbPages", $context) ? $context["nbPages"] : (function () { throw new Twig_Error_Runtime('Variable "nbPages" does not exist.', 96, $this->source); })())));
                foreach ($context['_seq'] as $context["_key"] => $context["p"]) {
                    // line 97
                    echo "        <li
        ";
                    // line 98
                    if (($context["p"] == (isset($context["page"]) || array_key_exists("page", $context) ? $context["page"] : (function () { throw new Twig_Error_Runtime('Variable "page" does not exist.', 98, $this->source); })()))) {
                        echo " class=\"active\"";
                    }
                    echo ">
          <a href=\"";
                    // line 99
                    echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath((isset($context["page_list"]) || array_key_exists("page_list", $context) ? $context["page_list"] : (function () { throw new Twig_Error_Runtime('Variable "page_list" does not exist.', 99, $this->source); })()), array("maxpage" => (isset($context["maxpage"]) || array_key_exists("maxpage", $context) ? $context["maxpage"] : (function () { throw new Twig_Error_Runtime('Variable "maxpage" does not exist.', 99, $this->source); })()), "page" => $context["p"])), "html", null, true);
                    echo twig_escape_filter($this->env, (isset($context["paramsUrl"]) || array_key_exists("paramsUrl", $context) ? $context["paramsUrl"] : (function () { throw new Twig_Error_Runtime('Variable "paramsUrl" does not exist.', 99, $this->source); })()), "html", null, true);
                    echo "\">";
                    echo twig_escape_filter($this->env, $context["p"], "html", null, true);
                    echo "</a>
        </li>
      ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['p'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 102
                echo "
    ";
            }
            // line 104
            echo "
  ";
        }
        // line 106
        echo "
</ul>";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    public function getTemplateName()
    {
        return "common/pager.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  373 => 106,  369 => 104,  365 => 102,  353 => 99,  347 => 98,  344 => 97,  339 => 96,  334 => 92,  322 => 89,  316 => 88,  313 => 87,  308 => 86,  303 => 82,  291 => 79,  285 => 78,  282 => 77,  277 => 76,  273 => 73,  269 => 71,  257 => 68,  251 => 67,  248 => 66,  243 => 65,  238 => 61,  226 => 58,  220 => 57,  217 => 56,  212 => 55,  209 => 53,  207 => 52,  204 => 51,  192 => 48,  186 => 47,  183 => 46,  178 => 45,  173 => 41,  161 => 38,  155 => 37,  152 => 36,  147 => 35,  145 => 34,  143 => 33,  140 => 32,  137 => 31,  125 => 28,  119 => 27,  116 => 26,  111 => 25,  108 => 24,  97 => 20,  88 => 19,  79 => 18,  70 => 17,  61 => 16,  52 => 15,  47 => 12,  44 => 11,  41 => 9,  38 => 8,  35 => 6,  32 => 5,  30 => 3,  26 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("<ul class=\"pagination\">

  {% set params = app.request.uri|split('?') 
  %}
  {% if params[1] is defined %}
    {% set paramsUrl = \"?\" ~ params[1] 
    %}
  {% else %}
    {% set paramsUrl = \"\" 
    %}
  {% endif %}

  <p>
    Number of elements per page 
    <a href=\"{{ path(page_list, {'maxpage': 10, 'page': 1}) }}{{ paramsUrl }}\" class=\"{% if maxpage == 10 %}active{% endif %}\">10</a> - 
    <a href=\"{{ path(page_list, {'maxpage': 50, 'page': 1}) }}{{ paramsUrl }}\" class=\"{% if maxpage == 50 %}active{% endif %}\">50</a> - 
    <a href=\"{{ path(page_list, {'maxpage': 100, 'page': 1}) }}{{ paramsUrl }}\" class=\"{% if maxpage == 100 %}active{% endif %}\">100</a> - 
    <a href=\"{{ path(page_list, {'maxpage': 500, 'page': 1}) }}{{ paramsUrl }}\" class=\"{% if maxpage == 500 %}active{% endif %}\">500</a> - 
    <a href=\"{{ path(page_list, {'maxpage': 1000, 'page': 1}) }}{{ paramsUrl }}\" class=\"{% if maxpage == 1000 %}active{% endif %}\">1000</a> -
    <a href=\"{{ path(page_list, {'maxpage': 'All', 'page': 1}) }}{{ paramsUrl }}\" class=\"{% if maxpage == \"All\" %}active{% endif %}\">All</a> 
  </p>

  {# On utilise la fonction range(a, b) qui crée un tableau de valeurs entre a et b #}
  {% if nbPages<20 %}
    {% for p in range(1, nbPages) %}
      <li
      {% if p == page %} class=\"active\"{% endif %}>
        <a href=\"{{ path(page_list, {'maxpage': maxpage, 'page': p}) }}{{ paramsUrl }}\">{{ p }}</a>
      </li>
    {% endfor %}
  {% else %}

    {% if page<11 %}
      {# first 10 #}
      {% for p in range(1, 10) %}
        <li
        {% if p == page %} class=\"active\"{% endif %}>
          <a href=\"{{ path(page_list, {'maxpage': maxpage, 'page': p}) }}{{ paramsUrl }}\">{{ p }}</a>
        </li>
      {% endfor %}

      <li><a>...</a></li>

      {# last 3 #}
      {% for p in range(nbPages-2, nbPages) %}
        <li
        {% if p == page %} class=\"active\"{% endif %}>
          <a href=\"{{ path(page_list, {'maxpage': maxpage, 'page': p}) }}{{ paramsUrl }}\">{{ p }}</a>
        </li>
      {% endfor %}

    {% elseif page>(nbPages-10) %}

      {# first 3 #}
      {% for p in range(1, 3) %}
        <li
        {% if p == page %} class=\"active\"{% endif %}>
          <a href=\"{{ path(page_list, {'maxpage': maxpage, 'page': p}) }}{{ paramsUrl }}\">{{ p }}</a>
        </li>
      {% endfor %}

      <li><a>...</a></li>

      {# last 10 #}
      {% for p in range(nbPages-10, nbPages) %}
        <li
        {% if p == page %} class=\"active\"{% endif %}>
          <a href=\"{{ path(page_list, {'maxpage': maxpage, 'page': p}) }}{{ paramsUrl }}\">{{ p }}</a>
        </li>
      {% endfor %}

    {% else %}


      {# first 3 #}
      {% for p in range(1, 3) %}
        <li
        {% if p == page %} class=\"active\"{% endif %}>
          <a href=\"{{ path(page_list, {'maxpage': maxpage, 'page': p}) }}{{ paramsUrl }}\">{{ p }}</a>
        </li>
      {% endfor %}

      <li><a>...</a></li>

      {# +/- 5 #}
      {% for p in range(page-5, page+5) %}
        <li
        {% if p == page %} class=\"active\"{% endif %}>
          <a href=\"{{ path(page_list, {'maxpage': maxpage, 'page': p}) }}{{ paramsUrl }}\">{{ p }}</a>
        </li>
      {% endfor %}

      <li><a>...</a></li>

      {# last 3 #}
      {% for p in range(nbPages-2, nbPages) %}
        <li
        {% if p == page %} class=\"active\"{% endif %}>
          <a href=\"{{ path(page_list, {'maxpage': maxpage, 'page': p}) }}{{ paramsUrl }}\">{{ p }}</a>
        </li>
      {% endfor %}

    {% endif %}

  {% endif %}

</ul>", "common/pager.html.twig", "/Users/imac-mb/Documents/tennis-luckyloser/ATL-Stege/atl-stege/templates/common/pager.html.twig");
    }
}
