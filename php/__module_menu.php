<?php
namespace SIM\BANKING;
use SIM;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

const MODULE_VERSION		= '8.1.5';
DEFINE(__NAMESPACE__.'\MODULE_PATH', plugin_dir_path(__DIR__));
DEFINE(__NAMESPACE__.'\MODULE_SLUG', strtolower(basename(dirname(__DIR__))));
DEFINE(__NAMESPACE__.'\STATEMENT_FOLDER', wp_get_upload_dir()["basedir"]."/private/account_statements/");

require( MODULE_PATH  . 'lib/vendor/autoload.php');

add_filter('sim_submenu_banking_options', __NAMESPACE__.'\menuOptions', 10, 2);
function menuOptions($optionsHtml, $settings){
	ob_start();
	?>
	<label>
		<h4>E-mail address</h4>
		This will be used to send e-mail request to forward account statements to post@<?php echo str_replace('https://', '', site_url());?><br>
		<input type="email" name="email" value="<?php echo $settings['email'];?>">
	</label>

	<?php
	return $optionsHtml.ob_get_clean();
}

add_filter('sim_module_banking_after_save', __NAMESPACE__.'\afterUpdate');
function afterUpdate($options){
	SIM\ADMIN\installPlugin('postie/postie.php');

	return $options;
}

add_filter('sim_module_banking_functions', __NAMESPACE__.'\moduleFunctions');
function moduleFunctions($functionHtml){
	processPdf();

	ob_start();
	?>
	<h4>Form import</h4>
	<p>
		Select one or more pdf statements to convert to csv
	</p>
	<form method='POST' enctype="multipart/form-data">
		<label>
			Select one or more files
			<input type='file' name='statements[]' multiple='multiple'>
		</label>
		<br>
		<button type='submit' name='processstatements'>Process files</button>
	</form>

	<?php
	return $functionHtml.ob_get_clean();
}

function processPdf(){
	if(empty($_FILES)){
		return;
	}

	$spreadsheetMain = new Spreadsheet();
	$spreadsheetMain->removeSheetByIndex(0); // Remove default blank sheet

	//Loop over all attachments
	foreach($_FILES['statements']['tmp_name'] as $index => $path){
		$csvFileName			= str_replace(['.pdf', '.tmp'], ['.csv', '.csv'], $path);

		//Read the contents of the attachment
		$result					= SIM\PDFTOEXCEL\readPdf($path,  $csvFileName);		

		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
		$spreadsheet = $reader->load($csvFileName);
		
		// Add all sheets from the current file to the main file
		$i = 0;
		foreach ($spreadsheet->getAllSheets() as $sheet) {
			$title = substr(str_replace('.pdf', '', $_FILES['statements']['name'][$index]) . " - $i", 0, 10);
			$sheet->setTitle($title);
			$spreadsheetMain->addExternalSheet($sheet);
		}
	}

	$writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheetMain);

	$result=$writer->save("d:/test.csv");
	
	SIM\clearOutput();
	ob_start();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header("Content-Disposition: attachment; filename=account_statements.xlsx");
	$writer->save('php://output');
	ob_end_flush();
	exit;
	die();
}
