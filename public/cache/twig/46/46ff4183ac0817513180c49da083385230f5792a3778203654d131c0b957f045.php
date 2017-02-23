<?php

/* layouts/rest-based.twig */
class __TwigTemplate_5d53062daa6b859e457f5d3e0fab71ae3762839e75c01a03948de95a3898d61a extends Twig_Template
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
        echo "<!doctype html>
<html lang=\"en\">
\t<head>
\t\t<meta charset=\"UTF-8\">
\t\t<title>G2 Design Backend</title>
\t\t<base href=\"";
        // line 6
        echo twig_constant("BASE_URL");
        echo "\">
\t\t<script src=\"https://ajax.googleapis.com/ajax/libs/angularjs/1.4.3/angular.min.js\"></script>
\t\t<script
\t\t\tsrc=\"https://code.jquery.com/jquery-3.1.1.min.js\"
\t\t\tintegrity=\"sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=\"
\t\tcrossorigin=\"anonymous\"></script>
\t\t<script src=\"admin/app/app.js\"></script>
";
        // line 14
        echo "\t\t<script src=\"admin/js/custom.js\"></script>

\t\t<!-- Compiled and minified CSS -->
\t\t<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/css/materialize.min.css\">

\t\t<!-- Compiled and minified JavaScript -->
\t\t<script src=\"https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js\"></script>
\t</head>
\t<body>
\t\t<div ng-app=\"HelloWorldApp\">
\t\t\t<div ng-controller=\"HelloWorldController\">
\t\t\t\t<h1>";
        // line 25
        echo (isset($context["greeting"]) ? $context["greeting"] : null);
        echo "</h1>
\t\t\t</div>
\t\t</div>
\t</body>
</html>
";
    }

    public function getTemplateName()
    {
        return "layouts/rest-based.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  49 => 25,  36 => 14,  26 => 6,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "layouts/rest-based.twig", "D:\\xampp\\htdocs\\website_framework\\modules\\EntityCreator\\Views\\layouts\\rest-based.twig");
    }
}
