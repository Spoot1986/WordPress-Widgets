<?php

class SP_Widget{

	public $config = array();

	function __construct(){
		add_action('widgets_init', array($this,'sp_init_widget_area'));
	}

	function sp_init_widget_area() {   
		$data = $this->config;

		$values = array(
            'name'          => __('Custom widget', 'spf86'),
            'id'            => 'spf86_widget',
            'description'   => __('Add widgets here', 'spf86'),
            'before_title'  => '',
            'after_title'   => '',
            'before_widget' => '',
            'after_widget'  => '',
        );

        foreach ($values as $key => $value) {
			if (array_key_exists($key, $data)) {
				$values[$key] = $data[$key];
			}
		}

        register_sidebar($values);
    }

	public function sp_register_widget_area($data){
		$this->config = $data;
	}

}

abstract class SP_Widget_ABS extends WP_Widget{

	//show widget(frontend)
    function widget($args, $data) {
    	$this->sp_widget($args, $data);
    }

    //show widget(backend)
    function form($data) {
    	$this->sp_form($data);
    }

    //update new data
    function update($new_data, $old_data) {
        $data = array();

        foreach ($new_data as $key => $value) {
            $data[$key] = $value;
        }

        return $data;
    }

    //fields function (input, select, etc)
    public function sp_add_fields_widget($type, $label, $caption, $values, $id){
        if($type == 'text'){
            echo '<p><label for="'.$this->get_field_id($id).'">'.$label.':</label><br>';
            echo '<input type="text" id="'.$this->get_field_id($id).'" name="'.$this->get_field_name($id).'" value="'.esc_attr($values).'"></p>';
        }

        if($type == 'number'){
            echo '<p><label for="'.$this->get_field_id($id).'">'.$label.':</label><br>';
            echo '<input type="number" class="spnumber" id="'.$this->get_field_id($id).'" name="'.$this->get_field_name($id).'" value="'.esc_attr($values).'">'.$caption.'</p>';
        }

        if($type == 'select'){
            echo '<p><label for="'.$this->get_field_id($id).'">'.$label.':</label><br>';
            echo '<select class="widefat" id="'.$this->get_field_id($id).'" name="'.$this->get_field_name($id).'">';

            $i = 0;
            foreach ($values as $val) {
                   
                if($i == 0){
                	foreach ($values as $fval){
                		if($fval['key'] == $val['key'] && !empty($fval['value'])){
                			echo '<option value="'.$fval['key'].'">'.$fval['value'].'</option>';	
                		}
                	}                	
                }

                $i++; 

                if(!empty($val['value'])){
                	echo '<option value="'.$val['key'].'">'.$val['value'].'</option>';
                }

                if($i == 1){
                    echo '<option disabled>-------</option>';
                } 
            }

            echo '</select>';
        }
    }

    public function sp_get_default_value($data, $key){
        if(isset($data[$key])){
            return $data[$key];
        } else {
            return '';
        }
    }

    function sp_register_widget() {
        register_widget(get_class($this));
    }

    abstract public function sp_widget($args, $data);
    abstract public function sp_form($data);

}
?>
