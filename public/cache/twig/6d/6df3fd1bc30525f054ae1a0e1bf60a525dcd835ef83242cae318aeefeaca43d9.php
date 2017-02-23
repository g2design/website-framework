<?php

/* partials/search.twig */
class __TwigTemplate_5ec4172a59b6dd6dc5bbd7737df4fa24c78b77a647421f62c46eab16fc7f0c0b extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!-- used for searching the current list -->
<input type=\"text\" ng-model=\"search\" class=\"form-control\" placeholder=\"Search product...\">
 
<!-- table that shows product record list -->
<table class=\"hoverable bordered\">
    <thead>
        <tr>
            <th class=\"text-align-center\">ID</th>
            <th class=\"width-30-pct\">Name</th>
            <th class=\"width-30-pct\">Description</th>
            <th class=\"text-align-center\">Price</th>
            <th class=\"text-align-center\">Action</th>
        </tr>
    </thead>
    <tbody ng-init=\"getAll()\">
        <tr ng-repeat=\"d in names | filter:search\">
            <td class=\"text-align-center\">";
        // line 17
        echo $this->getAttribute((isset($context["d"]) ? $context["d"] : null), "id", array());
        echo "</td>
            <td>";
        // line 18
        echo $this->getAttribute((isset($context["d"]) ? $context["d"] : null), "name", array());
        echo "</td>
            <td>";
        // line 19
        echo $this->getAttribute((isset($context["d"]) ? $context["d"] : null), "description", array());
        echo "</td>
            <td class=\"text-align-center\">";
        // line 20
        echo $this->getAttribute((isset($context["d"]) ? $context["d"] : null), "price", array());
        echo "</td>
            <td>
                <a ng-click=\"readOne(d.id)\" class=\"waves-effect waves-light btn margin-bottom-1em\"><i class=\"material-icons left\">edit</i>Edit</a>
                <a ng-click=\"deleteProduct(d.id)\" class=\"waves-effect waves-light btn margin-bottom-1em\"><i class=\"material-icons left\">delete</i>Delete</a>
            </td>
        </tr>
    </tbody>
</table>";
    }

    public function getTemplateName()
    {
        return "partials/search.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  49 => 20,  45 => 19,  41 => 18,  37 => 17,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "partials/search.twig", "D:\\xampp\\htdocs\\website_framework\\modules\\EntityCreator\\Views\\partials\\search.twig");
    }
}
