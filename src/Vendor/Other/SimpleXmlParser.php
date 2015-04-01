<?php

namespace Vendor\Other;

//!-----------------------------------------------------------------
// @class      SimpleXmlParser
// @desc       Cria um parser que constrói uma estrutura de dados
//             a partir de um arquivo XML
// @author     Marcos Pont
//!-----------------------------------------------------------------
class SimpleXmlParser
{
    public $root;                    // @var root    (object) Objecto XmlNode raiz da árvore XML
    public $parser;                  // @var parser  (resource)     Objeto xml_parser criado
    public $data;                    // @var data    (string) Dados XML a serem interpretados pelo parser
    public $vals;                    // @var vals    (array) Vetor de valores capturados do arquivo XML
    public $index;                   // @var index   (array) Vetor de índices da árvore XML
    public $charset = "UTF-8";  // @var charset (string) Conjunto de caracteres definido para a criação do parser XML

    //!-----------------------------------------------------------------
    // @function        SimpleXmlParser::SimpleXmlParser
    // @desc            Construtor do XML Parser. Parseia o conteúdo XML.
    // @access          public
    // @param           fileName  (string) Nome do arquivo XML a ser processado
    // @param           data      (string) Dados XML, se fileName = ""
    //!-----------------------------------------------------------------
    public function __construct($fileName = '', $data = '', $charset = '')
    {
        if ($data == "") {
            if (!file_exists($fileName)) {
                $this->_raiseError("Can't open file ".$fileName);
            }
            $this->data = implode("", file($fileName));
        } else {
            $this->data = $data;
        }
        $this->data = eregi_replace(">"."[[:space:]]+"."<", "><", $this->data);
        $this->charset = ($charset != '') ? $charset : $this->charset;
        $this->_parseFile($fileName);
    }

    //!-----------------------------------------------------------------
    // @function        SimpleXmlParser::getRoot
    // @desc            Retorna a raiz da árvore XML criada pelo parser
    // @access          public
    // @returns         Raiz da árvore XML
    //!-----------------------------------------------------------------
    public function getRoot()
    {
        return $this->root;
    }

    //!-----------------------------------------------------------------
    // @function        SimpleXmlParser::_parseFile
    // @desc            Inicializa o parser XML, setando suas opções de
    //                  configuração e executa a função de interpretação
    //                  do parser armazenando os resultados em uma estrutura
    //                  de árvore
    // @access          private
    //!-----------------------------------------------------------------
    public function _parseFile($fileName = "")
    {
        $this->parser = xml_parser_create($this->charset);
        xml_parser_set_option($this->parser, XML_OPTION_TARGET_ENCODING, $this->charset);
        xml_parser_set_option($this->parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, 0);

        if (!xml_parse_into_struct($this->parser, $this->data, $this->vals, $this->index)) {
            $this->_raiseError("Error while parsing XML File <b>$fileName</b> : ".xml_error_string(xml_get_error_code($this->parser))." at line ".xml_get_current_line_number($this->parser));
        }
        xml_parser_free($this->parser);
        $this->_buildRoot(0);
    }

    //!-----------------------------------------------------------------
    // @function        SimpleXmlParser::_buildRoot
    // @desc            Cria o apontador da raiz da árvore XML a partir
    //                  do primeiro valor do vetor $this->vals. Inicia a
    //                  execução recursiva para montagem da árvore
    // @access          private
    // @see             PHP2Go::_getChildren
    //!-----------------------------------------------------------------
    public function _buildRoot()
    {
        $i = 0;
        $this->root = new XmlNode(
            $this->vals[$i]['tag'],
            (isset($this->vals[$i]['attributes'])) ? $this->vals[$i]['attributes'] : null,
            $this->_getChildren($this->vals, $i),
            (isset($this->vals[$i]['value'])) ? $this->vals[$i]['value'] : null
        );
    }
    //!-----------------------------------------------------------------
    // @function        SimpleXmlParser::_getChildren
    // @desc            Função recursiva para a montagem da árvore XML
    // @access          private
    // @param           vals (array) vetor de valores do arquivo
    // @param           i    (int) índice atual do vetor de valores
    // @see             PHP2Go::_getRoot
    //!-----------------------------------------------------------------
    public function _getChildren($vals, &$i)
    {
        $children = array();
        while (++$i < sizeof($vals)) {
            switch ($vals[$i]['type']) {
                case 'cdata':
                    array_push($children, $vals[$i]['value']);
                    break;
                case 'complete':
                    array_push($children, new XmlNode(
                        $vals[$i]['tag'],
                        (isset($vals[$i]['attributes']) ? $vals[$i]['attributes'] : null),
                        null,
                        (isset($vals[$i]['value']) ? $vals[$i]['value'] : null)
                    ));
                    break;

                case 'open':
                    array_push($children, new XmlNode(
                        $vals[$i]['tag'],
                        (isset($vals[$i]['attributes']) ? $vals[$i]['attributes'] : null),
                        $this->_getChildren($vals, $i),
                        (isset($vals[$i]['value']) ? $vals[$i]['value'] : null)
                    ));
                    break;

                case 'close':
                    return $children;
            }
        }
    }

    //!-----------------------------------------------------------------
    // @function        SimpleXmlParser::_raiseError
    // @desc            Tratamento de erros da classe
    // @access          private
    // @param           errorMsg (string) Mensagem de erro
    //!-----------------------------------------------------------------

    public function _raiseError($errorMsg)
    {
        trigger_error($errorMsg, E_USER_ERROR);
    }
}
