<?php

namespace Library;

use DateTime;
use Library\Database;
use Library\Holiday;

class Calendar {

    protected $date = \NULL;
    protected $page = 0;
    public $output = \NULL;
    protected $username = \NULL;
    protected $user_id = \NULL;
    protected $query = \NULL;
    protected $stmt = \NULL;
    protected $urlDate = \NULL;
    protected $sendDate = \NULL;
    protected $prev = \NULL;
    public $current = \NULL;
    protected $next = \NULL;
    protected $month = \NULL;
    protected $day = \NULL;
    protected $year = \NULL;
    protected $days = \NULL;
    protected $currentDay = \NULL;
    protected $highlightToday = \NULL;
    protected $highlightHoliday = \NULL;
    protected $isHoliday = \NULL;
    protected $prevMonth = \NULL;
    protected $nextMonth = \NULL;
    public $selectedMonth = \NULL;
    public $n = \NULL;
    public $z = 0;
    public $result = \NULL;
    public $tab = "\t"; // Tab 2 spaces over;
    public $calendar = []; // The HTML Calender:
    protected $holiday = [];
    protected $alphaDay = [0 => "Sun", 1 => "Mon", 2 => "Tue", 3 => "Wed", 4 => "Thu", 5 => "Fri", 6 => "Sat"];
    protected $smallDays = [0 => "S", 1 => "M", 2 => "T", 3 => "W", 4 => "T", 5 => "F", 6 => "S"];
    protected $imporantDates = [];
    protected $myPage = \NULL;
    protected $now = \NULL;
    protected $monthlyChange = \NULL;
    protected $pageName = "index";

    /* Constructor to create the calendar */

    public function __construct($date = null) {
        $this->selectedMonth = new \DateTime($date, new \DateTimeZone("America/Detroit"));
        $this->current = new \DateTime($date, new \DateTimeZone("America/Detroit"));
        $this->current->modify("first day of this month");
        $this->n = $this->current->format("n"); // Current Month as a number (1-12):
    }

    public function set_user_id($user_id = -1) {
        $this->user_id = $user_id;
    }

    public function checkIsAValidDate($myDateString) {
        return (bool) strtotime($myDateString);
    }

    public function phpDate() {
        $setDate = filter_input(INPUT_GET, 'location', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $valid = $this->checkIsAValidDate($setDate);
        if (isset($setDate) && strlen($setDate) === 10 && $valid) {
            self::__construct($setDate);
        }
    }

    public function returnDate() {
        return $this->selectedMonth;
    }

    public function getHolidayNames() {
        return $this->isHoliday->checkForHoliday($this->selectedMonth->format('Y-m-j'));
    }

    /*
     * Not Currently Being Used:
     */

    protected function checkForEntry($calDate) {
        $blog = "blog.php";
        $db = Database::getInstance();
        $pdo = $db->getConnection();
        $this->username = isset($_SESSION['user']) ? $_SESSION['user']->username : \NULL;

        $this->query = 'SELECT 1 FROM cms WHERE page_name=:page_name AND DATE_FORMAT(date_added, "%Y-%m-%d")=:date_added AND user_id=:user_id';

        $this->stmt = $pdo->prepare($this->query);

        $this->stmt->execute([':page_name' => $blog, ':date_added' => $calDate, ':user_id' => $this->user_id]);

        $this->result = $this->stmt->fetch();

        /* If result is true there is data in day, otherwise no data */
        if ($this->result) {
            return \TRUE;
        } else {
            return \FALSE;
        }
    }

    protected function isItToday() {
        /*
         * If selected month (user) equals today's date then highlight the day, if
         * not then treat it as a normal day to be displayed.
         */
        if ($this->now->format("F j, Y") === $this->current->format("F j, Y")) {
            $this->calendar[$this->z]['class'] = 'item today';
            $this->calendar[$this->z]['date'] = $this->current->format("j");
        } else {
            $this->todaysSquares(); // Regular Reg. Boxes - Lt. Green Holidays
        }
    }

    protected function todaysSquares() {
        /*
         * Determine if just a regular day or if it's a holiday.
         */
        if (array_key_exists($this->current->format("Y-m-d"), $this->holiday[0])) {
            $this->calendar[$this->z]['class'] = 'item holiday';
            $this->calendar[$this->z]['date'] = $this->current->format("j");
        } else {
            $this->calendar[$this->z]['class'] = 'item date';
            $this->calendar[$this->z]['date'] = $this->current->format("j");
        }
    }

    protected function drawDays() {

        $this->now = new \DateTime("Now", new \DateTimeZone("America/Detroit"));
        //echo "<pre>" . print_r($this->holiday, 1) . "</pre>";
        $x = 1;
        while ($x <= 7) {
            /*
             * Determine if selected month (user) equal current month to be
             * displayed. If it is proceed with check, if not the fade the box,
             * so that the user will know that it is not the month currently being
             * displayed.
             */
            if ($this->selectedMonth->format('n') === $this->current->format('n')) {
                $this->isItToday();
            } else {
                /*
                 * Fade out previous and next month's dates
                 */
                $this->calendar[$this->z]['class'] = 'item prev-date';
                $this->calendar[$this->z]['date'] = $this->current->format("j");
            }

            $this->current->modify("+1 day");
            $x += 1;
            $this->z += 1;
        }
    }

    protected function controls() {
        $this->monthlyChange = new DateTime($this->current->format("F j, Y"));
        $this->monthlyChange->modify("-1 month");
        $this->prev = $this->monthlyChange->format("Y-m-d");
        $this->monthlyChange->modify("+2 month");
        $this->next = $this->monthlyChange->format("Y-m-d");
        /* Create heading controls for the calendar */
        $this->calendar[$this->z]['previous'] = $this->pageName . '?location=' . $this->prev;

        $this->calendar[$this->z]['next'] = $this->pageName . '?location=' . $this->next;
    }

    protected function display($pageName) {
        $holidayCheck = new Holiday($this->current->format("F j, Y"), 1);

        $this->holiday[] = $holidayCheck->holidays();
        $this->pageName = $pageName;
        $this->controls();

        $this->calendar[$this->z]['month'] = $this->current->format('F Y');


        /* Generate last Sunday of previous Month */
        $this->current->modify("last sun of previous month");

        /*
         * Output 6 rows (42 days) guarantees an even calendar that will
         * display nicely.
         */
        $num = 1;
        while ($num <= 6) {
            $this->drawDays();
            $num += 1;
        }

        return $this->calendar;
    }

    public function generateCalendar(string $pageName = "index") {
        return $this->display($pageName);
    }

}
