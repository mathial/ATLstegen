<?php

/* site/matchs_list.html.twig */
class __TwigTemplate_6b887e18937f7a211709084026dd04eeb95f347ec00ca0421c041f6c3f41ca60 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("common/layout.html.twig", "site/matchs_list.html.twig", 1);
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
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "site/matchs_list.html.twig"));

        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    // line 3
    public function block_body($context, array $blocks = array())
    {
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "body"));

        // line 4
        echo "
  <h3>List Matchs (";
        // line 5
        echo twig_escape_filter($this->env, twig_length_filter($this->env, (isset($context["listMatchs"]) || array_key_exists("listMatchs", $context) ? $context["listMatchs"] : (function () { throw new Twig_Error_Runtime('Variable "listMatchs" does not exist.', 5, $this->source); })())), "html", null, true);
        echo ") - Page ";
        echo twig_escape_filter($this->env, (isset($context["page"]) || array_key_exists("page", $context) ? $context["page"] : (function () { throw new Twig_Error_Runtime('Variable "page" does not exist.', 5, $this->source); })()), "html", null, true);
        echo " / ";
        echo twig_escape_filter($this->env, (isset($context["nbPages"]) || array_key_exists("nbPages", $context) ? $context["nbPages"] : (function () { throw new Twig_Error_Runtime('Variable "nbPages" does not exist.', 5, $this->source); })()), "html", null, true);
        echo "</h3>

  ";
        // line 7
        $this->loadTemplate("common/filter.html.twig", "site/matchs_list.html.twig", 7)->display($context);
        // line 8
        echo "
  <table class=\"table\">
    <tr>
      <th>";
        // line 11
        echo $this->extensions['Knp\Bundle\PaginatorBundle\Twig\Extension\PaginationExtension']->sortable($this->env, (isset($context["listMatchs"]) || array_key_exists("listMatchs", $context) ? $context["listMatchs"] : (function () { throw new Twig_Error_Runtime('Variable "listMatchs" does not exist.', 11, $this->source); })()), "#", "m.id");
        echo "</th>
      <th>";
        // line 12
        echo $this->extensions['Knp\Bundle\PaginatorBundle\Twig\Extension\PaginationExtension']->sortable($this->env, (isset($context["listMatchs"]) || array_key_exists("listMatchs", $context) ? $context["listMatchs"] : (function () { throw new Twig_Error_Runtime('Variable "listMatchs" does not exist.', 12, $this->source); })()), "Date", "m.date");
        echo "</th>
      <th>Player1</th>
      <th>Player2</th>
      <th>Score</th>
      <th>";
        // line 16
        echo $this->extensions['Knp\Bundle\PaginatorBundle\Twig\Extension\PaginationExtension']->sortable($this->env, (isset($context["listMatchs"]) || array_key_exists("listMatchs", $context) ? $context["listMatchs"] : (function () { throw new Twig_Error_Runtime('Variable "listMatchs" does not exist.', 16, $this->source); })()), "Context", "m.context");
        echo "</th>
      <th>";
        // line 17
        echo $this->extensions['Knp\Bundle\PaginatorBundle\Twig\Extension\PaginationExtension']->sortable($this->env, (isset($context["listMatchs"]) || array_key_exists("listMatchs", $context) ? $context["listMatchs"] : (function () { throw new Twig_Error_Runtime('Variable "listMatchs" does not exist.', 17, $this->source); })()), "Conditions", "m.conditions");
        echo "</th>
      <th>Ranking</th>
    </tr>
    ";
        // line 20
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["listMatchs"]) || array_key_exists("listMatchs", $context) ? $context["listMatchs"] : (function () { throw new Twig_Error_Runtime('Variable "listMatchs" does not exist.', 20, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["match"]) {
            // line 21
            echo "
      <tr>
        <td align=center>";
            // line 23
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["match"], "id", array()), "html", null, true);
            echo "</td>
        <td align=center>";
            // line 24
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, twig_get_attribute($this->env, $this->source, $context["match"], "date", array()), "Y-m-d"), "html", null, true);
            echo "</td>
        ";
            // line 25
            if ((twig_get_attribute($this->env, $this->source, $context["match"], "tie", array()) != 1)) {
                // line 26
                echo "          <td class=\"winner-player\" align=center>
        ";
            } else {
                // line 28
                echo "          <td align=center>
        ";
            }
            // line 30
            echo "        ";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["match"], "idplayer1", array()), "nameshort", array()), "html", null, true);
            echo "</td>
        <td align=center>";
            // line 31
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["match"], "idplayer2", array()), "nameshort", array()), "html", null, true);
            echo "</td>
        <td align=center>";
            // line 32
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["match"], "score", array()), "html", null, true);
            echo "</td>
        <td align=center>";
            // line 33
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["match"], "context", array()), "html", null, true);
            echo "</td>
        <td align=center>";
            // line 34
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["match"], "conditions", array()), "html", null, true);
            echo "</td>
        ";
            // line 36
            echo "        <td></td>
      </tr>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['match'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 39
        echo "  </table>

  ";
        // line 41
        $context["page_list"] = "matchs_list";
        // line 43
        echo "  ";
        $this->loadTemplate("common/pager.html.twig", "site/matchs_list.html.twig", 43)->display($context);
        // line 44
        echo "
";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    public function getTemplateName()
    {
        return "site/matchs_list.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  148 => 44,  145 => 43,  143 => 41,  139 => 39,  131 => 36,  127 => 34,  123 => 33,  119 => 32,  115 => 31,  110 => 30,  106 => 28,  102 => 26,  100 => 25,  96 => 24,  92 => 23,  88 => 21,  84 => 20,  78 => 17,  74 => 16,  67 => 12,  63 => 11,  58 => 8,  56 => 7,  47 => 5,  44 => 4,  38 => 3,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"common/layout.html.twig\" %}

{% block body %}

  <h3>List Matchs ({{ listMatchs|length }}) - Page {{ page }} / {{ nbPages }}</h3>

  {% include \"common/filter.html.twig\" %}

  <table class=\"table\">
    <tr>
      <th>{{ knp_pagination_sortable(listMatchs, '#', 'm.id') }}</th>
      <th>{{ knp_pagination_sortable(listMatchs, 'Date', 'm.date') }}</th>
      <th>Player1</th>
      <th>Player2</th>
      <th>Score</th>
      <th>{{ knp_pagination_sortable(listMatchs, 'Context', 'm.context') }}</th>
      <th>{{ knp_pagination_sortable(listMatchs, 'Conditions', 'm.conditions') }}</th>
      <th>Ranking</th>
    </tr>
    {% for match in listMatchs %}

      <tr>
        <td align=center>{{ match.id }}</td>
        <td align=center>{{ match.date|date(\"Y-m-d\") }}</td>
        {% if match.tie!=1 %}
          <td class=\"winner-player\" align=center>
        {% else %}
          <td align=center>
        {% endif %}
        {{ match.idplayer1.nameshort }}</td>
        <td align=center>{{ match.idplayer2.nameshort }}</td>
        <td align=center>{{ match.score }}</td>
        <td align=center>{{ match.context }}</td>
        <td align=center>{{ match.conditions }}</td>
        {# <td>{{ match.ranking }}</td> #}
        <td></td>
      </tr>
    {% endfor %}
  </table>

  {% set page_list = 'matchs_list' 
  %}
  {% include \"common/pager.html.twig\" %}

{% endblock %}

", "site/matchs_list.html.twig", "/Users/imac-mb/Documents/tennis-luckyloser/ATL-Stege/atl-stege/templates/site/matchs_list.html.twig");
    }
}
