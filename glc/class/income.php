<?php
if(file_exists(dirname(__FILE__).'/database.php')) require_once(dirname(__FILE__).'/database.php');
/**
* Class for performing registration functions
* Author: Sarah Gregorio <sarahgregorio29@gmail.com>
*/
class Class_Income extends Class_Database
{
    private $board_table = array(1 => 'board', 2 => 'board_second', 3 => 'board_third', 4 => 'board_fourth', 5 => 'board_fifth');

    function __construct($db_con)
    {
        parent::__construct($db_con);
    }

    function update_income($data)
    {
        $sql = sprintf("UPDATE income SET amount = %.2f, reenter = %d, co_comm = %d, other = %d, other_type = '%s' WHERE id = %d", $data['amount'], $data['reenter'], $data['co_comm'], $data['other'], $data['other_type'], $data['income_id']);
        return $this->update($sql);
    }

    function get_total_commission($user_id)
    {
        //All income
        $sql = sprintf("SELECT SUM(amount) as total_income FROM income WHERE user_id = %d AND approved <> 2", $user_id);
        $income = $this->select($sql);
        return $income[0]['total_income'];
    }

    function get_total_pending_commission($user_id)
    {
        require_once(dirname(dirname(__FILE__)).'/config.php');
        $reserve_month = glc_option('reserve_month');
        $pending_commissions = 0;

        $sql = sprintf("SELECT * FROM income WHERE user_id = %d AND amount > 0 AND approved = 0", $user_id);
        $income = $this->select($sql);
        foreach ($income as $key => $row) {
            $rolling_reserve = $this->get_rolling_reserve($row['id']);
            if(!empty($rolling_reserve)):
                $rolling_reserve = $rolling_reserve[0];
                //Check if current date is N months after the commission was created
                $date_created = date('Y-m', strtotime(sprintf('+%d months', $reserve_month), strtotime($rolling_reserve['date_created'])));
                //If current date is equal or less than the reserve created date, deduct the reserve amount
                if(date('Y-m') <= $date_created):
                    $row['amount'] = (float)$row['amount'] - $rolling_reserve['reserve'];
                    $reserved += $rolling_reserve['reserve'];
                endif;
            endif;
            if (($row['approved'] != 1)) $pending_commissions += $row['amount'];
        }
        return $pending_commissions;
    }

    function update_rolling_reserve($data)
    {
        require_once(dirname(dirname(__FILE__)).'/config.php');
        $check = sprintf("SELECT * FROM income_reserve WHERE income_id = %d", $data['income_id']);
        $check = $this->select($check);
        if(!empty($check)):
            $sql = sprintf("UPDATE income_reserve SET income = %.2f, reserve = %.2f WHERE income_id = %d", $data['amount'], $data['rolling_reserve'], $data['income_id']);
            return $this->update($sql);
        else:
            $insert_data = array(
                'income_id' => $data['income_id'],
                'income'    => $data['amount'],
                'reserve'   => $data['rolling_reserve'],
                'reserve_percentage' => glc_option('reserve_percentage'),
                'date_created' => date('Y-m-d H:i:s')
            );
            return $this->insert_rolling_reserve($insert_data);
        endif;
    }

    function get_commission($income_id)
    {
        $sql = sprintf("SELECT *, i.id as income_id FROM income i LEFT JOIN income_reserve ir ON i.id = ir.income_id  WHERE i.id = %d", $income_id);
        return $this->select($sql);
    }

    function get_income_by_level($level, $start_date = "", $end_date = "")
    {
        $where_date = (!empty($start_date) && !empty($end_date)) ? sprintf("AND from_unixtime(time,'%%Y-%%m-%%d') BETWEEN '%s' AND '%s'", $start_date, $end_date) : '';
        $sql = sprintf("SELECT * FROM income WHERE level = %d %s", $level, $where_date);
        return $this->select($sql);
    }

