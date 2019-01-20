<?php

/* site/simulator.html.twig */
class __TwigTemplate_94cf6d17f71044570d0a094ac9a454b8d79fb249a2365d718c1daca749ba140f extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 3
        $this->parent = $this->loadTemplate("common/layout.html.twig", "site/simulator.html.twig", 3);
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
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "site/simulator.html.twig"));

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
    <h3>Simulator</h3>

    <div class=\"form\">
      ";
        // line 10
        echo         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new Twig_Error_Runtime('Variable "form" does not exist.', 10, $this->source); })()), 'form');
        echo "
    </div>

    ";
        // line 13
        if ((twig_length_filter($this->env, (isset($context["arrRt"]) || array_key_exists("arrRt", $context) ? $context["arrRt"] : (function () { throw new Twig_Error_Runtime('Variable "arrRt" does not exist.', 13, $this->source); })())) > 0)) {
            // line 14
            echo "    \t<p>Player 1's evolution: <b>";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["arrRt"]) || array_key_exists("arrRt", $context) ? $context["arrRt"] : (function () { throw new Twig_Error_Runtime('Variable "arrRt" does not exist.', 14, $this->source); })()), 1, array(), "array"), "html", null, true);
            echo "</b></p>
    \t<p>Player 2's evolution: <b>";
            // line 15
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["arrRt"]) || array_key_exists("arrRt", $context) ? $context["arrRt"] : (function () { throw new Twig_Error_Runtime('Variable "arrRt" does not exist.', 15, $this->source); })()), 2, array(), "array"), "html", null, true);
            echo "</b></p>
    ";
        }
        // line 17
        echo "
  ";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    public function getTemplateName()
    {
        return "site/simulator.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  71 => 17,  66 => 15,  61 => 14,  59 => 13,  53 => 10,  47 => 6,  41 => 5,  34 => 3,  32 => 1,  15 => 3,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% form_theme form 'bootstrap/bootstrap_4_layout.html.twig' %}

  {% extends \"common/layout.html.twig\" %}

  {% block body %}

    <h3>Simulator</h3>

    <div class=\"form\">
      {{ form(form) }}
    </div>

    {% if arrRt|length > 0 %}
    \t<p>Player 1's evolution: <b>{{ arrRt[1] }}</b></p>
    \t<p>Player 2's evolution: <b>{{ arrRt[2] }}</b></p>
    {% endif %}

  {% endblock %}
", "site/simulator.html.twig", "/Users/imac-mb/Documents/tennis-luckyloser/ATL-Stege/atl-stege/templates/site/simulator.html.twig");
    }
}
