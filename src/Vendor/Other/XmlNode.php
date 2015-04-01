<?php

namespace Vendor\Other;

//!-----------------------------------------------------------------
// @class      XmlNode
// @desc       Cria um nodo de árvore XML
// @author     Marcos Pont
//!-----------------------------------------------------------------
class XmlNode
{
    public $tag;           // @var tag (string) Tag correspondente ao nodo
    public $attrs;         // @var attrs (array) Vetor de atributos do nodo
    public $children;      // @var children (array) Vetor de filhos do nodo
    public $childrenCount; // @var childrenCount (int) Número de filhos do nodo
    public $value;         // @var value (mixed)       Valor CDATA do nodo XML

    //!-----------------------------------------------------------------
    // @function    XmlNode::XmlNode
    // @desc        Construtor do objeto XmlNode
    // @access      public
    // @param       nodeTag (string) Tag do nodo
    // @param       nodeAttrs (array) Vetor de atributos do nodo
    // @param       nodeChildren (array) Vetor de filhos do nodo, padrão é NULL
    // @param       nodeValue (mixed)       Valor CDATA do nodo XML, padrão é NULL
    //!-----------------------------------------------------------------
    public function __construct($nodeTag, $nodeAttrs, $nodeChildren = null, $nodeValue = null)
    {
        $this->tag = $nodeTag;
        $this->attrs = $nodeAttrs;
        $this->children = $nodeChildren;
        $this->childrenCount = is_array($nodeChildren) ? count($nodeChildren) : 0;
        $this->value = $nodeValue;
    }

    //!-----------------------------------------------------------------
    // @function    XmlNode::hasChildren
    // @desc        Verifica se o nodo XML possui filhos
    // @access      public
    // @returns     TRUE ou FALSE
    //!-----------------------------------------------------------------
    public function hasChildren()
    {
        return ($this->childrenCount > 0);
    }

    //!-----------------------------------------------------------------
    // @function    XmlNode::getChildrenCount
    // @desc        Retorna o número de filhos do nodo XML
    // @access      public
    // @returns     Número de filhos do nodo
    //!-----------------------------------------------------------------
    public function getChildrenCount()
    {
        return $this->childrenCount;
    }

    //!-----------------------------------------------------------------
    // @function    XmlNode::getChildren
    // @desc        Retorna o filho de índice $index do nodo, se existir
    // @param       index (int) ??ndice do nodo buscado
    // @returns     Filho de índice $index ou FALSE se ele não existir
    //!-----------------------------------------------------------------
    public function getChildren($index)
    {
        return (isset($this->children[$index]) ? $this->children[$index] : false);
    }

    //!-----------------------------------------------------------------
    // @function    XmlNode::getChildrenTagsArray
    // @desc        Retorna os filhos do nodo listados em um
    //              vetor associativo indexado pelas TAGS
    // @access      public
    // @returns     Vetor associativo no formato Children1Tag=>Children1Object,
    //              Children2Tag=>Children2Object, ...
    // @note        Esta função não deve ser utilizada quando uma TAG XML
    //              possui filhos com TAGS repetidas
    //!-----------------------------------------------------------------
    public function getChildrenTagsArray()
    {
        if (!$this->children) {
            return false;
        } else {
            $childrenArr = array();
            foreach ($this->children as $children) {
                $childrenArr[$children->tag] = $children;
            }

            return $childrenArr;
        }
    }
}
