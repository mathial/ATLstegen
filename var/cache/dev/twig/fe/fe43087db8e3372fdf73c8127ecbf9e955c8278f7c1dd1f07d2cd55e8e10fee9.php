<?php

/* site/rankings_view.html.twig */
class __TwigTemplate_ab295c3d65947ca439d09b5231b4099830c7cf3a68b2adc8750a0afe38a29ec4 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 3
        $this->parent = $this->loadTemplate("common/layout.html.twig", "site/rankings_view.html.twig", 3);
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
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "site/rankings_view.html.twig"));

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
    <h3>View Rankings</h3>

    <div class=\"form\">
      ";
        // line 10
        echo         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new Twig_Error_Runtime('Variable "form" does not exist.', 10, $this->source); })()), 'form');
        echo "
    </div>

    
    ";
        // line 14
        if ((twig_length_filter($this->env, (isset($context["detailsRankings"]) || array_key_exists("detailsRankings", $context) ? $context["detailsRankings"] : (function () { throw new Twig_Error_Runtime('Variable "detailsRankings" does not exist.', 14, $this->source); })())) > 0)) {
            // line 15
            echo "
      <h3>Rankings ";
            // line 16
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["ranking"]) || array_key_exists("ranking", $context) ? $context["ranking"] : (function () { throw new Twig_Error_Runtime('Variable "ranking" does not exist.', 16, $this->source); })()), "date", array()), "Y-m-d"), "html", null, true);
            echo "</h3>
      <table class=\"table\">
      <tr>
        <th>#</th>
        <th>Player</th>
        <th>Rating</th>
        <th>Evol</th>
        <th>W</th>
        <th>D</th>
        <th>T</th>
      </tr>

      ";
            // line 28
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["detailsRankings"]) || array_key_exists("detailsRankings", $context) ? $context["detailsRankings"] : (function () { throw new Twig_Error_Runtime('Variable "detailsRankings" does not exist.', 28, $this->source); })()));
            foreach ($context['_seq'] as $context["_key"] => $context["det"]) {
                // line 29
                echo "
        ";
                // line 30
                if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["det"], "idplayer", array()), "active", array()) == 1)) {
                    // line 31
                    echo "        <tr>
        ";
                } else {
                    // line 33
                    echo "        <tr class=\"inactive-player\">
        ";
                }
                // line 35
                echo "          <td>";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["det"], "position", array()), "html", null, true);
                echo "</td>
          <td>";
                // line 36
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["det"], "idplayer", array()), "nameshort", array()), "html", null, true);
                echo "</td>
          <td>";
                // line 37
                echo twig_escape_filter($this->env, twig_number_format_filter($this->env, twig_get_attribute($this->env, $this->source, $context["det"], "score", array()), 0, ".", " "), "html", null, true);
                echo "</td>
          <td>";
                // line 38
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["detailsPlayer"]) || array_key_exists("detailsPlayer", $context) ? $context["detailsPlayer"] : (function () { throw new Twig_Error_Runtime('Variable "detailsPlayer" does not exist.', 38, $this->source); })()), twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["det"], "idplayer", array()), "id", array()), array(), "array"), "evol", array(), "array"), "html", null, true);
                echo "</td>
          <td>";
                // line 39
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["detailsPlayer"]) || array_key_exists("detailsPlayer", $context) ? $context["detailsPlayer"] : (function () { throw new Twig_Error_Runtime('Variable "detailsPlayer" does not exist.', 39, $this->source); })()), twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["det"], "idplayer", array()), "id", array()), array(), "array"), "wins", array(), "array"), "html", null, true);
                echo "</td>
          <td>";
                // line 40
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["detailsPlayer"]) || array_key_exists("detailsPlayer", $context) ? $context["detailsPlayer"] : (function () { throw new Twig_Error_Runtime('Variable "detailsPlayer" does not exist.', 40, $this->source); })()), twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["det"], "idplayer", array()), "id", array()), array(), "array"), "defeats", array(), "array"), "html", null, true);
                echo "</td>
          <td>";
                // line 41
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["detailsPlayer"]) || array_key_exists("detailsPlayer", $context) ? $context["detailsPlayer"] : (function () { throw new Twig_Error_Runtime('Variable "detailsPlayer" does not exist.', 41, $this->source); })()), twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["det"], "idplayer", array()), "id", array()), array(), "array"), "ties", array(), "array"), "html", null, true);
                echo "</td>
        </tr>
      ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['det'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 44
            echo "
      ";
            // line 45
            if ((twig_length_filter($this->env, (isset($context["arrTotal"]) || array_key_exists("arrTotal", $context) ? $context["arrTotal"] : (function () { throw new Twig_Error_Runtime('Variable "arrTotal" does not exist.', 45, $this->source); })())) > 0)) {
                // line 46
                echo "        <tr>
          <th></th>
          <th></th>
          <th>";
                // line 49
                echo twig_escape_filter($this->env, twig_number_format_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["arrTotal"]) || array_key_exists("arrTotal", $context) ? $context["arrTotal"] : (function () { throw new Twig_Error_Runtime('Variable "arrTotal" does not exist.', 49, $this->source); })()), "score", array(), "array"), 0, ".", " "), "html", null, true);
                echo "</th>
          <th>";
                // line 50
                echo twig_escape_filter($this->env, twig_number_format_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["arrTotal"]) || array_key_exists("arrTotal", $context) ? $context["arrTotal"] : (function () { throw new Twig_Error_Runtime('Variable "arrTotal" does not exist.', 50, $this->source); })()), "evolscore", array(), "array"), 0, ".", " "), "html", null, true);
                echo "</th>
          <th>";
                // line 51
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["arrTotal"]) || array_key_exists("arrTotal", $context) ? $context["arrTotal"] : (function () { throw new Twig_Error_Runtime('Variable "arrTotal" does not exist.', 51, $this->source); })()), "wins", array(), "array"), "html", null, true);
                echo "</th>
          <th>";
                // line 52
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["arrTotal"]) || array_key_exists("arrTotal", $context) ? $context["arrTotal"] : (function () { throw new Twig_Error_Runtime('Variable "arrTotal" does not exist.', 52, $this->source); })()), "defeats", array(), "array"), "html", null, true);
                echo "</th>
          <th>";
                // line 53
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["arrTotal"]) || array_key_exists("arrTotal", $context) ? $context["arrTotal"] : (function () { throw new Twig_Error_Runtime('Variable "arrTotal" does not exist.', 53, $this->source); })()), "ties", array(), "array"), "html", null, true);
                echo "</th>
        </tr>
      ";
            }
            // line 56
            echo "
      </table>
      <p class=\"inactive-player\"><i>Inactive player</i></p>
      <p><i>W: wins - D: defeats - T: ties</i></p>
    ";
        }
        // line 61
        echo "  ";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    public function getTemplateName()
    {
        return "site/rankings_view.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  170 => 61,  163 => 56,  157 => 53,  153 => 52,  149 => 51,  145 => 50,  141 => 49,  136 => 46,  134 => 45,  131 => 44,  122 => 41,  118 => 40,  114 => 39,  110 => 38,  106 => 37,  102 => 36,  97 => 35,  93 => 33,  89 => 31,  87 => 30,  84 => 29,  80 => 28,  65 => 16,  62 => 15,  60 => 14,  53 => 10,  47 => 6,  41 => 5,  34 => 3,  32 => 1,  15 => 3,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% form_theme form 'bootstrap/bootstrap_4_layout.html.twig' %}

  {% extends \"common/layout.html.twig\" %}

  {% block body %}

    <h3>View Rankings</h3>

    <div class=\"form\">
      {{ form(form) }}
    </div>

    
    {% if detailsRankings|length >0 %}

      <h3>Rankings {{ ranking.date|date(\"Y-m-d\") }}</h3>
      <table class=\"table\">
      <tr>
        <th>#</th>
        <th>Player</th>
        <th>Rating</th>
        <th>Evol</th>
        <th>W</th>
        <th>D</th>
        <th>T</th>
      </tr>

      {% for det in detailsRankings %}

        {% if det.idplayer.active == 1 %}
        <tr>
        {% else %}
        <tr class=\"inactive-player\">
        {% endif %}
          <td>{{ det.position }}</td>
          <td>{{ det.idplayer.nameshort }}</td>
          <td>{{ det.score|number_format(0, '.', ' ') }}</td>
          <td>{{ detailsPlayer[det.idplayer.id][\"evol\"] }}</td>
          <td>{{ detailsPlayer[det.idplayer.id][\"wins\"] }}</td>
          <td>{{ detailsPlayer[det.idplayer.id][\"defeats\"] }}</td>
          <td>{{ detailsPlayer[det.idplayer.id][\"ties\"] }}</td>
        </tr>
      {% endfor %}

      {% if arrTotal|length >0 %}
        <tr>
          <th></th>
          <th></th>
          <th>{{ arrTotal[\"score\"]|number_format(0, '.', ' ') }}</th>
          <th>{{ arrTotal[\"evolscore\"]|number_format(0, '.', ' ') }}</th>
          <th>{{ arrTotal[\"wins\"] }}</th>
          <th>{{ arrTotal[\"defeats\"] }}</th>
          <th>{{ arrTotal[\"ties\"] }}</th>
        </tr>
      {% endif %}

      </table>
      <p class=\"inactive-player\"><i>Inactive player</i></p>
      <p><i>W: wins - D: defeats - T: ties</i></p>
    {% endif %}
  {% endblock %}

", "site/rankings_view.html.twig", "/Users/imac-mb/Documents/tennis-luckyloser/ATL-Stege/atl-stege/templates/site/rankings_view.html.twig");
    }
}
