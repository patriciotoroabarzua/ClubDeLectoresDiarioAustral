<?php

/******************************************
 *
 *    Class page_class.php
 *
 *    Page will handle the session ( if there is one ), the insertion of content into a template and the display of the resulting string
 *
 *    It would be good to work out a way where page looks in it's template and discovers which fields it needs and then goes and gets them from it's elements.
 *    Maybe not.  But page will at least be able to look at the fileds it has set and return an error message stating which fields are still missing.
 *    Included in the content are the dynamic links that will be needed.  Page will know that a field is within a Block be counting the fields values.
 *
 *********************************************************/


class page_class
{
    // Attributes

    // filehandle, filename of template
    var $template_file;

    // actual template object
    var $template_object;

    // filehandle for template - ensure incremented after everytime it's used
    var $filehandle;

    // output string - BIG ASS STRING
    var $output;

    // ie http://patrick/lamps/
    var $base_url;

    // ie /var/www/cart/templates
    var $path_to_templates;

    // ie catalog.html
    var $self;

    /*********************************************************************************
     * main data structures
     *
     * fields[] holds the content that will replace the {fields} in $template_file
     * fields[] is one dimensional
     *
     * blocks holds the content that will replace the {fields} contained in a <!-- Block -->
     * blocks is multi-dimensional:
     *
     * blocks[] = {BlockName1 => { {item_name=>"lava lamp", item_price=>"45.25", link_delete=>"cart.html?action=remove&item=34"},
     *                             {item_name=>"reading_lamp", ... }, ... },
     *            {BlockName2 => { {recomen=>"book1", ... }, ... , },
     *              ... ,}
     *             }
     *
     * Implies that template_file must know about BlockName in any object
     *
     **********************************************************************************/

    var $fields;
    var $blocks;

    // session ID is optional
    var $session;

    /****************************************************************
     *
     *
     *   Methods
     *
     *
     ***************************************************************/

    // Constructor
    function page_class($template_file, $path_to_templates = ".")
    {

        // Initalize properties
        if(isset($caller)){
        $this->self = $caller;
        }
        else{
        $this->self ="";
        }
        //$this->self = $caller;

        if (substr($path_to_templates, -1) == '/')
        {
            $path_to_templates = substr($path_to_templates, 0, strlen($path_to_templates)-1);
        }
        $this->path_to_templates = $path_to_templates;
        $this->filehandle = "a";
        $this->template_file = $template_file;

        $this->template_object = new Template($path_to_templates);
        //echo "A page was born today<BR>";

    }





    function add_all($fields, $blocks = NULL)
    {
        $this->template_object->set_file($this->filehandle, $this->template_file);

        if ($blocks)
        {
            // add blocks
            foreach($blocks as $blockname=>$entity)
            {
                //echo "<B>$blockname</B>";
                  $this->template_object->set_block($this->filehandle, $blockname, "X".$blockname);

                if ($entity != "") 
                {
                    if(is_array($entity))
                    foreach($entity as $item)
                    {
                    // get each field in block
                            if(is_Array($item))
                            foreach( $item as $fieldname=>$fieldvalue )
                            {
                                $fieldvalue = str_replace( '{', '/***llave***/',$fieldvalue);
                                $this->template_object->set_var( $fieldname, $fieldvalue);
                            }
                            $this->template_object->Parse("X".$blockname, $blockname, true);
                    }
                 }
             }
        }


        // check if there are any fields
        if ($fields)
        {
            // add fields
            foreach($fields as $key=>$value)
            {
                //echo "value:".$fields[$key];
                $value = str_replace( '{', '/***llave***/',$value);
                $this->template_object->set_var($key, $value);
            }

        }
        $this->template_object->Parse($this->output, $this->filehandle, true);
        $this->filehandle .= "a";

    }


    function display_output()
    {
        $this->template_object->p($this->output);
    }
    
    function get_output()
        {
        return ($this->template_object->get($this->output));
        }
}





#############################################################################
#############################################################################
#############################################################################




/*
 * Session Management for PHP3
 *
 * (C) Copyright 1999 NetUSE GmbH
 *                    Kristian Koehntopp
 *
 * $Id: template.inc,v 1.4.2.1 2000/03/23 11:24:00 kk Exp $
 *
 */ 

class Template {
  var $classname = "Template";

  /* if set, echo assignments */
  var $debug     = false;

  /* $file[handle] = "filename"; */
  var $file  = array();

  /* relative filenames are relative to this pathname */
  var $root   = "";

  /* $varkeys[key] = "key"; $varvals[key] = "value"; */
  var $varkeys = array();
  var $varvals = array();

  /* "remove"  => remove undefined variables
   * "comment" => replace undefined variables with comments
   * "keep"    => keep undefined variables
   */
  var $unknowns = "remove";
  
