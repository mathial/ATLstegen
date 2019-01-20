<?php

/* common/layout.html.twig */
class __TwigTemplate_4126884f64f9cb6562780c93e04444c6c4e874e30f69ad459d7457bd0e3618aa extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'stylesheets' => array($this, 'block_stylesheets'),
            'body' => array($this, 'block_body'),
            'javascripts' => array($this, 'block_javascripts'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "common/layout.html.twig"));

        // line 1
        echo "<!DOCTYPE html>
<html>
<head>
  <meta charset=\"utf-8\">
  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">

  <title>";
        // line 7
        $this->displayBlock('title', $context, $blocks);
        echo "</title>

  ";
        // line 9
        $this->displayBlock('stylesheets', $context, $blocks);
        // line 13
        echo "</head>

<body>
  <div class=\"container\">
    <div id=\"header\" class=\"jumbotron\">
      <h1>ATL Stegen</h1>
    </div>

    <div class=\"row\">
      <div id=\"menu\" class=\"col-md-3\">

        ";
        // line 24
        if (twig_get_attribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new Twig_Error_Runtime('Variable "app" does not exist.', 24, $this->source); })()), "user", array())) {
            // line 25
            echo "          <a href=\"";
            echo $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("logout");
            echo "\">Logout ";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new Twig_Error_Runtime('Variable "app" does not exist.', 25, $this->source); })()), "user", array()), "username", array()), "html", null, true);
            echo "</a>
        ";
        }
        // line 27
        echo "
        <h3>Menu</h3>
        

        
        <ul class=\"nav nav-pills nav-stacked\">
          ";
        // line 34
        echo "          <li><a href=\"";
        echo $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("rankings_view");
        echo "\">RANKINGS - view</a></li>
          <li><a href=\"";
        // line 35
        echo $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("simulator");
        echo "\">POINTS SIMULATOR</a></li>
          <li><a href=\"";
        // line 36
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("matchs_list", array("maxpage" => 50, "page" => 1)), "html", null, true);
        echo "\">MATCHS - list</a></li>
          <li><a href=\"";
        // line 37
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("players_list", array("maxpage" => 50, "page" => 1)), "html", null, true);
        echo "\">PLAYERS - list</a></li>
        </ul>


        ";
        // line 41
        if ($this->extensions['Symfony\Bridge\Twig\Extension\SecurityExtension']->isGranted("ROLE_USER")) {
            echo " 
          <ul class=\"nav nav-pills nav-stacked\">
            <li><a href=\"";
            // line 43
            echo $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("matchs_new");
            echo "\">MATCHS - new</a></li>
          </ul>
        ";
        }
        // line 46
        echo "
        ";
        // line 47
        if ($this->extensions['Symfony\Bridge\Twig\Extension\SecurityExtension']->isGranted("ROLE_ADMIN")) {
            echo " 
          <ul class=\"nav nav-pills nav-stacked\">
            <li><a href=\"";
            // line 49
            echo $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("rankings_generate_test");
            echo "\">RANKINGS - generate</a></li>
            <li><a href=\"";
            // line 50
            echo $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("player_new");
            echo "\">PLAYERS - new</a></li>
          </ul>
        ";
        }
        // line 53
        echo "        </ul>

      </div>

      <div>
        ";
        // line 58
        if ((twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new Twig_Error_Runtime('Variable "app" does not exist.', 58, $this->source); })()), "session", array()), "flashbag", array()), "peek", array(0 => "success"), "method")) > 0)) {
            echo " 
          <div class=\"alert alert-success\">
            ";
            // line 60
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new Twig_Error_Runtime('Variable "app" does not exist.', 60, $this->source); })()), "session", array()), "flashbag", array()), "get", array(0 => "success"), "method"));
            foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
                // line 61
                echo "              <p>Message flash Info : ";
                echo twig_escape_filter($this->env, $context["message"], "html", null, true);
                echo "</p>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['message'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 63
            echo "          </div>
        ";
        }
        // line 65
        echo "
        ";
        // line 66
        if ((twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new Twig_Error_Runtime('Variable "app" does not exist.', 66, $this->source); })()), "session", array()), "flashbag", array()), "peek", array(0 => "info"), "method")) > 0)) {
            echo " 
          <div class=\"alert alert-info\">
            ";
            // line 68
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new Twig_Error_Runtime('Variable "app" does not exist.', 68, $this->source); })()), "session", array()), "flashbag", array()), "get", array(0 => "info"), "method"));
            foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
                // line 69
                echo "              <p>Message flash Notice : ";
                echo twig_escape_filter($this->env, $context["message"], "html", null, true);
                echo "</p>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['message'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 71
            echo "          </div>
        ";
        }
        // line 73
        echo "
        ";
        // line 74
        if ((twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new Twig_Error_Runtime('Variable "app" does not exist.', 74, $this->source); })()), "session", array()), "flashbag", array()), "peek", array(0 => "error"), "method")) > 0)) {
            echo " 
          <div class=\"alert alert-danger\">
            ";
            // line 76
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new Twig_Error_Runtime('Variable "app" does not exist.', 76, $this->source); })()), "session", array()), "flashbag", array()), "get", array(0 => "error"), "method"));
            foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
                // line 77
                echo "              <p>Message flash Error : ";
                echo twig_escape_filter($this->env, $context["message"], "html", null, true);
                echo "</p>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['message'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 79
            echo "          </div>
        ";
        }
        // line 81
        echo "      </div>

      <div id=\"content\" class=\"col-md-9\">
        ";
        // line 84
        $this->displayBlock('body', $context, $blocks);
        // line 86
        echo "      </div>
    </div>

    <hr>

    <footer>
      <p>Bigfooter.</p>
    </footer>
  </div>

  ";
        // line 96
        $this->displayBlock('javascripts', $context, $blocks);
        // line 100
        echo "
