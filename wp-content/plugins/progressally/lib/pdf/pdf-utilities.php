<?php

class ProgressAllyPdfUtilities {
	private static $FONT_MAPPING = array('helvetica' => 'helvetica', 'courier' => 'courier', 'times' => 'times');
	private static $EXTRA_FONTS = array('Georgia' => 'Georgia', 'Tahoma' => 'Tahoma', 'TrebuchetMS' => 'TrebuchetMS');
	private static function set_font($pdf, $customization) {
		if (isset($customization['select-font'])) {
			if (isset(self::$EXTRA_FONTS[$customization['select-font']])) {
				$pdf->AddFont(self::$EXTRA_FONTS[$customization['select-font']]);
				$pdf->SetFont(self::$EXTRA_FONTS[$customization['select-font']]);
			} elseif (isset(self::$FONT_MAPPING[$customization['select-font']])) {
				$pdf->SetFont(self::$FONT_MAPPING[$customization['select-font']]);
			}
		}
	}
	private static function set_font_size($pdf, $customization) {
		if (isset($customization['font-size'])) {
			$font_size = floatval($customization['font-size']);
			$pdf->SetFontSize($font_size);
		}
	}
	private static function set_color($pdf, $customization) {
		if (isset($customization['color'])) {
			$hex = str_replace('#', '', $customization['color']);
			$r = substr($hex, 0, 2);
			$g = substr($hex, 2, 2);
			$b = substr($hex, 4, 2);
			$pdf->SetTextColor(hexdec($r), hexdec($g), hexdec($b));
		}
	}
	private static function add_text($pdf, $customization) {
		if (isset($customization['value'])) {
			self::set_font($pdf, $customization);
			self::set_color($pdf, $customization);
			self::set_font_size($pdf, $customization);
			$pdf->SetXY($customization['x'], $customization['y']);
			$text_align = 'L';
			if ($customization['select-align'] === 'center') {
				$text_align = 'C';
			} elseif ($customization['select-align'] === 'right') {
				$text_align = 'R';
			}
			$pdf->Cell($customization['w'], 0, $customization['value'], 0, 1, $text_align, false);
		}
	}
	public static function generate_customized_pdf($source_file, $file_name, $customizations) {
		require_once('fpdf181/fpdf.php');
		require_once('FPDI-1.6.1/fpdi.php');
		$pdf = new FPDI();

		$page_count = $pdf->setSourceFile($source_file);
		if ($page_count > 0) {
			$template_id = $pdf->importPage(1);
			$page_size = $pdf->getTemplateSize($template_id);
			if ($page_size['w'] > $page_size['h']) {
				$pdf->AddPage('L', array($page_size['w'], $page_size['h']));
			} else {
				$pdf->AddPage('P', array($page_size['w'], $page_size['h']));
			}

			$pdf->useTemplate($template_id);

			foreach ($customizations as $custom) {
				self::add_text($pdf, $custom);
			}
			$pdf->SetCompression('');
			$pdf->Output('D', $file_name);
		}
	}
	public static function extract_page_one($path, $one_page_path) {
		require_once('fpdf181/fpdf.php');
		require_once('FPDI-1.6.1/fpdi.php');
		$pdf = new FPDI();

		$page_count = $pdf->setSourceFile($path);
		if ($page_count > 1) {
			$template_id = $pdf->importPage(1);
			$page_size = $pdf->getTemplateSize($template_id);
			if ($page_size['w'] > $page_size['h']) {
				$pdf->AddPage('L', array($page_size['w'], $page_size['h']));
			} else {
				$pdf->AddPage('P', array($page_size['w'], $page_size['h']));
			}
			$pdf->useTemplate($template_id);
			$pdf->SetCompression('');
			$pdf->Output('F', $one_page_path);
			return $one_page_path;
		}
		return $path;
	}
	public static function get_file_dimension($path) {
		require_once('fpdf181/fpdf.php');
		require_once('FPDI-1.6.1/fpdi.php');
		$pdf = new FPDI();
		$page_count = $pdf->setSourceFile($path);
		if ($page_count > 0) {
			$template_id = $pdf->importPage(1);
			$page_size = $pdf->getTemplateSize($template_id);
			return array($page_size['w'], $page_size['h']);
		}
		return array(0, 0);
	}
}