  /* "yes" => halt, "report" => report error, continue, "no" => ignore error quietly */
  var $halt_on_error  = "yes";
  
  /* last error message is retained here */
  var $last_error     = "";


  /***************************************************************************/
  /* public: Constructor.
   * root:     template directory.
   * unknowns: how to handle unknown variables.
   */
  function Template($root = ".", $unknowns = "remove") {
    $this->set_root($root);
    $this->set_unknowns($unknowns);
  }

  /* public: setroot(pathname $root)
   * root:   new template directory.
   */  
  function set_root($root) {
    if (!is_dir($root)) {
      $this->halt("set_root: $root is not a directory.");
      return false;
    }
    
    $this->root = $root;
    return true;
  }

  /* public: set_unknowns(enum $unknowns)
   * unknowns: "remove", "comment", "keep"
   *
   */
  function set_unknowns($unknowns = "keep") {
    $this->unknowns = $unknowns;
  }

  /* public: set_file(array $filelist)
   * filelist: array of handle, filename pairs.
   *
   * public: set_file(string $handle, string $filename)
   * handle: handle for a filename,
   * filename: name of template file
   */
  function set_file($handle, $filename = "") {
    if (!is_array($handle)) {
      if ($filename == "") {
        $this->halt("set_file: For handle $handle filename is empty.");
        return false;
      }
      $this->file[$handle] = $this->filename($filename);
    } else {
      reset($handle);
      while(list($h, $f) = each($handle)) {
        $this->file[$h] = $this->filename($f);
      }
    }
  }

  /* public: set_block(string $parent, string $handle, string $name = "")
   * extract the template $handle from $parent, 
   * place variable {$name} instead.
   */
  function set_block($parent, $handle, $name = "") {
    if (!$this->loadfile($parent)) {
      $this->halt("subst: unable to load $parent.");
      return false;
    }
    if ($name == "")
      $name = $handle;

    $str = $this->get_var($parent);
    $reg = "/<!--\s+BEGIN $handle\s+-->(.*)<!--\s+END $handle\s+-->/sm";
    preg_match_all($reg, $str, $m);

//echo $str."--\n";
    $str = preg_replace($reg, "{"."{$name}"."}", $str);
//echo $str."----\n";
    $m[1][0] = (isset($m[1][0]))?$m[1][0]:"";
    $this->set_var($handle, $m[1][0]);
    $this->set_var($parent, $str);
  }
  
  /* public: set_var(array $values)
   * values: array of variable name, value pairs.
   *
   * public: set_var(string $varname, string $value)
   * varname: name of a variable that is to be defined
   * value:   value of that variable
   */
  function set_var($varname, $value = "") {
    if (!is_array($varname)) {
      if (!empty($varname))
      {
        if ($this->debug) print "scalar: set *$varname* to *$value*<br>\n";
      }
      $this->varkeys[$varname] = "/".$this->varname($varname)."/";

      //    Transformo los caracteres defectuosos
      $value = str_replace('$', '/***peso***/', $value);


      $this->varvals[$varname] = $value;
    } else {
      reset($varname);
      while(list($k, $v) = each($varname)) {
        if (!empty($k))
          if ($this->debug) print "array: set *$k* to *$v*<br>\n";
          $this->varkeys[$k] = "/".$this->varname($k)."/";
          $this->varvals[$k] = $v;
      }
    }
  }

  /* public: subst(string $handle)
   * handle: handle of template where variables are to be substituted.
   */
  function subst($handle) {
    if (!$this->loadfile($handle)) {
      $this->halt("subst: unable to load $handle.");
      return false;
    }

    $str = $this->get_var($handle);
    $str = @preg_replace($this->varkeys, $this->varvals, $str);
    return $str;
  }
  
  /* public: psubst(string $handle)
   * handle: handle of template where variables are to be substituted.
   */
  function psubst($handle) {
    print $this->subst($handle);
    
    return false;
  }

  /* public: parse(string $target, string $handle, boolean append)
   * public: parse(string $target, array  $handle, boolean append)
   * target: handle of variable to generate
   * handle: handle of template to substitute
   * append: append to target handle
   */
  function parse($target, $handle, $append = false) {
    if (!is_array($handle)) {
      $str = $this->subst($handle);
      if ($append) {
        $this->set_var($target, $this->get_var($target) . $str);
      } else {
        $this->set_var($target, $str);
      }
    } else {
      reset($handle);
      while(list($i, $h) = each($handle)) {
        $str = $this->subst($h);
        $this->set_var($target, $str);
      }
    }
    
    return $str;
  }
  
  function pparse($target, $handle, $append = false) {
    print $this->parse($target, $handle, $append);
    return false;
  }
  
  /* public: get_vars()
   */
  function get_vars() {
    reset($this->varkeys);
    while(list($k, $v) = each($this->varkeys)) {
      $result[$k] = $this->varvals[$k];
    }
    
    return $result;
  }
  