    function get_total_sales($level, $start_date, $end_date)
    {
        $where_date = (!empty($start_date) && !empty($end_date)) ? sprintf("AND DATE(p.date_created) BETWEEN '%s' AND '%s'", $start_date, $end_date) : '';
        $sql = sprintf("SELECT SUM(p.amount) as amount 
                FROM payments p
                INNER JOIN user_membership um
                ON p.user_id = um.user_id
                WHERE um.initial = %d %s", $level+1, $start_date, $end_date, $where_date);
        return $this->select($sql);
    }

    function insert_rolling_reserve($data)
    {
        $sql_array = $this->array_to_sql($data);
        $sql = sprintf('INSERT INTO income_reserve (%s) VALUES (%s)', $sql_array['keys'], $sql_array['values']);
        return $this->insert($sql);
    }

    function get_rolling_reserve($income_id)
    {
        $sql = sprintf("SELECT * FROM income_reserve WHERE income_id = %d", $income_id);
        return $this->select($sql);
    }

    function get_income($income_id)
    {
        $sql = sprintf("SELECT * FROM income WHERE id = %d", $income_id);
        return $this->select($sql);
    }

    function return_commission($data)
    {
        $sql = sprintf("UPDATE income SET amount = %.2f, other = 0, other_type = '' WHERE id = %d", $data['other'], $data['id']);
        return $this->update($sql);
    }

    function insert_commission($data)
    {
        $sql_array = $this->array_to_sql($data);
        $sql = sprintf('INSERT INTO income (%s) VALUES (%s)', $sql_array['keys'], $sql_array['values']);
        return $this->insert($sql);
    }

    function second_step_commission($data)
    {
        $sql_array = $this->array_to_sql($data);
        $sql = sprintf('INSERT INTO income (%s) VALUES (%s)', $sql_array['keys'], $sql_array['values']);
        return $this->insert($sql);
    }

    function has_second_step_commission($user_id, $level)
    {
        $sql = sprintf("SELECT * FROM income_relation WHERE user_id = %d AND level = %d AND third_step_income_id = 0", $user_id, $level);
        return $this->select($sql);
    }

    function income_relation($data)
    {
        $sql_array = $this->array_to_sql($data);
        $sql = sprintf('INSERT INTO income_relation (%s) VALUES (%s)', $sql_array['keys'], $sql_array['values']);
        return $this->insert($sql);
    }

    function update_income_relation($id, $income_id)
    {
        $sql = sprintf("UPDATE income_relation SET third_step_income_id = %d WHERE id = %d", $income_id, $id);
        return $this->update($sql);
    }

    function redirect($uri)
    {
        printf('<script type="text/javascript">window.location = "%s/%s"</script>', parent::$glc_url, $uri);
    }

    function is_partial($income_id)
    {
        $sql = sprintf("SELECT * FROM income_relation WHERE third_step_income_id = %d", $income_id);
        $result = $this->select($sql);
        if(!empty($result)) return true;
        return false;
    }

    function get_available_funds($id)
    {
        require_once(dirname(dirname(__FILE__)).'/config.php');
        $income_class = getInstance('Class_Income');
        $reserve_month = glc_option('reserve_month');
        $approved = 0;
        //Get approved commissions in income table
        $approved_commissions = mysqli_query($GLOBALS["___mysqli_ston"], "select * from income where user_id = $id and approved = 1");
        while($row = mysqli_fetch_array($approved_commissions)){
            //Compute rolling reserve
            $rolling_reserve = $income_class->get_rolling_reserve($row['id']);
            if(!empty($rolling_reserve)):
                $rolling_reserve = $rolling_reserve[0];
                //Check if current date is N months after the commission was created
                $date_created = date('Y-m', strtotime(sprintf('+%d months', $reserve_month), strtotime($rolling_reserve['date_created'])));
                //If current date is equal or less than the reserve created date, deduct the reserve amount
                if(date('Y-m') <= $date_created) $row['amount'] = (float)$row['amount'] - $rolling_reserve['reserve'];
            endif;
            $approved += $row['amount'];
        }

        //Get requested/paid commissions in paid_unpaid table
        $requested_commissions = mysqli_query($GLOBALS["___mysqli_ston"], "select SUM(amount) as amount from paid_unpaid where user_id = $id and paid > -1");
        while($row = mysqli_fetch_array($requested_commissions)){
            $requested = $row['amount'];
        }

        //Get funds used to purchase vouchers
        $purchase_funds = mysqli_query($GLOBALS["___mysqli_ston"], "select SUM(pd.amount) as amount from purchase_details pd INNER JOIN purchases pu ON pu.id = pd.purchase_id where pu.user_id = $id AND pd.payment_method = 1");
        while($row = mysqli_fetch_array($purchase_funds)){
            $purchases = $row['amount'];
        }

        //Return approved commissions less requested/paid commissions
        return (float)$approved - (float)$requested - (float)$purchases;
    }

    function array_to_sql($data)
    {
        $count = count($data); $values = ''; $flag = 0;
        $keys = implode(',', array_keys($data));
        foreach ($data as $key => $value) {
            $values .= sprintf('"%s"%s', $value, ($flag < $count-1) ? ',' : '');
            $flag++;
        }
        return array('keys' => $keys, 'values' => $values);
    }
}