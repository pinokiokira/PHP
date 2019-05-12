<?php
function debug($text)
{
    if($_SERVER["HTTP_HOST"] == "localhost")
    {
        print "<pre>";
        print_r($text);
        print "</pre>";
    }
}
function resulset2Array($result)
{
    $arrfieldName=array();
    $recs = array();
    $resultant = array();
    $i = 0;
    while ($i < mysql_num_fields($result))
    {
        $meta = mysql_fetch_field($result, $i);
        $arrfieldName[] = $meta->name;
        $i++;
    }
    $i = 0;
    $tmparray = array();
    while ($row = mysql_fetch_array($result))
    {
        for($r=0;$r < count($arrfieldName);$r++)
        {
            $tmparray[$arrfieldName[$r]] = $row[$r];
            $recs[] = array($arrfieldName[$r] => $row[$r]);
        }
        $resultant[$i] = $tmparray;
        $i++;
    }
    return $resultant;
}