  /* public: get_var(string varname)
   * varname: name of variable.
   *
   * public: get_var(array varname)
   * varname: array of variable names
   */
  function get_var($varname) {
    if (!is_array($varname)) {
      $var1 = (isset($this->varvals[$varname])?$this->varvals[$varname]:"");
      return $var1;

    }
    else {
      reset($varname);
      while(list($k, $v) = each($varname)) {
        $result[$k] = $this->varvals[$k];
      }
      
      return $result;
    }
  }
  
  /* public: get_undefined($handle)
   * handle: handle of a template.
   */
  function get_undefined($handle) {
    if (!$this->loadfile($handle)) {
      $this->halt("get_undefined: unable to load $handle.");
      return false;
    }
    
    preg_match_all("/\{([^}]+)\}/", $this->get_var($handle), $m);
    $m = $m[1];
    if (!is_array($m))
      return false;

    reset($m);
    while(list($k, $v) = each($m)) {
      if (!isset($this->varkeys[$v]))
        $result[$v] = $v;
    }
    
    if (count($result))
      return $result;
    else
      return false;
  }

  /* public: finish(string $str)
   * str: string to finish.
   */
  function finish($str) {
    switch ($this->unknowns) {
      case "keep":
      break;
      
      case "remove":
        $str = preg_replace("/\{[^}]+\}/", "", $str);
      break;

      case "comment":
        $str = preg_replace("/\{([^}]+)\}/", "<!-- Template $handle: Variable \\1 undefined -->", $str);
      break;
    }
    
    //    Devuelvo a la vida los caracteres defectuosos
    $str = str_replace('/***peso***/', '$', $str);
    $str = str_replace('/***llave***/', '{', $str);
    return $str;
  }

  /* public: p(string $varname)
   * varname: name of variable to print.
   */
  function p($varname) {
     global $isLocal;
     if($isLocal){
     print utf8_encode($this->finish($this->get_var($varname)));
     } else {
     print $this->finish($this->get_var($varname));
     }
  }

  function get($varname) {
    return $this->finish($this->get_var($varname));
  }
    
  /***************************************************************************/
  /* private: filename($filename)
   * filename: name to be completed.
   */
  function filename($filename) {
    //if (substr($filename, 0, 1) != "/" & substr($filename, 0, 3) != '../') {
    //  $filename = $this->root."/".$filename;
    //}
    if (!file_exists($filename))
        $filename = $this->root."/".$filename;
    
    if (!file_exists($filename))
      $this->halt("filename: file $filename does not exist.");

    return $filename;
  }
  
  /* private: varname($varname)
   * varname: name of a replacement variable to be protected.
   */
  function varname($varname) {
    return preg_quote("{".$varname."}");
  }

  /* private: loadfile(string $handle)
   * handle:  load file defined by handle, if it is not loaded yet.
   */
  function loadfile($handle) {

    $var1 = (isset($this->varkeys[$handle]))?$this->varkeys[$handle]:"";
    //$var2 = (isset($this->varvals[$handle]))?$this->varvals[$handle]:"";

    if ($var1 and !empty($var1))
      return true;

    if (!isset($this->file[$handle])) {
      $this->halt("loadfile: $handle is not a valid handle.");
      return false;
    }
    $filename = $this->filename($this->file[$handle]);

    $str = implode("", @file($filename));
    $str = str_replace( '{ ', '/***llave***/',$str);
    $str = str_replace( "{\t", '/***llave***/',$str);
    $str = str_replace( "{\n", '/***llave***/',$str);
    $str = str_replace( "{\r", '/***llave***/',$str);
    if (empty($str)) {
      $this->halt("loadfile: While loading $handle, $filename does not exist or is empty.");
      return false;
    }

    $this->set_var($handle, $str);
    
    return true;
  }

  /***************************************************************************/
  /* public: halt(string $msg)
   * msg:    error message to show.
   */
  function halt($msg) {
    $this->last_error = $msg;
    
    if ($this->halt_on_error != "no")
      $this->haltmsg($msg);
    
    if ($this->halt_on_error == "yes")
      die("<b>Halted.</b>");
    
    return false;
  }
  
  /* public, override: haltmsg($msg)
   * msg: error message to show.
   */
  function haltmsg($msg) {
    printf("<b>Template Error:</b> %s<br>\n", $msg);
  }
}



/* TEST
$test_page = new Page("test_page.html", "page.html", "");

$array = array( "name1"=>"Beelzebub", "name2"=>"Mammon", "name3"=>"Lucifer",
                "value1"=>"2nd in Command", "value2"=>"Demon of Greed", "value3"=>"Leader of the Revolt. former archangel" );

$test_page -> add_fields($array);
$test_page -> display_output();

*/


?>
