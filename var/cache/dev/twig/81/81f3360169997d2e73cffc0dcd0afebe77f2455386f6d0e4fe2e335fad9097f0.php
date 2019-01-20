<?php

/* site/generate_rankings_test.html.twig */
class __TwigTemplate_f395f20eeb9afa589b65c14988bf06f8706c62d6b9ab61c39fc47d3f6881028a extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 3
        $this->parent = $this->loadTemplate("common/layout.html.twig", "site/generate_rankings_test.html.twig", 3);
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
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "site/generate_rankings_test.html.twig"));

        // line 1
        $this->env->getRuntime("Symfony\\Component\\Form\\FormRenderer")->setTheme((isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new Twig_Error_Runtime('Variable "form" does not exist.', 1, $this->source); })()), array(0 => "bootstrap/bootstrap_4_layout.html.twig"), true);
        // line 3
        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    // line 5
    public function block_body($context, array $blocks = array())
    {
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "body"));

        // line 6
        echo "
    <h3>Generate Rankings</h3>

    <div class=\"form\">
      ";
        // line 10
        echo         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new Twig_Error_Runtime('Variable "form" does not exist.', 10, $this->source); })()), 'form');
        echo "
    </div>

    ";
        // line 13
        if ((twig_length_filter($this->env, (isset($context["arrMatchs"]) || array_key_exists("arrMatchs", $context) ? $context["arrMatchs"] : (function () { throw new Twig_Error_Runtime('Variable "arrMatchs" does not exist.', 13, $this->source); })())) > 0)) {
            // line 14
            echo "
      <h3>";
            // line 15
            echo twig_escape_filter($this->env, twig_length_filter($this->env, (isset($context["arrMatchs"]) || array_key_exists("arrMatchs", $context) ? $context["arrMatchs"] : (function () { throw new Twig_Error_Runtime('Variable "arrMatchs" does not exist.', 15, $this->source); })())), "html", null, true);
            echo " match(s)</h3>
      <table class=\"table\">
      <tr>
        <th>#</th>
        <th>Player1 (winner)</th>
        <th>Player2 (loser)</th>
        <th>tie</th>
      </tr>

      ";
            // line 24
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["arrMatchs"]) || array_key_exists("arrMatchs", $context) ? $context["arrMatchs"] : (function () { throw new Twig_Error_Runtime('Variable "arrMatchs" does not exist.', 24, $this->source); })()));
            foreach ($context['_seq'] as $context["_key"] => $context["match"]) {
                // line 25
                echo "        <tr>
          <td>";
                // line 26
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["match"], "id", array()), "html", null, true);
                echo "</td>
          <td>";
                // line 27
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["match"], "idPlayer1", array()), "html", null, true);
                echo "</td>
          <td>";
                // line 28
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["match"], "idPlayer2", array()), "html", null, true);
                echo "</td>
          <td>";
                // line 29
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["match"], "tie", array()), "html", null, true);
                echo "</td>
        </tr>
      ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['match'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 32
            echo "
      </table>
    ";
        }
        // line 35
        echo "
    ";
        // line 36
        if ((twig_length_filter($this->env, (isset($context["arrRankFinal"]) || array_key_exists("arrRankFinal", $context) ? $context["arrRankFinal"] : (function () { throw new Twig_Error_Runtime('Variable "arrRankFinal" does not exist.', 36, $this->source); })())) > 0)) {
            // line 37
            echo "
      <h3>Rankings ";
            // line 38
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, (isset($context["dateFrom"]) || array_key_exists("dateFrom", $context) ? $context["dateFrom"] : (function () { throw new Twig_Error_Runtime('Variable "dateFrom" does not exist.', 38, $this->source); })()), "Y-m-d"), "html", null, true);
            echo "</h3>
      <table class=\"table\">
      <tr>
        <th>#</th>
        <th>Player</th>
        <th>Rating</th>
      </tr>

      ";
            // line 46
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["arrRankFinal"]) || array_key_exists("arrRankFinal", $context) ? $context["arrRankFinal"] : (function () { throw new Twig_Error_Runtime('Variable "arrRankFinal" does not exist.', 46, $this->source); })()));
            foreach ($context['_seq'] as $context["_key"] => $context["rank"]) {
                // line 47
                echo "        <tr>
          <td>";
                // line 48
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["rank"], "rank", array()), "html", null, true);
                echo "</td>
          <td>";
                // line 49
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["rank"], "name", array()), "html", null, true);
                echo " (";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["rank"], "id", array()), "html", null, true);
                echo ")</td>
          <td>";
                // line 50
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["rank"], "rating", array()), "html", null, true);
                echo "</td>
        </tr>
      ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['rank'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 53
            echo "
      </table>
    ";
        }
        // line 56
        echo "  ";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    public function getTemplateName()
    {
        return "site/generate_rankings_test.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  159 => 56,  154 => 53,  145 => 50,  139 => 49,  135 => 48,  132 => 47,  128 => 46,  117 => 38,  114 => 37,  112 => 36,  109 => 35,  104 => 32,  95 => 29,  91 => 28,  87 => 27,  83 => 26,  80 => 25,  76 => 24,  64 => 15,  61 => 14,  59 => 13,  53 => 10,  47 => 6,  41 => 5,  34 => 3,  32 => 1,  15 => 3,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% form_theme form 'bootstrap/bootstrap_4_layout.html.twig' %}

  {% extends \"common/layout.html.twig\" %}

  {% block body %}

    <h3>Generate Rankings</h3>

    <div class=\"form\">
      {{ form(form) }}
    </div>

    {% if arrMatchs|length >0 %}

      <h3>{{ arrMatchs|length }} match(s)</h3>
      <table class=\"table\">
      <tr>
        <th>#</th>
        <th>Player1 (winner)</th>
        <th>Player2 (loser)</th>
        <th>tie</th>
      </tr>

      {% for match in arrMatchs %}
        <tr>
          <td>{{ match.id }}</td>
          <td>{{ match.idPlayer1 }}</td>
          <td>{{ match.idPlayer2 }}</td>
          <td>{{ match.tie }}</td>
        </tr>
      {% endfor %}

      </table>
    {% endif %}

    {% if arrRankFinal|length >0 %}

      <h3>Rankings {{ dateFrom|date(\"Y-m-d\") }}</h3>
      <table class=\"table\">
      <tr>
        <th>#</th>
        <th>Player</th>
        <th>Rating</th>
      </tr>

      {% for rank in arrRankFinal %}
        <tr>
          <td>{{ rank.rank }}</td>
          <td>{{ rank.name }} ({{ rank.id }})</td>
          <td>{{ rank.rating }}</td>
        </tr>
      {% endfor %}

      </table>
    {% endif %}
  {% endblock %}

", "site/generate_rankings_test.html.twig", "/Users/imac-mb/Documents/tennis-luckyloser/ATL-Stege/atl-stege/templates/site/generate_rankings_test.html.twig");
    }
}
