<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class ProgressAllyElementorWidget extends Widget_Base {
    public function get_name() {
        return 'progressally-elementor-widget';
    }

    public function get_title() {
        return 'ProgressAlly';
    }

    public function get_icon() {
        return 'progressally-elementor-widget-icon';
    }

    public function get_categories() {
        return array('accessally-widgets');
    }

    public function get_script_depends() {
        return array();
    }

	private static function convert_gutenberg_to_elementor_array($source_array) {
		$result = array();
		foreach ($source_array as $entry) {
			$result[$entry['value']] = $entry['label'];
		}
		return $result;
	}
	private static function generate_selection_data() {
		$shortcode_type_selections = ProgressAllyGutenberg::generate_shortcode_options();
		$result_type_selection = self::convert_gutenberg_to_elementor_array($shortcode_type_selections);

		$additional_input_dependent = array();
		foreach (ProgressAllyGutenberg::$additional_input_type_config as $key => $config) {
			$additional_input_dependent[$key] = array();
		}
		foreach (ProgressAllyGutenberg::$shortcode_config as $key => $config) {
			foreach ($config['input'] as $input_type) {
				$additional_input_dependent[$input_type] []= $key;
			}
		}
		return array('type' => $result_type_selection, 'additional' => $additional_input_dependent);
	}

    protected function _register_controls() {
        $this->start_controls_section(
            'accessally_widget_title',
            array(
                'label' => 'AccessAlly Control',
            )
        );

		$selection_data = self::generate_selection_data();
        $this->add_control(
            'progressally_element_type',
            array(
                'label' => 'Widget type',
                'type' => Controls_Manager::SELECT2,
                'default' => 'objective-list',
                'options' => $selection_data['type'],
                'multiple' => false
            )
        );
		$additional_dependent = $selection_data['additional'];
		foreach (ProgressAllyGutenberg::$additional_input_type_config as $key => $config) {
			$params = array(
					'label' => $config['label'],
					'type' => Controls_Manager::TEXT,
					'default' => '',
					'label_block' => true,
					'condition' => array('progressally_element_type' => $additional_dependent[$key])
				);

			$this->add_control('progressally_input_' . $key, $params);
		}

        $this->end_controls_section();
	}

    protected function render() {
		try {
			$settings = $this->get_settings_for_display();
			if (empty($settings['progressally_element_type'])) {
				return 'Please select an element type to display';
			}
			$shortcode_type = $settings['progressally_element_type'];
			if (isset(ProgressAllyGutenberg::$shortcode_config[$shortcode_type])) {
				$config = ProgressAllyGutenberg::$shortcode_config[$shortcode_type];
				$code = $config['code'];
				if (!empty($config['input'])) {
					$params = array();
					foreach ($config['input'] as $index) {
						if (isset($settings['progressally_input_' . $index])) {
							$params []= $settings['progressally_input_' . $index];
						} else {
							$params []= '';
						}
					}
					$code = vsprintf($code, $params);
				}
				$code = do_shortcode($code);
				echo $code;
				return;
			}
			echo 'Please select an element type to display';
		} catch (Exception $ex) {
			echo $ex->getMessage();
		}
    }

    protected function content_template() {
    }
}