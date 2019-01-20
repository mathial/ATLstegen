<?php

/* site/list_players.html.twig */
class __TwigTemplate_96761c32e92ee7b9849b0d14423679a433b4930bf7a53776d8ea75813599b355 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("common/layout.html.twig", "site/list_players.html.twig", 1);
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
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "site/list_players.html.twig"));

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
  <h3>List Players (";
        // line 5
        echo twig_escape_filter($this->env, twig_length_filter($this->env, (isset($context["listPlayers"]) || array_key_exists("listPlayers", $context) ? $context["listPlayers"] : (function () { throw new Twig_Error_Runtime('Variable "listPlayers" does not exist.', 5, $this->source); })())), "html", null, true);
        echo ") - Page ";
        echo twig_escape_filter($this->env, (isset($context["page"]) || array_key_exists("page", $context) ? $context["page"] : (function () { throw new Twig_Error_Runtime('Variable "page" does not exist.', 5, $this->source); })()), "html", null, true);
        echo " / ";
        echo twig_escape_filter($this->env, (isset($context["nbPages"]) || array_key_exists("nbPages", $context) ? $context["nbPages"] : (function () { throw new Twig_Error_Runtime('Variable "nbPages" does not exist.', 5, $this->source); })()), "html", null, true);
        echo "</h3>

  ";
        // line 7
        $this->loadTemplate("common/filter.html.twig", "site/list_players.html.twig", 7)->display($context);
        // line 8
        echo "
  <table class=\"table\">
    <tr>
      <th>";
        // line 11
        echo $this->extensions['Knp\Bundle\PaginatorBundle\Twig\Extension\PaginationExtension']->sortable($this->env, (isset($context["listPlayers"]) || array_key_exists("listPlayers", $context) ? $context["listPlayers"] : (function () { throw new Twig_Error_Runtime('Variable "listPlayers" does not exist.', 11, $this->source); })()), "Id", "p.id");
        echo "</th>
      <th>";
        // line 12
        echo $this->extensions['Knp\Bundle\PaginatorBundle\Twig\Extension\PaginationExtension']->sortable($this->env, (isset($context["listPlayers"]) || array_key_exists("listPlayers", $context) ? $context["listPlayers"] : (function () { throw new Twig_Error_Runtime('Variable "listPlayers" does not exist.', 12, $this->source); })()), "Name", "p.namelong");
        echo "</th>
      <th>";
        // line 13
        echo $this->extensions['Knp\Bundle\PaginatorBundle\Twig\Extension\PaginationExtension']->sortable($this->env, (isset($context["listPlayers"]) || array_key_exists("listPlayers", $context) ? $context["listPlayers"] : (function () { throw new Twig_Error_Runtime('Variable "listPlayers" does not exist.', 13, $this->source); })()), "Mail", "p.email");
        echo "</th>
      <th>Stts</th>
      <th>Roles</th>
      <th>Action</th>
    </tr>
    ";
        // line 18
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["listPlayers"]) || array_key_exists("listPlayers", $context) ? $context["listPlayers"] : (function () { throw new Twig_Error_Runtime('Variable "listPlayers" does not exist.', 18, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["player"]) {
            // line 19
            echo "
      <tr>
        <td><a href=\"";
            // line 21
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("player_view", array("id" => twig_get_attribute($this->env, $this->source, $context["player"], "id", array()))), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["player"], "id", array()), "html", null, true);
            echo "</a></td>
        <td>";
            // line 22
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["player"], "namelong", array()), "html", null, true);
            echo "</td>
        <td>";
            // line 23
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["player"], "email", array()), "html", null, true);
            echo "</td>
        <td>";
            // line 24
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["player"], "active", array()), "html", null, true);
            echo "</td>
        <td>";
            // line 25
            echo twig_escape_filter($this->env, twig_join_filter(twig_get_attribute($this->env, $this->source, $context["player"], "roles", array()), ", "), "html", null, true);
            echo "</td>
        <td>
";
            // line 33
            echo "
        </td>
      </tr>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['player'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 37
        echo "  </table>

  ";
        // line 39
        $context["page_list"] = "players_list";
        // line 41
        echo "  ";
        $this->loadTemplate("common/pager.html.twig", "site/list_players.html.twig", 41)->display($context);
        // line 42
        echo "
";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    public function getTemplateName()
    {
        return "site/list_players.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  128 => 42,  125 => 41,  123 => 39,  119 => 37,  110 => 33,  105 => 25,  101 => 24,  97 => 23,  93 => 22,  87 => 21,  83 => 19,  79 => 18,  71 => 13,  67 => 12,  63 => 11,  58 => 8,  56 => 7,  47 => 5,  44 => 4,  38 => 3,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"common/layout.html.twig\" %}

{% block body %}

  <h3>List Players ({{ listPlayers|length }}) - Page {{ page }} / {{ nbPages }}</h3>

  {% include \"common/filter.html.twig\" %}

  <table class=\"table\">
    <tr>
      <th>{{ knp_pagination_sortable(listPlayers, 'Id', 'p.id') }}</th>
      <th>{{ knp_pagination_sortable(listPlayers, 'Name', 'p.namelong') }}</th>
      <th>{{ knp_pagination_sortable(listPlayers, 'Mail', 'p.email') }}</th>
      <th>Stts</th>
      <th>Roles</th>
      <th>Action</th>
    </tr>
    {% for player in listPlayers %}

      <tr>
        <td><a href=\"{{ path('player_view', {'id': player.id}) }}\">{{ player.id }}</a></td>
        <td>{{ player.namelong }}</td>
        <td>{{ player.email }}</td>
        <td>{{ player.active }}</td>
        <td>{{ player.roles|join(', ') }}</td>
        <td>
{#           <a class=\"btn-default btn\" href=\"{{ path('admin_access.view_player', {'id': player.id}) }}\"><img class=\"icon-list\" src=\"{{ asset('bundles/app/images/edit.png') }}\"></a>
          {% if player.status == 1 %} 
            <a class=\"btn-default btn\" href=\"{{ path('admin_access.disable_player', {'id': player.id}) }}\" onclick=\"return confirm('Please confirm the disabling of the player');\"><img class=\"icon-list\" src=\"{{ asset('bundles/app/images/delete.png') }}\"></a>
          {% else %}
            <a class=\"btn-default btn\" href=\"{{ path('admin_access.activate_player', {'id': player.id}) }}\" onclick=\"return confirm('Please confirm the activation of the player');\"><img class=\"icon-list\" src=\"{{ asset('bundles/app/images/add.png') }}\"></a>
          {% endif %} #}

        </td>
      </tr>
    {% endfor %}
  </table>

  {% set page_list = 'players_list' 
  %}
  {% include \"common/pager.html.twig\" %}

{% endblock %}

", "site/list_players.html.twig", "/Users/imac-mb/Documents/tennis-luckyloser/ATL-Stege/atl-stege/templates/site/list_players.html.twig");
    }
}
