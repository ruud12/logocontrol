<?php
/**
 * DokuWiki Plugin logocontrol (Syntax Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Ruud Habing <ruud at habing dot net>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) {
    die();
}

class syntax_plugin_logocontrol extends DokuWiki_Syntax_Plugin
{
    /**
     * @return string Syntax mode type
     */
    public function getType()
    {
        return 'substition';
    }

    /**
     * @return string Paragraph type
     */
    public function getPType()
    {
        return 'block';
    }

    /**
     * @return int Sort order - Low numbers go before high numbers
     */
    public function getSort()
    {
        return 200;
    }

    /**
     * Connect lookup pattern to lexer.
     *
     * @param string $mode Parser mode
     */
    public function connectTo($mode)
    {
        $this->Lexer->addSpecialPattern('<logo.+?</logo>', $mode, 'plugin_logocontrol');
//        $this->Lexer->addEntryPattern('<FIXME>', $mode, 'plugin_logocontrol_logo_control');
    }

//    public function postConnect()
//    {
//        $this->Lexer->addExitPattern('</FIXME>', 'plugin_logocontrol_logo_control');
//    }

    /**
     * Handle matches of the logocontrol syntax
     *
     * @param string       $match   The match of the syntax
     * @param int          $state   The state of the handler
     * @param int          $pos     The position in the document
     * @param Doku_Handler $handler The handler
     *
     * @return array Data for the renderer
     */
    public function handle($match, $state, $pos, Doku_Handler $handler)
    {
        $match = substr(trim($match), 6, -7);
        preg_match_all('/(\w+):[\s]*(-?(?:[0-9]+[\.|,][0-9]+|\w+))/', $match, $matches);
        
        $data = array("type"=>"line");

        $accepted = array(
            "type"=>array(
                "pie",
                "bar",
                "line",
                "gauge"
            ),
            "unit"=>array(
                "V",
                "%",
                "A",
                "bar"
            )
        );

        $accepted_values = array(
            "update_interval"=>array(0.5,10),
            "min"=>array(-100,300),
            "max"=>array(-100,300),
            "width"=>array(100, 2000),
            "height"=>array(100, 2000),
            "value"=>array(0,100),
        );

        for ($i=0; $i < count($matches[1]); $i++) {
            $key = $matches[1][$i];
            $value = $matches[2][$i];
            if (array_key_exists($key, $accepted)) {
                if (in_array($value, $accepted[$key])) {
                    $data[$key] = $value;
                }
            };


            if (array_key_exists($key, $accepted_values)) {
                $value = floatval($value);
                if ($value < $accepted_values[$key][0]) {
                    $data[$key] = $accepted_values[$key][0];
                } elseif ($value > $accepted_values[$key][1]) {
                    $data[$key] = $accepted_values[$key][1];
                } else {
                    $data[$key] = $value;
                }
            }

        }

        $data['unique_id'] = uniqid();

        return $data;
    }

    /**
     * Render xhtml output or metadata
     *
     * @param string        $mode     Renderer mode (supported modes: xhtml)
     * @param Doku_Renderer $renderer The renderer
     * @param array         $data     The data from the handler() function
     *
     * @return bool If rendering was successful.
     */
    public function render($mode, Doku_Renderer $renderer, $data)
    {
        if ($mode !== 'xhtml') {
            return false;
        }

        foreach ($data as $key=>$value) {
            $renderer->doc .= $key."= ".$value."<br>";
            
        }



        /* $height = $data['height'] ? '"height": '.$data['height']:''; */
        /* $width = $data['width'] ? '"width": '.$data['width'].',':''; */
        /* $size = '"size": { '.$width.$height.' }'; */

        /* $size2 = array($data['height'] ? '"height": '.$data['height']:''); */

        /* $renderer->doc .= "<div id='chart'></div>"; */
        /* $renderer->doc .= "<script type='text/javascript'>". */
        /*     "var chart = c3.generate({". */
        /*     "bindto: '#chart',". */
        /*     "data: {". */
        /*       "columns: [". */
        /*         "['data1', 30, 200, 100, 400, 150, 250],". */
        /*         "['data2', 50, 20, 10, 40, 15, 25]". */
        /*         "],". */
        /*         "type: '".$data["type"]."'". */
        /*     "},".$size. */
        /*     "});". */
        /*     "</script>"; */

        file_put_contents("/home/ruud/test.txt",json_encode((array)$data));

        $renderer->doc .= "<div id='chart_".$data['unique_id']."'></div>";
        $renderer->doc .= "<div  style='display:none;' id='chart1_data_".$data['unique_id']."'>".json_encode( (array)$data)."</div>";
        /* $renderer->doc .= '<div style="display:none;" id="chart_data_'.$data['unique_id'].'">'. */
        /*     '{'. */
        /*     '"bindto": "#chart_'.$data['unique_id'].'",'. */
        /*     '"data": {'. */
        /*       '"columns": ['. */
        /*         '["data1", 30, 200, 100, 400, 150, 250],'. */
        /*         '["data2", 50, 20, 10, 40, 105, 25]'. */
        /*         '],'. */
        /*         '"type": "'.$data["type"].'"'. */
        /*     '},'.$size. */
        /*     '}'. */
        /*     '</div>'; */
 
        /* if ($data['update_interval']) { */
        /*     $renderer->doc .="<script type='text/javascript'>". */
        /*         "setTimeout(function() {". */
        /*         "chart.load({". */
        /*         "columns: [". */
        /*         "['data3', 130, -150, 200, 300, -200, 100]". */
        /*         "]". */
        /*         "});". */
        /*         "}, ".($data["update_interval"]*1000).")". */
        /*         "</script>"; */
        /* } */
        
        /* $info = $this->getInfo(); */
//        $renderer->doc .= "<script type='text/javascript' src='".DOKU_BASE."lib/plugins/".$info['base']."/assets/initialize_graphs.js'></script>";

        return true;
    }
}