</body>
</html>";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    // line 7
    public function block_title($context, array $blocks = array())
    {
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "title"));

        echo "ATL Stegen";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    // line 9
    public function block_stylesheets($context, array $blocks = array())
    {
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "stylesheets"));

        // line 10
        echo "    <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css\">
    <link rel=\"stylesheet\" href=\"";
        // line 11
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("/css/styles.css"), "html", null, true);
        echo "\">
  ";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    // line 84
    public function block_body($context, array $blocks = array())
    {
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "body"));

        // line 85
        echo "        ";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    // line 96
    public function block_javascripts($context, array $blocks = array())
    {
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "javascripts"));

        // line 97
        echo "    <script src=\"//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js\"></script>
    <script src=\"//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js\"></script>
  ";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    public function getTemplateName()
    {
        return "common/layout.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  283 => 97,  277 => 96,  270 => 85,  264 => 84,  255 => 11,  252 => 10,  246 => 9,  234 => 7,  225 => 100,  223 => 96,  211 => 86,  209 => 84,  204 => 81,  200 => 79,  191 => 77,  187 => 76,  182 => 74,  179 => 73,  175 => 71,  166 => 69,  162 => 68,  157 => 66,  154 => 65,  150 => 63,  141 => 61,  137 => 60,  132 => 58,  125 => 53,  119 => 50,  115 => 49,  110 => 47,  107 => 46,  101 => 43,  96 => 41,  89 => 37,  85 => 36,  81 => 35,  76 => 34,  68 => 27,  60 => 25,  58 => 24,  45 => 13,  43 => 9,  38 => 7,  30 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("<!DOCTYPE html>
<html>
<head>
  <meta charset=\"utf-8\">
  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">

  <title>{% block title %}ATL Stegen{% endblock %}</title>

  {% block stylesheets %}
    <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css\">
    <link rel=\"stylesheet\" href=\"{{ asset('/css/styles.css') }}\">
  {% endblock %}
</head>

<body>
  <div class=\"container\">
    <div id=\"header\" class=\"jumbotron\">
      <h1>ATL Stegen</h1>
    </div>

    <div class=\"row\">
      <div id=\"menu\" class=\"col-md-3\">

        {% if app.user %}
          <a href=\"{{ path('logout') }}\">Logout {{ app.user.username }}</a>
        {% endif %}

        <h3>Menu</h3>
        

        
        <ul class=\"nav nav-pills nav-stacked\">
          {# <li><a href=\"{{ path('homepage') }}\">Home</a></li> #}
          <li><a href=\"{{ path('rankings_view') }}\">RANKINGS - view</a></li>
          <li><a href=\"{{ path('simulator') }}\">POINTS SIMULATOR</a></li>
          <li><a href=\"{{ path('matchs_list', {'maxpage':50, 'page':1}) }}\">MATCHS - list</a></li>
          <li><a href=\"{{ path('players_list', {'maxpage':50, 'page':1}) }}\">PLAYERS - list</a></li>
        </ul>


        {% if is_granted('ROLE_USER') %} 
          <ul class=\"nav nav-pills nav-stacked\">
            <li><a href=\"{{ path('matchs_new') }}\">MATCHS - new</a></li>
          </ul>
        {% endif %}

        {% if is_granted('ROLE_ADMIN') %} 
          <ul class=\"nav nav-pills nav-stacked\">
            <li><a href=\"{{ path('rankings_generate_test') }}\">RANKINGS - generate</a></li>
            <li><a href=\"{{ path('player_new') }}\">PLAYERS - new</a></li>
          </ul>
        {% endif %}
        </ul>

      </div>

      <div>
        {% if app.session.flashbag.peek('success') | length > 0 %} 
          <div class=\"alert alert-success\">
            {% for message in app.session.flashbag.get('success') %}
              <p>Message flash Info : {{ message }}</p>
            {% endfor %}
          </div>
        {% endif %}

        {% if app.session.flashbag.peek('info') | length > 0 %} 
          <div class=\"alert alert-info\">
            {% for message in app.session.flashbag.get('info') %}
              <p>Message flash Notice : {{ message }}</p>
            {% endfor %}
          </div>
        {% endif %}

        {% if app.session.flashbag.peek('error') | length > 0 %} 
          <div class=\"alert alert-danger\">
            {% for message in app.session.flashbag.get('error') %}
              <p>Message flash Error : {{ message }}</p>
            {% endfor %}
          </div>
        {% endif %}
      </div>

      <div id=\"content\" class=\"col-md-9\">
        {% block body %}
        {% endblock %}
      </div>
    </div>

    <hr>

    <footer>
      <p>Bigfooter.</p>
    </footer>
  </div>

  {% block javascripts %}
    <script src=\"//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js\"></script>
    <script src=\"//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js\"></script>
  {% endblock %}

</body>
</html>", "common/layout.html.twig", "/Users/imac-mb/Documents/tennis-luckyloser/ATL-Stege/atl-stege/templates/common/layout.html.twig");
    }
}
