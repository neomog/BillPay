<?php declare(strict_types=1);

namespace App\classes;

class Utility
{


    /**
     ** convert input array to a csv file and force downlaod the same
     **
     ** should be called before any output is send to the browser
     ** input array should be an associative array
     ** the key of the associative array will be first row of the csv file
     **
     ** @param array $array
     ** @return null
     **/
    public function array_to_csv_download(array &$array) {

        if (count($array) == 0) {
            return null;
        }

        $filename = "msghist_data_export_" . date("Y-m-d") . ".csv";
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");

        $df = fopen("php://output", 'w');
        fputcsv($df, array_keys(reset($array)));
        foreach ($array as $row) {
            fputcsv($df, $row);
        }
        fclose($df);
        die();
    }

    public function string_to_csv_download(string $csvData) {
        if (empty($csvData)) {
            return null;
        }

        $filename = "msghist_data_export_" . date("Y-m-d") . ".csv";

        // Disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // Force download
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // Disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");

        // Calculate and set the content length
        $contentLength = strlen($csvData);
        header("Content-Length: $contentLength");

        // Disable output buffering
        ob_end_clean();

        echo $csvData;
        die();
    }


    public function create_db_table() {
        $servername = "your_server_name";
        $username = "your_username";
        $password = "your_password";
        $dbname = "your_database_name";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

// Get the current date in a format like '2023-10-17'
        $currentDate = date('Y-m-d');

// Use the current date to construct the table name
        $dynamicTableName = "table_" . $currentDate;

// Define your table creation SQL here with the dynamic table name
        $tableCreateSQL = "CREATE TABLE IF NOT EXISTS $dynamicTableName (
    id INT AUTO_INCREMENT PRIMARY KEY,
    column1 VARCHAR(255),
    column2 INT
);";

        if ($conn->query($tableCreateSQL) === TRUE) {
            echo "Table created successfully";
        } else {
            echo "Error creating table: " . $conn->error;
        }

        $conn->close();

    }

    /*****************************************
     * ***************************************
     *  check that request date is > 24hrs
     * to set the table to request
     * ***************************************
     *****************************************/
    public static function check_over_date($dateString): bool
    {

// Convert the date string to a DateTime object
        $date = new DateTime($dateString);

// Get the current date
        $currentDate = new DateTime();

        $yesterday = date('Y-m-d',strtotime("-1 days"));

// Calculate the difference in hours
        $interval = $currentDate->diff($date);
        $hoursDifference = $interval->h + ($interval->d * 24);

        if ($hoursDifference > 24 && $yesterday !== $dateString) {
            return true;
        }

        return false;

    }

    /*****************************************
     * ***************************************
     * compare string to an array for blocked*
     * keywords                              *
     * ***************************************
     *****************************************/
    public function checkKeywordsInString($string, $keywords) {
        $string = strtolower($string);
//        foreach ($keywords as $keyword) {
//            if (strpos($string, $keyword) !== false) {
//                return $keyword; // At least one keyword found
//            }
//        }
        foreach ($keywords as $keyword) {
            $pattern = '/\b' . preg_quote($keyword, '/') . '\b/';
            if (preg_match($pattern, $string)) {
                return $keyword; // Keyword found
            }
        }
        return false; // No keywords found

        // $platform['blocked_msg_keywords'] => coma separated string turned into array
        // trim is used to resolve the error as a result of bug in not detecting some
        // keywords
//        $blocked_keywords = explode(',', strtolower($platform['blocked_msg_keywords']));
//        $blocked_keywords = array_map('trim', $blocked_msg_keywords);
    }

}