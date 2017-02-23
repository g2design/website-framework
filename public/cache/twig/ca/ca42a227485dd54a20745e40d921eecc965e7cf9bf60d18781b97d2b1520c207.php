<?php

/* partials/product-form.twig */
class __TwigTemplate_fedf0445336758474788dc129e785c31e83324650b7380c6f96bf7ed02792b43 extends Twig_Template
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
        echo "<div id=\"modal-product-form\" class=\"modal\">
\t<div class=\"modal-content\">
\t\t<h4 id=\"modal-product-title\">Create New Product</h4>
\t\t<div class=\"row\">
\t\t\t<div class=\"input-field col s12\">
\t\t\t\t<input ng-model=\"name\" type=\"text\" class=\"validate\" id=\"form-name\" placeholder=\"Type name here...\" />
\t\t\t\t<label for=\"name\">Name</label>
\t\t\t</div>
\t\t\t<div class=\"input-field col s12\">
\t\t\t\t<textarea ng-model=\"description\" type=\"text\" class=\"validate materialize-textarea\" placeholder=\"Type description here...\"></textarea>
\t\t\t\t<label for=\"description\">Description</label>
\t\t\t</div>
\t\t\t<div class=\"input-field col s12\">
\t\t\t\t<input ng-model=\"price\" type=\"text\" class=\"validate\" id=\"form-price\" placeholder=\"Type price here...\" />
\t\t\t\t<label for=\"price\">Price</label>
\t\t\t</div>
\t\t\t<div class=\"input-field col s12\">
\t\t\t\t<a id=\"btn-create-product\" class=\"waves-effect waves-light btn margin-bottom-1em\" ng-click=\"createProduct()\"><i class=\"material-icons left\">add</i>Create</a>

\t\t\t\t<a id=\"btn-update-product\" class=\"waves-effect waves-light btn margin-bottom-1em\" ng-click=\"updateProduct()\"><i class=\"material-icons left\">edit</i>Save Changes</a>

\t\t\t\t<a class=\"modal-action modal-close waves-effect waves-light btn margin-bottom-1em\"><i class=\"material-icons left\">close</i>Close</a>
\t\t\t</div>
\t\t</div>
\t</div>
</div>
<!-- floating button for creating product -->
<div class=\"fixed-action-btn\" style=\"bottom:45px; right:24px;\">
\t<a class=\"waves-effect waves-light btn modal-trigger btn-floating btn-large red\" href=\"#modal-product-form\" ng-click=\"showCreateForm()\"><i class=\"large material-icons\">add</i></a>
</div>";
    }

    public function getTemplateName()
    {
        return "partials/product-form.twig";
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "partials/product-form.twig", "D:\\xampp\\htdocs\\website_framework\\modules\\EntityCreator\\Views\\partials\\product-form.twig");
    }
}
