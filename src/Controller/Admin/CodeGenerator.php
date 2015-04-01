<?php

namespace Controller\Admin;

class CodeGenerator extends BaseController
{
    public function indexAction()
    {

        $tables = array();

        //get all tables from database
        $stmt = $this->registry->db->query('SHOW TABLES');
        while ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            if ($this->validGenerateTable($row[0])) {
                $tables[] = $row[0];
            }
        }

        $this->registry->smarty->assign(array('tables' => $tables));

        $contents = $this->registry->smarty->fetch($this->registry->smartyController . 'index.tpl');

        $this->registry->smarty->assign(array(
            'pageTitle' => 'Code Generator',
            'contents' => $contents
        ));
        $contents = $this->registry->smarty->fetch($this->registry->smartyModule . 'index.tpl');
        $this->registry->response->setContent($contents);

    }

    private function validGenerateTable($table)
    {
        return !in_array($table, array(TABLE_PREFIX . 'ac_user', TABLE_PREFIX . 'ac_user_profile'));
    }

    public function generateAction()
    {
        $success = $error = $information = $formData = array();
        $table = $this->registry->router->getArg('table');

        $columnData = array();
        $indexColumnData = array();
        $tableNotFound = false;

        $tableWithoutPrefix = '';
        $primaryField = '';
        $source = array();

        if ($table == '' || !$this->validGenerateTable($table)) {
            $error[] = 'Table not found.';
            $tableNotFound = true;

        } else {
            //check directory to save files
            $directories['model'] = SITE_PATH;
            $directories['controlleradmin'] = SITE_PATH . 'controllers/{{CONTROLLER_GROUP}}/';
            $directories['languageadmin'] = SITE_PATH . 'language/'
                . $this->registry->langCode . '/{{CONTROLLER_GROUP}}/';
            $directories['templateadmin'] = $this->registry->smarty->template_dir
                . '/_controller/{{CONTROLLER_GROUP}}/';
            $formData['directories'] = $directories;

            //template files
            $source_basedir = $this->registry->smarty->template_dir . '/'
                . $this->registry->smartyController . 'generate_format/';
            $source['model'] = $source_basedir . 'model.tpl';
            $source['controlleradmin'] = $source_basedir . 'controller_admin.tpl';
            $source['languageadmin'] = $source_basedir . 'language_admin.tpl';
            $source['controlleradminindex'] = $source_basedir . 'controller_admin_index.tpl';
            $source['controlleradminadd'] = $source_basedir . 'controller_admin_add.tpl';
            $source['controlleradminedit'] = $source_basedir . 'controller_admin_edit.tpl';

            $tableWithoutPrefix = str_replace(TABLE_PREFIX, '', $table);

            //default Classname
            $formData['fmodule'] = $formData['fcontrollerclass'] = $this->getDefaultClassName($table);
            $formData['MODULE_LOWER'] = strtolower($formData['fmodule']);
            $formData['ftablealias'] = $this->getDefaultTableAlias($tableWithoutPrefix);

            //get table fields detail
            $sql = 'SHOW COLUMNS FROM ' . $table;
            try {
                $stmt = $this->registry->db->query($sql);
                $columnData = $stmt->fetchAll();
            } catch (\Exception $e) {
                $error[] = $e->getMessage();
            }

            //get table index detail
            $sql = 'SHOW INDEX FROM ' . $table;
            try {
                $stmt = $this->registry->db->query($sql);
                $indexData = $stmt->fetchAll();

                if (count($indexData) > 0) {
                    foreach ($indexData as $indexInfo) {
                        $indexColumnData[] = $indexInfo['Column_name'];
                    }
                }
            } catch (\Exception $e) {
                $error[] = $e->getMessage();
            }

            //get column prefix, based on PRIMARY KEY name
            $primaryField = '';
            foreach ($columnData as $columnDetail) {
                foreach ($columnDetail as $key => $value) {
                    if ($key == 'Key' && $value == 'PRI') {
                        $primaryField = $columnDetail['Field'];
                    }
                }
            }
            //check if primaryField had the underscore _
            $primaryParts = explode('_', $primaryField);
            if ($primaryParts[0] != $primaryField) {
                $columnPrefix = $primaryParts[0] . '_';
            } else {
                $columnPrefix = '';
            }

            //tracking text field in column base on data type
            //to enable in search text
            $formData['textfield'] = array();

            //build the Default Property after remove the columnPrefix
            foreach ($columnData as $columnDetail) {
                //remove column Prefix for Model Property
                $propValue = str_replace($columnPrefix, '', $columnDetail['Field']);

                //remove remain underscores in property name
                $propValue = str_replace('_', '', $propValue);

                $formData['fprop'][$columnDetail['Field']] = $propValue;

                $labeltmpvalue = ucfirst($propValue);

                $predefinedLabel = array(
                    'id' => 'ID',
                    'uid' => 'User ID',
                    'pid' => 'Product ID',
                    'ipaddress' => 'IP Address',
                    'ip' => 'IP Address',
                    'displayorder' => 'Display Order',
                    'datecreated' => 'Date Created',
                    'datemodified' => 'Date Modified',
                    'datepublished' => 'Date Published',
                    'seotitle' => 'SEO Title',
                    'seokeyword' => 'SEO Keyword',
                    'seodescription' => 'SEO Description',
                    'fullname' => 'Full Name',
                    'firstname' => 'First Name',
                    'lastname' => 'Last Name',
                    'filesize' => 'File Size',
                    'filetype' => 'File Type',
                    'invoiceid' => 'Invoice ID',
                    'isdeleted' => 'Is Deleted',
                    'isenable' => 'Is Enable',
                    'isactive' => 'Is Active',
                    'parentid' => 'Parent ID',
                    'objectid' => 'Object ID'
                );

                if (isset($predefinedLabel[$propValue])) {
                    $labeltmpvalue = $predefinedLabel[$propValue];
                }


                $formData['flabel'][$columnDetail['Field']] = $labeltmpvalue;

                if (stripos($columnDetail['Type'], 'char') !== false
                    || stripos($columnDetail['Type'], 'text') !== false
                ) {
                    $formData['textfield'][] = $columnDetail['Field'];
                }
            }

        }

        //start generating
        if ($this->postBag->has('fsubmit')) {
            $formData = array_merge($formData, $this->postBag->all());

            $directories['model'] = SITE_PATH;
            $directories['controlleradmin'] = SITE_PATH . 'Controller' . DIRECTORY_SEPARATOR;
            $directories['languageadmin'] = SITE_PATH . 'language' . DIRECTORY_SEPARATOR
                . $this->registry->langCode . DIRECTORY_SEPARATOR
                . strtolower($formData['fcontrollernamespace']) . DIRECTORY_SEPARATOR;
            $directories['templateadmin'] = $this->registry->smarty->template_dir
                . DIRECTORY_SEPARATOR . '_controller' . DIRECTORY_SEPARATOR
                . strtolower($formData['fcontrollernamespace']) . DIRECTORY_SEPARATOR;

            $formData['directories'] = $directories;
            $formData['tableWithoutPrefix'] = $tableWithoutPrefix;
            $formData['primaryfield'] = $primaryField;

            //assign default filterable
            $formData['ffilterable'][$primaryField] = 1;
            $formData['fsortable'][$primaryField] = 1;

            //refine prop array because there is no character outside [a-z0-9_]
            foreach ($formData['fprop'] as $k => $v) {
                $formData['fprop'][$k] = preg_replace('/[^a-z0-9_]/ims', '_', $v);
                $formData['ftypeshort'][$k] = $this->getColumnTypeShort($formData['ftype'][$k]);
            }

            if ($this->generateValidate($formData, $error)) {
                //Process Model Namespace to check directory
                $directories['model'] .= str_replace('\\', DIRECTORY_SEPARATOR, $formData['fmodulenamespace']);

                //destination
                $destination['model'] = $directories['model'] . DIRECTORY_SEPARATOR . $formData['fmodule'] . '.php';

                //check enable generating Admin Controller
                if (isset($formData['fadmincontrollertoggler'])) {

                    ///////////////////////////////
                    //MENU ITEM
                    $leftMenuAppendString = '<li id="menu_' . strtolower($formData['fcontrollerclass'])
                        . '"><a class="sidebar-link" href="{$conf.rooturl}'
                        . strtolower($formData['fcontrollernamespace']) . '/'
                        . strtolower($formData['fcontrollerclass']) . '"><i class="fa '
                        . $formData['fcontrollericonclass'] . '"></i> ' . $formData['fmodule'] . 's</a></li>';
                    $information[] = 'Add following line to '
                        . '<code>/templates/default/_controller/admin/header_leftmenu.tpl</code>'
                        . 'to create menu item link to this controller: <small><pre>'
                        . htmlspecialchars($leftMenuAppendString) . '</pre></small>';

                    ///////////////////////////////
                    //CLASSMAP FOR CONTROLLER
                    $controllerClassmapAppendString = "'controller\\"
                        . strtolower($formData['fcontrollernamespace']) . "\\"
                        . strtolower($formData['fmodule']) . "' => 'Controller' . DIRECTORY_SEPARATOR . '"
                        . $formData['fcontrollernamespace'] . "' . DIRECTORY_SEPARATOR . '"
                        . $formData['fmodule'] . ".php',";
                    $information[] = '<a href="' . $this->registry->conf['rooturl_admin']
                        . $this->registry->controller . '/classmap" title="Generate Classmap Array" '
                        . ' target="_blank"><b>[CLICK HERE]</b></a> to generate new Classmap Array or just adding '
                        . 'following line to Array <code>$classmapList</code> declared in '
                        . '<code>/includes/classmap.php</code> for mapping controller file name to class name: '
                        . '<small><pre>' . htmlspecialchars($controllerClassmapAppendString) . '</pre></small>';

                    ///////////////////////////////
                    //PERMISSION FOR CONTROLLER
                    $controllerPermissionAppendString = "'" . strtolower($formData['fcontrollerclass']) . "_*',";
                    $information[] = 'Add following line to Array <code>$groupPermisson[...]</code> declared in '
                        . ' <code>/includes/permission.php</code> to grant permission for user group you want '
                        . '<small><pre>' . htmlspecialchars($controllerPermissionAppendString) . '</pre></small>';


                    $adminmodule = $formData['fcontrollerclass'];

                    $directories['controlleradmin'] .= $formData['fcontrollernamespace'] . DIRECTORY_SEPARATOR;

                    $destination['controlleradmin'] = $directories['controlleradmin'] . $adminmodule . '.php';
                    $destination['languageadmin'] = $directories['languageadmin'] . strtolower($adminmodule) . '.xml';

                    //create Admin template directory to save generated TPL files.
                    $savetplfiles = false;
                    $admintemplatedirectory = $directories['templateadmin'] . strtolower($adminmodule);
                    if (!file_exists($admintemplatedirectory)) {
                        if (!mkdir($admintemplatedirectory)) {
                            $error[] = 'Error while creating directory <code>' . $directories['templateadmin']
                                . strtolower($adminmodule) . '</code> to store Admin template files.';
                        } else {
                            $savetplfiles = true;
                        }
                    } else {
                        if (!is_writable($admintemplatedirectory)) {
                            $error[] = 'Directory <code>' . $admintemplatedirectory
                                . '</code> is not writable to save generated tpl files.';
                        } else {
                            $savetplfiles = true;
                        }
                    }

                    if ($savetplfiles) {
                        $destination['controlleradminindex'] = $directories['templateadmin']
                            . strtolower($adminmodule) . DIRECTORY_SEPARATOR . 'index.tpl';
                        $destination['controlleradminadd'] = $directories['templateadmin']
                            . strtolower($adminmodule) . DIRECTORY_SEPARATOR . 'add.tpl';
                        $destination['controlleradminedit'] = $directories['templateadmin']
                            . strtolower($adminmodule) . DIRECTORY_SEPARATOR . 'edit.tpl';
                    }

                }

                //looping through source file
                foreach ($destination as $index => $destpath) {
                    $overwrite = false;
                    if (!file_exists($destpath) || (file_exists($destpath) && isset($formData['foverwrite']))) {
                        $overwrite = true;
                    }

                    //start writing
                    if ($overwrite) {
                        $sourcecontent = file_get_contents($source[$index]);
                        $replaceData = $this->getSearchReplaceInfo($formData);

                        $sourcecontent = str_replace($replaceData['search'], $replaceData['replace'], $sourcecontent);

                        if (file_put_contents($destpath, $sourcecontent) !== false) {
                            $success[] = 'Saved file <code>' . $destpath . '</code> successfully.';
                        } else {
                            $error[] = 'Error while saving file <code>' . $destpath . '</code>.';
                        }
                    } else {
                        $error[] = 'File <code>' . $destpath . '</code> Existed, check Overwrite to save new file.';
                    }

                }
            }
        }

        $this->registry->smarty->assign(array(
            'success' => $success,
            'error' => $error,
            'information' => $information,
            'formData' => $formData,
            'table' => $table,
            'tableNotFound' => $tableNotFound,
            'tableWithoutPrefix' => $tableWithoutPrefix,
            'columnData' => $columnData,
            'indexColumnData' => $indexColumnData
        ));

        $contents = $this->registry->smarty->fetch($this->registry->smartyController . 'generate.tpl');

        $this->registry->smarty->assign(array(
            'pageTitle' => 'Code Generating',
            'contents' => $contents
        ));
        $contents = $this->registry->smarty->fetch($this->registry->smartyModule . 'index.tpl');
        $this->registry->response->setContent($contents);

    }

    /**
     * Generate Classmap array for mapping controller class to filename
     */
    public function classmapAction()
    {
        $success = $error = $warning = array();
        $sourceBasedir = $this->registry->smarty->template_dir
            . DIRECTORY_SEPARATOR . $this->registry->smartyController . 'generate_format' . DIRECTORY_SEPARATOR;

        //Duyet thu muc controller de lay danh sach filename
        $controllerDir = 'Controller';

        $classmapFiledata = '';

        if (is_readable($controllerDir)) {
            $fileListing = \Litpi\Helper::directoryToArray($controllerDir, true);

            $classmapFiledata = file_get_contents($sourceBasedir . 'include_classmap.tpl');

            $classmapArray = array();
            foreach ($fileListing as $file) {
                $fileExt = \Litpi\Helper::fileExtension($file);
                if ($fileExt == 'php') {
                    $fileparts = explode(DIRECTORY_SEPARATOR, $file);

                    $namespacePart = '';
                    $classpathPart = '';

                    for ($i = 1; $i < count($fileparts); $i++) {
                        if ($i != count($fileparts) - 1) {
                            // exlude final element (filename)
                            $namespacePart .= strtolower($fileparts[$i]) . '\\';
                            $classpathPart .= "'" . $fileparts[$i] . "' . " . '$s' . " . ";
                        } else {
                            // this is filename
                            $namespacePart .= str_replace('.php', '', strtolower($fileparts[$i]));
                            $classpathPart .= "'" . $fileparts[$i] . "'";
                        }
                    }

                    $classmapArray[] = "'$namespacePart' => $classpathPart";

                }
            }

            $classmapFiledata = str_replace(
                '{{CLASSMAP_ARRAY_ELEMENTS}}',
                implode(",\n        ", $classmapArray),
                $classmapFiledata
            );
            $warning[] = 'Replace following PHP scripts to <code>/includes/classmap.php</code> file';

        } else {
            $error[] = 'Can not read controller directory (' . $controllerDir . ')';
        }

        $this->registry->smarty->assign(array(
            'success' => $success,
            'error' => $error,
            'warning' => $warning,
            'classmapFiledata' => $classmapFiledata
        ));

        $contents = $this->registry->smarty->fetch($this->registry->smartyController . 'classmap.tpl');

        $this->registry->smarty->assign(array(
            'pageTitle' => 'Classmap Generating',
            'contents' => $contents
        ));
        $contents = $this->registry->smarty->fetch($this->registry->smartyModule . 'index.tpl');
        $this->registry->response->setContent($contents);
    }

    ####################################################################################################
    ####################################################################################################
    ####################################################################################################

    private function generateValidate($formData, &$error)
    {
        $pass = true;
        $modelPath = '';

        //Validate MODEL DIRECTORY base on NAMESPACE
        if (strlen($formData['fmodulenamespace']) == 0) {
            $pass = false;
            $error[] = 'Model Namespace is required.';
        } elseif ($formData['fmodulenamespace'][0] == '\\') {
            $pass = false;
            $error[] = 'Model Namespace must not start with "\\" character.';
        } else {

            $modelPath = SITE_PATH . str_replace('\\', DIRECTORY_SEPARATOR, $formData['fmodulenamespace']);

            if (!file_exists($modelPath)) {
                $pass = false;
                $error[] = 'Directory contains model not existed. (' . $modelPath . ')';
            } elseif (!is_dir($modelPath)) {
                $pass = false;
                $error[] = 'Model Namespace path is not directory.';
            } elseif (!is_writable($modelPath)) {
                $pass = false;
                $error[] = 'Directory contains model is not writable. Check Permission and CHMOD...';
            } else {

            }
        }

        //Validate valid base model class
        if (strlen($formData['fmodulebaseclass']) == 0) {
            $pass = false;
            $error[] = 'Base class for Model is required.';
        } else {
            //check if baseclass is from rootnamespace
            if ($formData['fmodulebaseclass'][0] == '\\') {
                $tmpBaseClassName = $formData['fmodulebaseclass'];
            } else {
                $tmpBaseClassName = $formData['fmodulenamespace'] . '\\' . $formData['fmodulebaseclass'];
            }

            if (!class_exists($tmpBaseClassName)) {
                $pass = false;
                $error[] = 'Base class for Model is not existed. Check again (' . $tmpBaseClassName . ')';
            }
        }

        //validate MODEL settings
        if (strlen($formData['fmodule']) == 0) {
            $pass = false;
            $error[] = 'Model Class Name is not valid. Please input the Class name';
        }

        if (strlen($formData['ftablealias']) == 0) {
            $pass = false;
            $error[] = 'Table prefix is requirement.';
        }

        foreach ($formData['fprop'] as $k => $v) {
            if (strlen($v) == 0) {
                $pass = false;
                $error[] = 'Model Mapping Key <code>' . $k . '</code> is required.';
            }
        }

        //Validate CONTROLLER DIRECTORY base on NAMESPACE
        if (strlen($formData['fcontrollernamespace']) == 0) {
            $pass = false;
            $error[] = 'Controller Namespace is required.';
        } elseif ($formData['fcontrollernamespace'][0] == '\\') {
            $pass = false;
            $error[] = 'Controller Namespace MUST NOT start with "\\" character.';
        } else {

            $controllerPath = SITE_PATH . 'Controller' . DIRECTORY_SEPARATOR
                . str_replace('\\', DIRECTORY_SEPARATOR, $formData['fcontrollernamespace']);

            if (!file_exists($controllerPath)) {
                $pass = false;
                $error[] = 'Directory contains controller not existed. (' . $modelPath . ')';
            } elseif (!is_dir($controllerPath)) {
                $pass = false;
                $error[] = 'Controller Namespace path is not directory.';
            } elseif (!is_writable($controllerPath)) {
                $pass = false;
                $error[] = 'Directory contains Controller is not writable. Check Permission and CHMOD...';
            } else {

            }
        }

        //validate ADMIN CONTROLLER settings
        if (isset($formData['fadmincontrollertoggler'])) {
            if ($formData['fcontrollernamespace'] == '') {
                $pass = false;
                $error[] = 'Controller Namespace is required.';
            }

            foreach ($formData['flabel'] as $k => $v) {
                if (strlen($v) == 0) {
                    $pass = false;
                    $error[] = 'Admin Language Label Mapping Key <code>' . $k . '</code> is required.';
                }
            }

            foreach ($formData['directories'] as $k => $directory) {
                if (!is_writable($directory) && $k != 'model') {
                    $pass = false;
                    $error[] = 'Directory <code>' . $directory . '</code> is not writable.';
                }
            }
        }

        return $pass;
    }

    /**
     * Return default valid Model Class Name based on table name
     */
    private function getDefaultClassName($table)
    {
        //remove TABLE_PREFIX
        $table = str_replace(TABLE_PREFIX, '', $table);

        //explode to get table parts (if had)
        $parts = explode('_', $table);

        $classname = '';
        foreach ($parts as $part) {
            $classname .= ucfirst($part);
        }

        return $classname;
    }

    /**
     * Return alias from table.
     * default is the first letter of each word in table name. Words are seperated by underscore.
     */
    private function getDefaultTableAlias($table)
    {
        $alias = '';
        $words = explode('_', $table);
        foreach ($words as $word) {
            $alias .= $word[0];
        }

        return $alias;
    }

    /**
     * Create search/replace pattern data to build the final generated files.
     */
    private function getSearchReplaceInfo($formData)
    {

        $s = array();

        ############################################
        ############################################
        ##	MODEL.TPL
        ############################################
        ############################################
        $s['{{MODULE_NAMESPACE}}'] = $formData['fmodulenamespace'];
        $s['{{MODULE_BASECLASS}}'] = $formData['fmodulebaseclass'];
        $s['{{MODULE_PREFIX}}'] = $formData['fmodelclassprefix'];
        $s['{{DB_OBJECT}}'] = isset($formData['fdbobject']) ? $formData['fdbobject'] : 'db';

        $s['{{MODULE}}'] = $formData['fmodelclassprefix'] . $formData['fmodule'];
        $s['{{MODULE_SIMPLIFY}}'] = $formData['fmodule'];
        $s['{{MODULE_LOWER}}'] = strtolower($formData['fmodule']);

        //{{DATECREATED_ASSIGN}}
        $tmp = '';
        foreach ($formData['fprop'] as $k => $v) {
            if ($v == 'datecreated') {
                $tmp = "\$this->datecreated = time();\n\n";

                //add to exclude list to prevent add/edit in TPL
                $formData['fexclude'][$k] = 1;
            }
        }
        $s['{{DATECREATED_ASSIGN}}'] = $tmp;
        //end---

        //{{DATEMODIFIED_ASSIGN}}
        $tmp = '';
        foreach ($formData['fprop'] as $k => $v) {
            if ($v == 'datemodified') {
                $tmp = "\$this->datemodified = time();\n\n";

                //add to exclude list to prevent add/edit in TPL
                $formData['fexclude'][$k] = 1;
            }
        }
        $s['{{DATEMODIFIED_ASSIGN}}'] = $tmp;
        //end---

        //{{PROPERTY}}

        if (isset($formData['fconstantable']) && count($formData['fconstantable']) > 0) {
            foreach ($formData['fprop'] as $k => $v) {

            }
        }

        $tmp = "\n";
        $constantData = array();
        foreach ($formData['fprop'] as $k => $v) {
            $tmp .= "" . '    public $'
                . $v . ' = ' . $this->getDefaultValueFromColumnType($formData['ftypeshort'][$k]) . ';' . "\n";

            //check this is constant
            if (isset($formData['fconstantable'][$k]) && strlen($formData['fconstantable'][$k]) > 0) {
                //Constant format: CONSTANT1:value1:text,CONSTANT2:value2:text2,...
                $constantGroups = explode(',', $formData['fconstantable'][$k]);
                foreach ($constantGroups as $constantGroup) {
                    $constantInfo = explode(':', $constantGroup);
                    if (count($constantInfo) == 3) {
                        $constantData[$k][] = $constantInfo;
                    }
                }
            }
        }

        $tmpConstant = '';
        $tmpFunctionConstantFull = '';

        //append ConstantData before Property define
        if (!empty($constantData)) {
            foreach ($constantData as $k => $constantGroups) {
                //////////////////////////
                //DEFINE CONSTANT
                foreach ($constantGroups as $constantInfo) {
                    $tmpConstant .= "\n    const " . $constantInfo[0] . " = " . $constantInfo[1] . ";";
                }
                $tmpConstant .= "\n";

                //////////////////////////
                // RELATED METHODS FOR CONSTANT
                $filetemplatefunctionConstant = $this->registry->smarty->template_dir
                    . '/' . $this->registry->smartyController
                    . 'generate_format/model_function_constant.tpl';
                if (file_exists($filetemplatefunctionConstant)) {
                    $st = array();

                    $st['{{PROPERTYCONSTANTUCFIRST}}'] = ucfirst($formData['fprop'][$k]);
                    $st['{{PROPERTYCONSTANT}}'] = $formData['fprop'][$k];
                    $st['{{PROPERTYCONSTANT_LIST}}'] = '';
                    $st['{{PROPERTYCONSTANT_GETNAME}}'] = '';
                    $st['{{PROPERTYCONSTANT_CHECK}}'] = '';

                    foreach ($constantGroups as $constantInfo) {
                        $st['{{PROPERTYCONSTANT_LIST}}'] .= "\$output[self::"
                            . $constantInfo[0] . "] = '" . $constantInfo[2] . "';\n        ";

                        $st['{{PROPERTYCONSTANT_GETNAME}}'] .= "case self::" . $constantInfo[0]
                            . ":\n                \$name = '" . $constantInfo[2]
                            . "';\n                break;\n            ";

                        if ($st['{{PROPERTYCONSTANT_CHECK}}'] != '') {
                            $st['{{PROPERTYCONSTANT_CHECK}}'] .= "\n             || ";
                        }
                        $st['{{PROPERTYCONSTANT_CHECK}}'] .= "(\$this->" . $formData['fprop'][$k]
                            . " == self::" . $constantInfo[0]
                            . " && \$name == '" . str_replace(' ', '', strtolower($constantInfo[2])) . "')";
                    }

                    $tmpFunctionConstant = file_get_contents($filetemplatefunctionConstant);

                    if ($tmpFunctionConstant != '') {
                        $tmpFunctionConstant = strtr($tmpFunctionConstant, $st);
                        $tmpFunctionConstant = strtr($tmpFunctionConstant, $s);

                        //Assign for output
                        $tmpFunctionConstantFull .= $tmpFunctionConstant;
                    } else {

                        $exceptionMsgTmp = 'Can not OPEN tpl file for generate related methods for '
                            . 'constant property (Not found file tpl at ' . $filetemplatefunctionConstant . ').';
                        throw new \Exception($exceptionMsgTmp);
                    }
                } else {
                    $exceptionMsgTmp = 'Can not FIND tpl file for generate related methods for'
                        . ' constant property (Not found file tpl at ' . $filetemplatefunctionConstant . ').';
                    throw new \Exception($exceptionMsgTmp);
                }

            }
        }

        $s['{{PROPERTY}}'] = $tmpConstant . $tmp;
        $s['{{FUNCTION_CONSTANT}}'] = $tmpFunctionConstantFull;

        //end---

        $s['{{PRIMARY_FIELD}}'] = $formData['primaryfield'];
        $s['{{PRIMARY_PROPERTY}}'] = $formData['fprop'][$formData['primaryfield']];
        $s['{{TABLE_WITHOUT_PREFIX}}'] = $formData['tableWithoutPrefix'];

        //{{PROPERTY_ADD_LIST}}
        //{{PROPERTY_ADD_QUESTIONMARK}}
        //{{PROPERTY_ADD_BINDING}}
        //{{FUNCTION_GETMAXDISPLAYORDER}}

        $tmpColumn = array();
        $tmpQuestionmark = array();
        $tmpBinding = array();
        $tmpFunctionGetMaxDisplayOrder = '';
        foreach ($formData['fprop'] as $k => $v) {
            if ($k != $formData['primaryfield']) {

                $tmpColumn[] = $k;
                $tmpQuestionmark[] = '?';

                if ($formData['ftypeshort'][$k] == 'int') {
                    if (isset($formData['fipaddressable'][$k])) {
                        $tmpBinding[] = '(int)Helper::getIpAddress(true)';
                    } elseif (isset($formData['fdisplayorderable'][$k])) {
                        $tmpBinding[] = '(int)$this->getMaxDisplayOrder()';

                        $filetemplatefunctionGetMaxDisplayOrder = $this->registry->smarty->template_dir
                            . '/' . $this->registry->smartyController
                            . 'generate_format/model_function_getmaxdisplayorder.tpl';
                        if (file_exists($filetemplatefunctionGetMaxDisplayOrder)) {
                            $st = array();
                            //generate function getMaxDisPlayOrder()
                            $st['{{FUNCTION_DISPLAYORDER_COLUMN}}'] = $k;
                            $st['{{FUNCTION_DISPLAYORDER_GROUPWHERE}}'] = '';
                            $st['{{FUNCTION_DISPLAYORDER_GROUPBINDING}}'] = '';

                            if (isset($formData['fdisplayordergroup'][$k])
                                && $formData['fdisplayordergroup'][$k] != ''
                            ) {
                                $st['{{FUNCTION_DISPLAYORDER_GROUPWHERE}}'] = 'WHERE '
                                    . $formData['fdisplayordergroup'][$k] . ' = ?';
                                $st['{{FUNCTION_DISPLAYORDER_GROUPBINDING}}'] = '$this->'
                                    . $formData['fprop'][$formData['fdisplayordergroup'][$k]];
                            }

                            $tmpFunctionGetMaxDisplayOrder = file_get_contents($filetemplatefunctionGetMaxDisplayOrder);

                            if ($tmpFunctionGetMaxDisplayOrder != '') {
                                $tmpFunctionGetMaxDisplayOrder = strtr($tmpFunctionGetMaxDisplayOrder, $st);
                                $tmpFunctionGetMaxDisplayOrder = strtr($tmpFunctionGetMaxDisplayOrder, $s);
                            } else {
                                $exceptionMsgTmp = 'Can not OPEN tpl file for generate function getMaxDisplayOrder()'
                                    . ' (Not found file tpl at ' . $filetemplatefunctionGetMaxDisplayOrder . ').';
                                throw new \Exception($exceptionMsgTmp);
                            }
                        } else {
                            $exceptionMsgTmp = 'Can not FIND tpl file for generate function getMaxDisplayOrder()'
                                . ' (Not found file tpl at ' . $filetemplatefunctionGetMaxDisplayOrder . ').';
                            throw new \Exception($exceptionMsgTmp);
                        }

                    } else {
                        $tmpBinding[] = '(int)$this->' . $v;
                    }
                } elseif ($formData['ftypeshort'][$k] == 'float') {
                    $tmpBinding[] = '(float)$this->' . $v;
                } else {
                    $tmpBinding[] = '(string)$this->' . $v;
                }
            }

        }

        $s['{{PROPERTY_ADD_LIST}}'] = implode(",\n                    ", $tmpColumn);
        $s['{{PROPERTY_ADD_QUESTIONMARK}}'] = implode(', ', $tmpQuestionmark);
        $s['{{PROPERTY_ADD_BINDING}}'] = implode(",\n            ", $tmpBinding);
        $s['{{FUNCTION_GETMAXDISPLAYORDER}}'] = $tmpFunctionGetMaxDisplayOrder;

        //end---

        //{{PROPERTY_UPDATE_LIST}}
        //{{PROPERTY_UPDATE_BINDING}}
        $tmpColumn = array();
        $tmpBinding = array();
        foreach ($formData['fprop'] as $k => $v) {
            if ($k != $formData['primaryfield'] && $v != 'datecreated' && !isset($formData['fipaddressable'][$k])) {

                $tmpColumn[] = $k . ' = ?';
                if ($formData['ftypeshort'][$k] == 'int') {
                    $tmpBinding[] = '(int)$this->' . $v;
                } elseif ($formData['ftypeshort'][$k] == 'float') {
                    $tmpBinding[] = '(float)$this->' . $v;
                } else {
                    $tmpBinding[] = '(string)$this->' . $v;
                }

            }
        }
        $s['{{PROPERTY_UPDATE_LIST}}'] = implode(",\n                    ", $tmpColumn);
        $s['{{PROPERTY_UPDATE_BINDING}}'] = implode(",\n            ", $tmpBinding);
        //end---

        //{{PROPERTY_ASSIGN_DATA_THIS}}
        //{{PROPERTY_ASSIGN_DATA_CLASS}}
        $tmp = '';
        $tmp2 = '';
        foreach ($formData['fprop'] as $k => $v) {
            //Custom assign for IP ADDRESS FIELD
            if (isset($formData['fipaddressable'][$k])) {
                $tmp .= '$this->' . $v . ' = (string) long2ip($row[\'' . $k . '\']);' . "\n        ";
                $tmp2 .= '$my' . $formData['fmodule'] . '->' . $v
                    . ' = (string) long2ip($row[\'' . $k . '\']);' . "\n            ";
            } else {
                $tmp .= '$this->' . $v . ' = (' . $formData['ftypeshort'][$k] . ')$row[\'' . $k . '\'];' . "\n        ";
                $tmp2 .= '$my' . $formData['fmodule'] . '->' . $v
                    . ' = (' . $formData['ftypeshort'][$k] . ')$row[\'' . $k . '\'];' . "\n            ";
            }

        }
        $s['{{PROPERTY_ASSIGN_DATA_THIS}}'] = $tmp;
        $s['{{PROPERTY_ASSIGN_DATA_CLASS}}'] = $tmp2;
        //end---

        $s['{{TABLE_ALIAS_NODOT}}'] = $formData['ftablealias'];
        $s['{{TABLE_ALIAS_DOT}}'] = $formData['ftablealias'] . '.';

        //{{MODULE_FILTERABLE}}
        $filterable = '';
        foreach ($formData['fprop'] as $k => $v) {
            if (isset($formData['ffilterable'][$k])) {
                $defaultValue = $this->getDefaultValueFromColumnType($formData['ftypeshort'][$k]);

                if ($formData['ftypeshort'][$k] == 'int') {
                    //custom for IP Address search
                    if (isset($formData['fipaddressable'][$k])) {
                        $a = '!= \'\'';
                        $assign = "(int)ip2long(\$formData['f{$v}'])";
                    } else {
                        $a = '> 0';
                        $assign = "(int)\$formData['f{$v}']";
                    }
                } elseif ($formData['ftypeshort'][$k] == 'float') {
                    $a = '> 0';
                    $assign = "(float)\$formData['f{$v}']";
                } else {
                    $a = '!= \'\'';
                    $assign = "Helper::unspecialtext((string)\$formData['f{$v}'])";
                }

                if ($formData['ftypeshort'][$k] == 'int') {
                    $b = '(int)';
                } elseif ($formData['ftypeshort'][$k] == 'float') {
                    $b = '(float)';
                } else {
                    $b = '(string)';
                }

                $filterable .= "\n        if (\$formData['f$v'] $a) {\n            "
                    . "\$whereString .= (\$whereString != '' ? ' AND ' : '') . "
                    . "'{$formData['ftablealias']}.$k = ? ';\n"
                    . "            \$bindParams[] = {$assign};\n"
                    . "        }\n";


            }
        }
        $s['{{MODULE_FILTERABLE}}'] = $filterable;
        //end---

        //{{MODULE_SEARCHABLETEXT}}
        $searchabletext = array();
        foreach ($formData['fprop'] as $k => $v) {
            if (isset($formData['fsearchabletext'][$k])) {
                $searchabletext[] = array($k, $v);
            }
        }
        $searchabletextString = '';

        if (count($searchabletext) > 0) {
            $searchabletextString .= "\n        if (strlen(\$formData['fkeywordFilter']) > 0) "
                . "{\n            \$formData['fkeywordFilter'] = "
                . "Helper::unspecialtext(\$formData['fkeywordFilter']);\n";

            $combinegroup = '';
            $combinegroupBindParams = '';

            for ($i = 0; $i < count($searchabletext); $i++) {
                $searchabletextString .= "";

                if ($i == 0) {
                    $searchabletextString .= "\n            " . 'if';
                } else {
                    $searchabletextString .= ' elseif';
                }
                $searchabletextString .= " (\$formData['fsearchKeywordIn'] == '" . $searchabletext[$i][1]
                    . "') {\n                \$whereString .= (\$whereString != '' ? ' AND ' : '') . '"
                    . $formData['ftablealias'] . "." . $searchabletext[$i][0]
                    . " LIKE ? ';\n"
                    . "                \$bindParams[] = '%' . \$formData['fkeywordFilter'] . '%';\n\n"
                    . "            }";

                $combinegroup[] = "(" . $formData['ftablealias'] . "." . $searchabletext[$i][0]
                    . " LIKE ?)";
                $combinegroupBindParams .= "\n                "
                    . "\$bindParams[] = '%' . \$formData['fkeywordFilter'] . '%';";
            }

            //else, combine all

            $searchabletextString .= " else {\n                \$"
                . "whereString .= (\$whereString != '' ? ' AND ' : '') . '( "
                . implode("\n                    OR ", $combinegroup) . " )';\n$combinegroupBindParams\n            }";

            $searchabletextString .= "\n        }";

        }

        $s['{{MODULE_SEARCHABLETEXT}}'] = $searchabletextString;
        //end---

        //{{MODULE_SORTABLE}}
        $sortable = array();
        foreach ($formData['fprop'] as $k => $v) {
            if (isset($formData['fsortable'][$k])) {
                $sortable[] = array($k, $v);
            }
        }

        $sortableString = '';
        for ($i = 0; $i < count($sortable); $i++) {
            if ($i > 0) {

                $sortableString .= ' else';
            }
            $sortableString .= "if (\$sortby == '" . $sortable[$i][1]
                . "') {\n            \$orderString = '" . $sortable[$i][0] . " ' . \$sorttype;\n        }";
        }

        if (count($sortable) > 0) {
            $sortableString .= " else {\n            ";
        } else {
            $sortableString .= "\n        ";
        }
        $sortableString .= "\$orderString = '" . $formData['primaryfield'] . " ' . \$sorttype;\n        }";

        $s['{{MODULE_SORTABLE}}'] = $sortableString;
        //end---

        ############################################
        ############################################
        ##	CONTROLLER_ADMIN.TPL
        ############################################
        ############################################
        //{{CONTROLLERGROUP}}
        if ($formData['fcontrollerrecordperpage'] <= 0) {
            $formData['fcontrollerrecordperpage'] = 30;
        }
        $s['{{CONTROLLER_RECORDPERPAGE}}'] = (int)$formData['fcontrollerrecordperpage'];
        $s['{{CONTROLLER_ICONCLASS}}'] = $formData['fcontrollericonclass'];
        $s['{{CONTROLLER_CLASS}}'] = $formData['fcontrollerclass'];
        $s['{{CONTROLLER_NAMESPACE}}'] = $formData['fcontrollernamespace'];
        $s['{{CONTROLLERGROUP}}'] = $formData['fcontrollergroup'];

        //{{FILTERABLE_ARRAY}}
        //{{FILTERABLE_GET_ARGUMENTS}}
        //{{FILTERABLE_APPLY_FORMDATA}}
        $tmpArray = array();
        $tmpArgs = '';
        $tmpApply = '';
        foreach ($formData['ffilterable'] as $k => $v) {
            $defaultValue = $this->getDefaultValueFromColumnType($formData['ftypeshort'][$k]);

            $prop = $formData['fprop'][$k];

            if ($formData['ftypeshort'][$k] == 'int') {
                $typename = '(int)';
            } elseif ($formData['ftypeshort'][$k] == 'float') {
                $typename = '(float)';
            } else {
                $typename = '(string)';
            }

            $tmpArgs .= "\n        \$" . $prop . "Filter = " . $typename . "\$this->postBag->get('" . $prop . "');";

            if ($defaultValue === 0) {
                $applyCondition = '> 0';
            } else {
                $applyCondition = '!= ""';
            }

            $tmpApply .= "\n\n        if (\$" . $prop . "Filter " . $applyCondition
                . ") {\n            \$formData['f" . $prop . "'] = \$" . $prop . "Filter;\n        }";
            $tmpArray[] = "'" . $prop . "'";

        }
        $s['{{FILTERABLE_ARRAY}}'] = implode(",\n            ", $tmpArray);
        $s['{{FILTERABLE_GET_ARGUMENTS}}'] = $tmpArgs;
        $s['{{FILTERABLE_APPLY_FORMDATA}}'] = $tmpApply;
        //end---

        //{{SEARCHABLETEXT_GET_ARGUMENTS}}
        //{{SEARCHABLETEXT_APPLY_FORMDATA}}
        $tmpArgs = '';
        $tmpApply = '';
        if (count($formData['fsearchabletext']) > 0) {
            $tmpArgs = "\n        \$keywordFilter = Helper::plaintext(\$this->postBag->get('keyword'));"
                . "\n        \$searchKeywordIn = (string)\$this->postBag->get('searchin');";

            //build if conditional for searchkeyword in
            $i = 0;
            foreach ($formData['fsearchabletext'] as $k => $v) {

                if ($i > 0) {
                    $tmpApply .= ' else';
                } else {
                    $tmpApply .= "\n            ";
                }
                $tmpApply .= "if (\$searchKeywordIn == '" . $formData['fprop'][$k] . "') {\n            }";
                $i++;
            }

            $tmpApply = "\n        if (strlen(\$keywordFilter) > 0) {\n" . $tmpApply
                . "\n            \$formData['fkeyword'] = \$formData['fkeywordFilter'] = \$keywordFilter;"
                . "\n            \$formData['fsearchin'] = \$formData['fsearchKeywordIn'] = \$searchKeywordIn;"
                . "\n        }";
        }
        $s['{{SEARCHABLETEXT_GET_ARGUMENTS}}'] = $tmpArgs;
        $s['{{SEARCHABLETEXT_APPLY_FORMDATA}}'] = $tmpApply;
        //end---

        //{{CONSTANT_CONTROLLER_ASSIGN_ADD}}
        $tmp = '';
        if (!empty($constantData)) {
            foreach ($constantData as $k => $constantGroups) {
                $tmp .= "\n            '" . $formData['fprop'][$k] . "Options' => \\" . $formData['fmodulenamespace']
                    . "\\" . $formData['fmodule'] . "::get" . ucfirst($formData['fprop'][$k]) . "List(),";
            }
        }
        $s['{{CONSTANT_CONTROLLER_ASSIGN_ADD}}'] = $tmp;
        //end---

        //{{CONSTANT_CONTROLLER_ASSIGN_EDIT}}
        $tmp = '';
        if (!empty($constantData)) {
            foreach ($constantData as $k => $constantGroups) {
                $tmp .= "\n                '" . $formData['fprop'][$k]
                    . "Options' => \\" . $formData['fmodulenamespace']
                    . "\\" . $formData['fmodule'] . "::get" . ucfirst($formData['fprop'][$k]) . "List(),";
            }
        }
        $s['{{CONSTANT_CONTROLLER_ASSIGN_EDIT}}'] = $tmp;
        //end---

        //{{{{ADD_ASSIGN_PROPERTY}}}}
        $tmp = '';
        if (!is_array($formData['fexclude'])) {
            $formData['fexclude'] = array();
        }
        foreach ($formData['fprop'] as $k => $v) {
            if (!isset($formData['fexclude'][$k]) && $k != $formData['primaryfield']) {

                $typenameCloseBrace = '';
                if ($formData['ftypeshort'][$k] == 'int') {
                    $typename = '(int)';
                } elseif ($formData['ftypeshort'][$k] == 'float') {
                    $typename = '(float)';
                } else {
                    $typename = 'Helper::plaintext(';
                    $typenameCloseBrace = ')';
                }

                $tmp .= "\n                    \$my" . $formData['fmodule'] . "->" . $v
                    . " = $typename\$formData['f" . $v . "']$typenameCloseBrace;";
            }
        }
        $s['{{ADD_ASSIGN_PROPERTY}}'] = $tmp;
        //end---

        //{{EDIT_FORMDATA_INIT}}
        $tmp = '';
        foreach ($formData['fprop'] as $k => $v) {
            $tmp .= "\n            \$formData['f" . $v . "'] = \$my" . $formData['fmodule'] . "->" . $v . ";";
        }
        $s['{{EDIT_FORMDATA_INIT}}'] = $tmp;
        //end--


        //{{JSON_DATA_ASSIGN}}
        $tmp = '';
        foreach ($formData['fprop'] as $k => $v) {
            if (!isset($formData['fexcludeindex'][$k])) {

                if (isset($constantData[$k])) {
                    $tmp .= "\n                '" . $v . "' => (string)\$my"
                        . $formData['fmodule'] . "->get" . ucfirst($v) . "Name(),";
                } elseif ($v == 'datecreated' || $v == 'datemodified') {
                    $tmp .= "\n                '" . $v . "' => (string)\$my" . $formData['fmodule'] . "->" . $v
                        . " > 0 ? date('d/m/Y', \$my" . $formData['fmodule'] . "->" . $v . ") : '-',";
                } else {
                    if ($formData['ftypeshort'][$k] == 'int') {
                        if ($v == 'ipaddress') {
                            $typename = '(string)';
                        } else {
                            $typename = '(int)';
                        }
                    } elseif ($formData['ftypeshort'][$k] == 'float') {
                        $typename = '(float)';
                    } else {
                        $typename = '(string)';
                    }
                    $tmp .= "\n                '" . $v . "' => $typename\$my" . $formData['fmodule'] . "->" . $v . ",";
                }
            }
        }
        $s['{{JSON_DATA_ASSIGN}}'] = $tmp;
        //end--

        //{{{{EDIT_ASSIGN_PROPERTY}}}}
        $tmp = '';
        if (!is_array($formData['fexclude'])) {
            $formData['fexclude'] = array();
        }
        foreach ($formData['fprop'] as $k => $v) {
            if (!isset($formData['fexclude'][$k]) && $k != $formData['primaryfield']) {
                $typenameCloseBrace = '';
                if ($formData['ftypeshort'][$k] == 'int') {
                    $typename = '(int)';
                } elseif ($formData['ftypeshort'][$k] == 'float') {
                    $typename = '(float)';
                } else {
                    $typename = 'Helper::plaintext(';
                    $typenameCloseBrace = ')';
                }

                $tmp .= "\n                        \$my" . $formData['fmodule'] . "->"
                    . $v . " = $typename\$formData['f"
                    . $v . "']$typenameCloseBrace;";
            }
        }
        $s['{{EDIT_ASSIGN_PROPERTY}}'] = $tmp;
        //end---

        //{{ADD_VALIDATOR}}
        //{{EDIT_VALIDATOR}}
        $tmp = '';
        foreach ($formData['fvalidating'] as $k => $v) {
            if ($v == 'notempty') {
                $tmp .= "\n\n        if (\$formData['f"
                    . $formData['fprop'][$k]
                    . "'] == '') {\n            \$error[] = \$this->registry->lang['controller']['err"
                    . ucfirst($formData['fprop'][$k])
                    . "Required'];\n            \$pass = false;\n        }";

            } elseif ($v == 'greaterthanzero') {
                $tmp .= "\n\n        if (\$formData['f" . $formData['fprop'][$k]
                    . "'] <= 0) {\n            \$error[] = \$this->registry->lang['controller']['err"
                    . ucfirst($formData['fprop'][$k])
                    . "MustGreaterThanZero'];\n            \$pass = false;\n        }";

            } elseif ($v == 'email') {
                $tmp .= "\n\n        if (Helper::validateEmail(\$formData['f" . $formData['fprop'][$k]
                    . "'])) {\n            \$error[] = \$this->registry->lang['controller']['err"
                    . ucfirst($formData['fprop'][$k])
                    . "InvalidEmail'];\n            \$pass = false;\n        }";
            }
        }
        $s['{{ADD_VALIDATOR}}'] = $s['{{EDIT_VALIDATOR}}'] = $tmp;
        //end--

        ############################################
        ############################################
        ##	LANGUAGE_ADMIN.TPL
        ############################################
        ############################################
        //{{FORM_FIELD_LABEL}}
        $tmp = '';
        foreach ($formData['flabel'] as $k => $v) {
            $tmp .= "\n\t" . '<lines name="label' . ucfirst($formData['fprop'][$k])
                . '" descr=""><![CDATA[' . $v . ']]></lines>';
        }
        //searchable
        if (count($formData['fsearchabletext']) > 0) {
            $tmp .= "\n\t" . '<lines name="formKeywordLabel" descr=""><![CDATA[Keyword]]></lines>';
            $tmp .= "\n\t" . '<lines name="formKeywordInLabel" descr=""><![CDATA[- Search keyword in -]]></lines>';
        }

        $s['{{FORM_FIELD_LABEL}}'] = $tmp;
        //end---

        //{{FORM_FIELD_VALIDATING}}
        $tmp = '';
        foreach ($formData['fvalidating'] as $k => $v) {
            if ($v == 'notempty') {
                $tmp .= "\n\t" . '<lines name="err'
                    . ucfirst($formData['fprop'][$k])
                    . 'Required" descr=""><![CDATA[' . $formData['flabel'][$k] . ' is required.]]></lines>';
            } elseif ($v == 'greaterthanzero') {
                $tmp .= "\n\t" . '<lines name="err'
                    . ucfirst($formData['fprop'][$k])
                    . 'MustGreaterThanZero" descr=""><![CDATA[' . $formData['flabel'][$k]
                    . ' must be greater than zero.]]></lines>';
            } elseif ($v == 'email') {
                $tmp .= "\n\t" . '<lines name="err'
                    . ucfirst($formData['fprop'][$k])
                    . 'InvalidEmail" descr=""><![CDATA[' . $formData['flabel'][$k]
                    . ' is not valid format.]]></lines>';
            }
        }
        $s['{{FORM_FIELD_VALIDATING}}'] = $tmp;
        //END---

        ############################################
        ############################################
        ##	CONTROLLER_ADMIN_INDEX.TPL
        ############################################
        ############################################
        //{{FIELD_TABLE_HEAD}},
        $fieldHead = '';
        $fieldData = '';
        $indexColumnSpanCount = 2;  //Counting for Checkbox column & Action Column

        foreach ($formData['flabel'] as $k => $v) {
            $prop = $formData['fprop'][$k];

            if (!isset($formData['fexcludeindex'][$k])) {
                $indexColumnSpanCount++;
                $headlabel = '{$lang.controller.label' . ucfirst($prop) . '}';

                //check if filter
                if (isset($formData['fsortable'][$k])) {
                    $sortableClass = 'formfilterth is-sortable';
                } else {
                    $sortableClass = 'formfilterth';
                }

                $fieldHead .= "\n\t\t\t\t\t<th class=\"$sortableClass\" id=\"$prop\">" . $headlabel . "</th>";
            }
        }
        $s['{{FIELD_TABLE_HEAD}}'] = $fieldHead;
        $s['{{FIELD_TABLE_HEADSPAN}}'] = $indexColumnSpanCount;
        //end---

        //{{FILTERABLE_CONTROLGROUP}}
        //{{FILTERABLE_CONTROLGROUP_MORE}}
        $tmpControl = '';
        $tmpControlMore = '';
        $tmpJs = '';
        foreach ($formData['ffilterable'] as $k => $v) {
            $prop = $formData['fprop'][$k];
            $tmpControl .= '<option value="' . $prop . '">{$lang.controller.label'
                . ucfirst($prop) . '}</option>' . "\n";
            //If constant field, must be select box
            if (isset($constantData[$k])) {
                $tmpControlMore .= '
                    <select id="filter_' . $prop . '_more" class="filter_more hideinit input-sm">
                        {html_options options=$' . $prop . 'Options selected=$formData.f' . $prop . '}
                    </select>' . "\n";
            } else {
                $tmpControlMore .= '<input type="text" id="filter_'
                    . $prop . '_more" value="{$formData.f'
                    . $prop . '}" class="filter_more hideinit input-sm" />' . "\n";
            }
        }
        $s['{{FILTERABLE_CONTROLGROUP}}'] = $tmpControl;
        $s['{{FILTERABLE_CONTROLGROUP_MORE}}'] = $tmpControlMore;
        //end---

        //{{SEARCHABLETEXT_CONTROLGROUP}}
        //{{SEARCHABLETEXT_INPUT_PLACEHOLDER}}
        $tmpControl = '';
        $tmpPlaceholder = array();
        if (count($formData['fsearchabletext']) > 0) {
            $keywordinoption = '';
            foreach ($formData['fsearchabletext'] as $k => $v) {
                $prop = $formData['fprop'][$k];
                $keywordinoption .= "\n\t\t\t\t\t" . '<option value="'
                    . $prop . '" {if $formData.fsearchin eq "' . $prop
                    . '"}selected="selected"{/if}>{$lang.controller.label' . ucfirst($prop) . '}</option>';
                $tmpPlaceholder[] = '{$lang.controller.label' . ucfirst($prop) . '}';
            }

            $tmpControl = '{$lang.controller.formKeywordLabel}:' . "\n\t\t\t\t" .
                '<input type="text" name="fkeyword" id="fkeyword" '
                . ' size="20" value="{$formData.fkeyword|@htmlspecialchars}" class="" />' . "\n\t\t\t\t" .
                '<select name="fsearchin" id="fsearchin">' . "\n\t\t\t\t\t" .
                '<option value="">{$lang.controller.formKeywordInLabel}</option>' . $keywordinoption .
                '</select>';
        }
        $s['{{SEARCHABLETEXT_CONTROLGROUP}}'] = $tmpControl;
        $s['{{SEARCHABLETEXT_INPUT_PLACEHOLDER}}'] = implode(', ', $tmpPlaceholder);
        //end---

        ############################################
        ############################################
        ##	CONTROLLER_ADMIN_ADD.TPL
        ##	CONTROLLER_ADMIN_EDIT.TPL
        ############################################
        ############################################
        //{{FORM_ADD_CONTROLGROUP}}, {{FORM_EDIT_CONTROLGROUP}}
        $tmp = '';

        foreach ($formData['fprop'] as $k => $v) {
            if (!isset($formData['fexclude'][$k]) && $k != $formData['primaryfield']) {
                if (isset($formData['fvalidating'][$k]) && $formData['fvalidating'][$k] != 'notneed') {
                    $requireString = ' <span class="star_require">*</span>';
                } else {
                    $requireString = '';
                }

                //detect correct input
                $colMdWidth = 6;
                if ($v == 'enable') {
                    $inputstring = '<select class="input-mini" name="fenable" id="fenable"><option value="1">'
                        . '{$lang.default.formYesLabel}</option><option value="0" '
                        . ' {if $formData.fenable == \'0\'}selected="selected"{/if}>'
                        . '{$lang.default.formNoLabel}</option></select>';
                } elseif (isset($constantData[$k])) {
                    //constant must used in Select box
                    $inputstring = '<select class="" name="f' . $v . '" id="f' . $v . '">{html_options options=$'
                        . $v . 'Options selected=$formData.f' . $v . '}</select>';
                } elseif ($this->getDefaultValueFromColumnType($formData['ftypeshort'][$k]) === 0) {
                    $inputstring = '<input type="text" name="f' . $v . '" id="f' . $v . '" value="{$formData.f' . $v
                        . '|@htmlspecialchars}" class="form-control">';
                } else {
                    //detect varchar 512
                    if (strpos($formData['ftype'][$k], 'text') !== false
                        || preg_replace('/[^0-9]/', '', $formData['ftype'][$k]) > 255
                    ) {
                        $colMdWidth = 12;
                        $inputstring = '<textarea name="f' . $v . '" id="f' . $v
                            . '" rows="5" class="">{$formData.f' . $v . '}</textarea>';
                    } else {
                        $inputstring = '<input type="text" name="f' . $v . '" id="f' . $v
                            . '" value="{$formData.f' . $v . '|@htmlspecialchars}" class="form-control">';
                    }
                }

                $tmp .= "\n\n\t" . '<div class="col-md-' . $colMdWidth . ' ssb clear inner-left">' . "\n\t\t"
                    . '<label for="f' . $v . '">{$lang.controller.label'
                    . ucfirst($formData['fprop'][$k]) . '}' . $requireString . '</label>' . "\n\t\t" . ''
                    . $inputstring . '' . "\n\t" . '</div>';
            }
        }
        $s['{{FORM_ADD_CONTROLGROUP}}'] = $s['{{FORM_EDIT_CONTROLGROUP}}'] = $tmp;

        //end---


        return array('search' => array_keys($s), 'replace' => array_values($s));
    }

    private function getDefaultValueFromColumnType($typeshort)
    {
        $defaultValue = "''";

        if ($typeshort == 'int'
            || $typeshort == 'float'
            || $typeshort == 'double'
            || $typeshort == 'decimal'
            || $typeshort == 'real'
        ) {
            $defaultValue = 0;
        }

        return $defaultValue;
    }

    /**
     * Only support 3 type, Int, float, string
     */
    private function getColumnTypeShort($type)
    {
        $type = strtolower($type);

        if (stripos($type, 'int') !== false) {
            $typeshort = 'int';
        } elseif (in_array($type, array('decimal', 'float', 'double', 'real'))) {
            $typeshort = 'float';
        } else {
            $typeshort = 'string';
        }

        return $typeshort;
    }
}
