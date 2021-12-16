<?php

class NumberChain {

    private $values;
    private $chains;
    private $num_memo_vals;
    private $num_memo_chains;
    private $num_89;

    function __construct(){
        $this->values[1] = 1;
        $this->chains[89] = array(145, 42, 20, 4, 16, 37, 89);
        $this->chains[1] = array(1);
        $this->num_memo_vals = 0;
        $this->num_memo_chains = 0;
        $this->num_89 = 0;
    }

    public function incr_num_vals(){
        $this->num_memo_vals++;
    }

    public function incr_num_chains($n){
        $this->num_memo_chains += $n;
    }

    public function incr_num_89(){
        $this->num_89++;
    }

    public function reset_nums(){
        $this->num_memo_vals = 0;
        $this->num_memo_chains = 0;
        $this->num_89 = 0;
    }

    public function get_nums(){
        return array("vals" => $this->num_memo_vals,
        "chains" => $this->num_memo_chains,
        "eightynine" => $this->num_89);
    }

    public function set_value($n, $val){
        $this->values[$n] = $val;
    }

    public function get_value(String $n){

        if(key_exists($n, $this->values)){
            return $this->values[$n];
        }
        return false;
    }

    public function set_chain($n, $val){
        $this->chains[$n] = $val;
    }

    public function get_chain($n){

        if(key_exists($n, $this->chains)){
            return $this->chains[$n];
        }
        return false;
    }

    public function gen_next_number(String $n){

        $val = $this->get_value((int)$n);

        if($val){
            $this->incr_num_vals();
            return $val;
        }

        $len = strlen($n);
        $sum = 0;
    
        for($i = 0; $i < $len; $i++){
            $sum += $n[$i] * $n[$i];
        }

        // Memoize next number mappings
        $this->set_value((int)$n, $sum);
    
        return $sum;
    
    }

    public function gen_num_chain($n){

        $chain = $this->get_chain($n);
        if($chain){
            return $chain;
        }

        $chain = [];
        $tmp = $n;
        $arr = [];

        while(true){
            
            $val = $this->gen_next_number($tmp);

            $arr[] = $val;

            // If the chain exists, store the next value, 
            $chain = $this->get_chain($val);
            if($chain){

                $end = $chain[count($chain) - 1];
                $arr[] = $end;

                if($end == 1 || $end == 89){
                    $this->incr_num_chains(count($chain));
                    

                    $end == 89 ? $this->incr_num_chains(count($chain)) : null;
                    $this->set_chain($n, $arr);

                    return $arr;
                }

            }

            $tmp = $val;

        }
    }

    public function get_range($n){
        $this->reset_nums();

        for($i = 1; $i < $n; $i++){
            $tmp = $this->gen_num_chain($i);
            if($tmp[count($tmp) - 1] == 89){
                $this->incr_num_89();
            }
        }

        $this->print_status();
    }

    public function print_status(){

        $stats = $this->get_nums();

        echo "\n\n#####################################################\n\n";
        echo "Number of memoized values: ", $stats["vals"], "\n";
        echo "Number of memoized chains: ", $stats["chains"], "\n";
        echo "Number of starting values resulting in 89: ", $stats["eightynine"], "\n\n";
        echo "#####################################################\n\n";

    }
        
}



function main() {
    global $argv;
    ini_set("memory_limit", "512M");

    if($argv[1] < 1 || $argv[1] > 1000000){
        echo "Invalid selection, must be between 1 and 1000000";
    }else{
        $num_chain = new NumberChain();

        $num_chain->get_range($argv[1]);
    }

    

    echo "\n";
}

main()

?>