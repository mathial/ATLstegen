<?php

/* common/filter.html.twig */
class __TwigTemplate_e0bf36f461fdad2ea30e4f9e974c9b5fb97bb1f5ec3b21492029ca0b308a2040 extends Twig_Template
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
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "common/filter.html.twig"));

        // line 1
        echo "  <div>
    <form method=\"GET\">
      <input name=\"filter\" type=\"text\" value=\"";
        // line 3
        echo twig_escape_filter($this->env, (isset($context["filter"]) || array_key_exists("filter", $context) ? $context["filter"] : (function () { throw new Twig_Error_Runtime('Variable "filter" does not exist.', 3, $this->source); })()), "html", null, true);
        echo "\">
      <button type=\"submit\" class=\"btn btn-default btn-sm\">Filter</button>
    </form>
  </div>";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    public function getTemplateName()
    {
        return "common/filter.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  30 => 3,  26 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("  <div>
    <form method=\"GET\">
      <input name=\"filter\" type=\"text\" value=\"{{ filter }}\">
      <button type=\"submit\" class=\"btn btn-default btn-sm\">Filter</button>
    </form>
  </div>", "common/filter.html.twig", "/Users/imac-mb/Documents/tennis-luckyloser/ATL-Stege/atl-stege/templates/common/filter.html.twig");
    }
}
