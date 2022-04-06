<?php
require_once('Products.php');

class Output
{

    public function __construct()
    {
        $this->products = new Products();
    }
    public function __destruct()
    {
    }
    //wordt 2d array verwacht
    public function createTable($entries, $ftable)
    {
        $tableheader = false;
        $html = "";
        $html .= '<table class="' . $ftable . '">';
        foreach ($entries as $row) {
            if ($tableheader == false) {
                $html .= "<tr>";
                foreach ($row as $key => $value) {
                    $html .= "<th>{$key}</th>";
                }
                $html .= "<th>actions</th>";
                $html .= "</tr>";
                $tableheader = true;
            }
            $html .= "<tr>";
            foreach ($row as $key => $value) {
                $html .= "<td data-title='{$key}'>{$value}</td>";
            }
            $html .= "<td><a href='index.php?op=read&id=" . $row['product_id'] .
            "'><i class='fa-solid fa-book-open-reader'></i>Read</a><a href='index.php?op=update&id=" . $row['product_id'] . 
            "'><i class='fa-solid fa-highlighter'></i>Update</a><a href='index.php?op=delete&id=" . $row['product_id'] . 
            "'onclick=\"return confirm('Are you sure you want to delete?');\"><i class='fa-solid fa-dumpster-fire'></i>Delete</a></td>";
            $html .= "</tr>";
        }
        $html .= "</table>";
        return $html;
    }
    public function createlist($enteries){
        $html = '<ul>';
        foreach ($enteries as $entery) {
    
            foreach ($entery as  $value) {
                $html .= "<li>" . $value . "</li>";
            }
        }
        $html .= '</ul>';
        return $html;
    }
    public function createPageButtons($pages){
        $html = "";
        for ($i = 1; $i <= $pages; $i++) {
            //ajaxfield
            $html .= "<a href='./index.php?&op=readpage&p=$i'>$i</a>";
        }
        return $html;
    }
    
}

?>