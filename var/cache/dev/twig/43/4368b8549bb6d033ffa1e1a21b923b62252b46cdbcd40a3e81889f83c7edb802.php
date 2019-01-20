<?php

/* site/player_view.html.twig */
class __TwigTemplate_9ae599290cb93a97ba43ff9cea309df6ba742fd59ed35117c301136696cf18be extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 2
        $this->parent = $this->loadTemplate("common/layout.html.twig", "site/player_view.html.twig", 2);
        $this->blocks = array(
            'body' => array($this, 'block_body'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "common/layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "site/player_view.html.twig"));

        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    // line 4
    public function block_body($context, array $blocks = array())
    {
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "body"));

        // line 5
        echo "
  <h3>View player ";
        // line 6
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["player"]) || array_key_exists("player", $context) ? $context["player"] : (function () { throw new Twig_Error_Runtime('Variable "player" does not exist.', 6, $this->source); })()), "namelong", array()), "html", null, true);
        echo "</h3>

  <div>
  \t<label>Name</label>
  \t";
        // line 10
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["player"]) || array_key_exists("player", $context) ? $context["player"] : (function () { throw new Twig_Error_Runtime('Variable "player" does not exist.', 10, $this->source); })()), "namelong", array()), "html", null, true);
        echo "
  </div>

\t<div>
  \t<label>Name short</label>
  \t";
        // line 15
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["player"]) || array_key_exists("player", $context) ? $context["player"] : (function () { throw new Twig_Error_Runtime('Variable "player" does not exist.', 15, $this->source); })()), "nameshort", array()), "html", null, true);
        echo "
  </div>

\t<div>
  \t<label>Birthdate</label>
  \t";
        // line 20
        echo twig_escape_filter($this->env, sprintf(twig_get_attribute($this->env, $this->source, (isset($context["player"]) || array_key_exists("player", $context) ? $context["player"] : (function () { throw new Twig_Error_Runtime('Variable "player" does not exist.', 20, $this->source); })()), "birthdate", array()), "YYYY-mm-dd"), "html", null, true);
        echo "
  </div>

\t<div>
  \t<label>Level</label>
  \t";
        // line 25
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["player"]) || array_key_exists("player", $context) ? $context["player"] : (function () { throw new Twig_Error_Runtime('Variable "player" does not exist.', 25, $this->source); })()), "level", array()), "html", null, true);
        echo "
  </div>    

  ";
        // line 28
        if ((twig_length_filter($this->env, (isset($context["arrStatsOpponents"]) || array_key_exists("arrStatsOpponents", $context) ? $context["arrStatsOpponents"] : (function () { throw new Twig_Error_Runtime('Variable "arrStatsOpponents" does not exist.', 28, $this->source); })())) > 0)) {
            // line 29
            echo "
  \t<h3>Stats opponents - ";
            // line 30
            echo twig_escape_filter($this->env, (isset($context["nbMTot"]) || array_key_exists("nbMTot", $context) ? $context["nbMTot"] : (function () { throw new Twig_Error_Runtime('Variable "nbMTot" does not exist.', 30, $this->source); })()), "html", null, true);
            echo " match(s)</h3>
      <table class=\"table\">
      <tr>
        <th>Player</th>
        <th>Nb matches</th>
      </tr>

      ";
            // line 37
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["arrStatsOpponents"]) || array_key_exists("arrStatsOpponents", $context) ? $context["arrStatsOpponents"] : (function () { throw new Twig_Error_Runtime('Variable "arrStatsOpponents" does not exist.', 37, $this->source); })()));
            foreach ($context['_seq'] as $context["_key"] => $context["opp"]) {
                // line 38
                echo "        <tr>
          <td><a href=\"";
                // line 39
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("player_view", array("id" => twig_get_attribute($this->env, $this->source, $context["opp"], "id", array()))), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["opp"], "name", array()), "html", null, true);
                echo "</a></td>
          <td>";
                // line 40
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["opp"], "nbM", array()), "html", null, true);
                echo "</td>
        </tr>
      ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['opp'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 43
            echo "
  ";
        }
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    public function getTemplateName()
    {
        return "site/player_view.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  121 => 43,  112 => 40,  106 => 39,  103 => 38,  99 => 37,  89 => 30,  86 => 29,  84 => 28,  78 => 25,  70 => 20,  62 => 15,  54 => 10,  47 => 6,  44 => 5,  38 => 4,  15 => 2,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("
{% extends \"common/layout.html.twig\" %}

{% block body %}

  <h3>View player {{ player.namelong }}</h3>

  <div>
  \t<label>Name</label>
  \t{{ player.namelong }}
  </div>

\t<div>
  \t<label>Name short</label>
  \t{{ player.nameshort }}
  </div>

\t<div>
  \t<label>Birthdate</label>
  \t{{ player.birthdate|format(\"YYYY-mm-dd\") }}
  </div>

\t<div>
  \t<label>Level</label>
  \t{{ player.level }}
  </div>    

  {% if arrStatsOpponents|length >0 %}

  \t<h3>Stats opponents - {{ nbMTot }} match(s)</h3>
      <table class=\"table\">
      <tr>
        <th>Player</th>
        <th>Nb matches</th>
      </tr>

      {% for opp in arrStatsOpponents %}
        <tr>
          <td><a href=\"{{ path('player_view', {'id':opp.id}) }}\">{{ opp.name }}</a></td>
          <td>{{ opp.nbM }}</td>
        </tr>
      {% endfor %}

  {% endif %}
{% endblock %}", "site/player_view.html.twig", "/Users/imac-mb/Documents/tennis-luckyloser/ATL-Stege/atl-stege/templates/site/player_view.html.twig");
    }
}
