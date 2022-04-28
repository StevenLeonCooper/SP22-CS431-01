<?php

class Permissions 
{
    public int $view_list;
    public int $view_others;
    public int $modify_others;

    function __construct(int $view_list = 0, int $view_others = 0, int $modify_others = 0) {
        $this->view_list = $view_list;
        $this->view_others = $view_others;
        $this->modify_others = $modify_others;
    }

    public function verify(string $url, array $perm_list = []) 
    {
        $allow = false;

        foreach($perm_list as $item => $perms) {
            
            $route = $perms['route'];

            if(strpos($url, $route)) {
                $vl = $perms['view_list'];
                $vo = $perms['view_others'];
                $mo = $perms['modify_others'];

                if($vl < $this->view_list) continue;
                if($vo < $this->view_others) continue;
                if($mo < $this->modify_others) continue;

                $allow = true;
            }
        }

        return $allow;
    }
}