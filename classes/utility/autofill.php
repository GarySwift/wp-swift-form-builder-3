<?php
/**
 * autosave
 */
class WP_Swift_Autofill
{
    public function run($form_data) {

        $autofill = array();

        foreach ($form_data as $section) {
            foreach ($section["inputs"] as $key => $input) {
                if (isset($input["autofill"]) && $input["autofill"] && $input["clean"]) {

                    if ($input["type"] === "checkbox") {
                        $value = array(
                            "type" => $input["type"],
                            "val" => $input["value"]
                        );                         
                    } else {
                        $value = array(
                            "type" => $input["type"],
                            "val" => $input["clean"]
                        );                        
                    }
                    if (strpos($input["css_class"], 'hide') !== false) {
                        $value["hidden"] = true;
                    }
                    $autofill[$key] = $value;                    
                }
            }
        }

        if (count($autofill)) {
            return $autofill;
        }

        return false;   
    }
}