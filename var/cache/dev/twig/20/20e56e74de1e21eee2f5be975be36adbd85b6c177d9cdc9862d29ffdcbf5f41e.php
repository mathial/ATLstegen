<?php

/* @Framework/Form/form_help.html.php */
class __TwigTemplate_1ed8f4b2fddc2222c17b83f6fe9bb7fd0227d5d45b0cf4714d25847a6a0e33a4 extends Twig_Template
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
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "@Framework/Form/form_help.html.php"));

        // line 1
        echo "<?php if (!empty(\$help)): ?>
    <p id=\"<?php echo \$view->escape(\$id); ?>_help\" class=\"help-text\"><?php echo \$view->escape(false !== \$translation_domain ? \$view['translator']->trans(\$help, array(), \$translation_domain) : \$help); ?></p>
<?php endif; ?>
";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    public function getTemplateName()
    {
        return "@Framework/Form/form_help.html.php";
    }

    public function getDebugInfo()
    {
        return array (  26 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("<?php if (!empty(\$help)): ?>
    <p id=\"<?php echo \$view->escape(\$id); ?>_help\" class=\"help-text\"><?php echo \$view->escape(false !== \$translation_domain ? \$view['translator']->trans(\$help, array(), \$translation_domain) : \$help); ?></p>
<?php endif; ?>
", "@Framework/Form/form_help.html.php", "/Users/imac-mb/Documents/tennis-luckyloser/ATL-Stege/atl-stege/vendor/symfony/framework-bundle/Resources/views/Form/form_help.html.php");
    }
}
