<?php

/*
 * Este es parte de Twig.
 *
 * (c) 2011 Fabien Potencier
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */

/**
 * Returns the value or the default value when it is undefined or empty.
 *
 * <pre>
 *  {{ var.foo|default('el elemento foo en var no está definido') }}
 * </pre>
 *
 * @package twig
 * @author  Fabien Potencier <fabien@symfony.com>
 */
class Twig_Node_Expression_Filter_Default extends Twig_Node_Expression_Filter
{
    public function __construct(Twig_NodeInterface $node, Twig_Node_Expression_Constant $filterName, Twig_NodeInterface $arguments, $lineno, $tag = null)
    {
        $default = new Twig_Node_Expression_Filter($node, new Twig_Node_Expression_Constant('default', $node->getLine()), $arguments, $node->getLine());

        if ('default' === $filterName->getAttribute('value') && ($node instanceof Twig_Node_Expression_Name || $node instanceof Twig_Node_Expression_GetAttr)) {
            $test = new Twig_Node_Expression_Test_Defined(clone $node, 'defined', new Twig_Node(), $node->getLine());
            $false = count($arguments) ? $arguments->getNode(0) : new Twig_Node_Expression_Constant('', $node->getLine());

            $node = new Twig_Node_Expression_Conditional($test, $default, $false, $node->getLine());
        } else {
            $node = $default;
        }

        parent::__construct($node, $filterName, $arguments, $lineno, $tag);
    }

    public function compile(Twig_Compiler $compiler)
    {
        $compiler->subcompile($this->getNode('node'));
    }
}