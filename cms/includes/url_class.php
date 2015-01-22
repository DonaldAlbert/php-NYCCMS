<?php
/**
 * Created by PhpStorm.
 * User: GRE-ENG
 * Date: 1/22/2015
 * Time: 7:08 PM
 */

class URL {
    protected $action;
    protected $type;
    protected $id;

    public function __construct()
    {
        $this->validate_Get("action");
        $this->validate_Get("type");
        $this->validate_Get("id");
    }

    protected function validate_Get($variable)
    {
        if( isset($_GET[$variable]) )
        {
            $this->$variable = $_GET[$variable];
        }
        else
        {
            $this->$variable = "";
        }
    }

    public function GetUrlComponents($indexed=True)
    {
        if( $indexed === True )
        {
            return [
                'action'=>$this->action,
                'type'=>$this->type,
                'id'=>$this->id,
            ];
        } elseif( $indexed === False )
        {
            return [
                $this->action,
                $this->type,
                $this->id,
            ];
        } else
        {
            return False;
        }
    }

    public function build_Path(array $url_components_array)
    {
        $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?';
        $url.='action='.$url_components_array['action'];
        if( isset($url_components_array['type']) )
        {
            $url.='&type='.$url_components_array['type'];
        }
        if( isset($url_components_array['id']) )
        {
            $url.='&id='.$url_components_array['id'];
        }

        return $url;
    }

    public function build_link($link_id, $link_text, array $url_components_array)
    {
        $link = $this->build_Path($url_components_array);
        $link = "<a id='$link_id' href='$link'>$link_text</a>";

        return $link;
    }
}