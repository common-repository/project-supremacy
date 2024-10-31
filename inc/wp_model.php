<?php

if ( ! class_exists( 'MPRS_Model' ) ) {

	class MPRS_Model {

        public static function insertData( $data, $table = false ) {
            global $wpdb;

            if(!$table){
                $table = static::TABLE_NAME;
            }

            $query  = "INSERT INTO " . $table . " ";
            $names  = '(';
            $values = '(';
            foreach ( $data as $k => $v ) {

	            $v = esc_sql($v);

                $names .= '`' . $k . '`,';
                $values .= "'$v',";
            }
            $names  = rtrim( $names, ',' ) . ')';
            $values = rtrim( $values, ',' ) . ')';
            $query .= $names . ' VALUES ' . $values;

            try {
                $wpdb->query( $query );
                $index = (int)$wpdb->get_var( 'SELECT LAST_INSERT_ID();' );
            } catch ( Exception $ex ) {
                return $ex->getMessage();
            }

            return $index;
        }

        public static function querySingle( $query ) {
            global $wpdb;
            $query = str_replace('TABLE_NAME', static::TABLE_NAME, $query);
            $results = $wpdb->get_results($query, ARRAY_A);

            if(is_array($results))
                return $results[0];
            else
                return false;
        }

        public static function query( $query, $table = false ) {
            global $wpdb;
	        if ($table !== false) {
		        $query = str_replace('{table}', $table, $query);
	        } else {
		        $query = str_replace('{table}', static::TABLE_NAME, $query);
	        }
            $results = $wpdb->get_results($query, ARRAY_A);

            if(is_array($results))
                return $results;
            else
                return false;
        }

		public static function getData(
			$what     = '*',
			$where    = false,
			$group_by = false,
			$limit    = false,
			$table    = false
		) {
			global $wpdb;
			if(!$table){
				$table = static::TABLE_NAME;
			}

			if ($what != '*')  {
				if (is_array($what)) {
					$what = join( ',', $what );
				}
			}

			$query = "SELECT {$what} FROM " . $table;

			if ($where != false) {
				$query .= " WHERE ";
				$c = 0;
				foreach ($where as $k => $v) {
					$c++;
					$AND = ' AND ';
					if ($c == sizeof($where)) $AND = '';
					$query .= "`$k` = '$v'$AND";
				}
			}

			if ($group_by != false) {
				$query .= ' GROUP BY ' . $group_by;
			}
			if ($limit != false) {
				$query .= ' LIMIT ' . $limit;
			}

			$results = $wpdb->get_results($query, ARRAY_A);

			if(is_array($results)) {
				if (sizeof($results) == 1) {
					return $results[0];
				} else if(sizeof($results) == 0) {
					return false;
				}
				return $results;
			} else {
				return false;
			}
		}

		public static function getDataLike(
			$what     = '*',
			$where    = false,
			$group_by = false,
			$limit    = false,
			$table    = false,
			&$count   = false
		) {
			global $wpdb;
			if(!$table){
				$table = static::TABLE_NAME;
			}

			if ($what != '*')  {
				if (is_array($what)) {
					$what = join( ',', $what );
				}
			}

			$query = '';

			if ($where != false) {
				$where_query = " WHERE ";
				$c = 0;
				foreach ($where as $k => $v) {
					$c++;
					if (empty($v)) continue;
					$AND = ' AND ';
					if ($c == sizeof($where)) $AND = '';
					$where_query .= "`$k` LIKE '%$v%'$AND";
				}
				if ($where_query != " WHERE ") {
					$where_query = rtrim($where_query, ' AND ');
					$query .= $where_query;
				}
			}

			if ($group_by != false) {
				$query .= ' GROUP BY ' . $group_by;
			}
			if ($limit != false) {
				$query .= ' LIMIT ' . $limit;
			}

			$queryResults       = "SELECT SQL_CALC_FOUND_ROWS {$what} FROM " . $table . $query;
			$queryFilter        = "SELECT FOUND_ROWS()";

			$results       = $wpdb->get_results($queryResults, ARRAY_A);
			$resultsFilter = $wpdb->get_results( $queryFilter, ARRAY_A );
			$count         = $resultsFilter[0]['FOUND_ROWS()'];

			if(is_array($results)) {
				if (sizeof($results) == 1) {
					return $results[0];
				} else if(sizeof($results) == 0) {
					return false;
				}
				return $results;
			} else {
				return false;
			}
		}

		public static function getAllData($table = false) {
			global $wpdb;
			if(!$table){
				$table = static::TABLE_NAME;
			}
			$query = "SELECT * FROM " . $table;
			$results = $wpdb->get_results($query, ARRAY_A);

			if(is_array($results))
				return $results;
			else
				return array();
		}

        public static function updateData($what, $where, $table = false) {
            global $wpdb;

            if(!$table){
                $table = static::TABLE_NAME;
            }

            $query = "UPDATE ".$table." SET ";
            foreach ($what as $k => $v) {
                $v = sanitize_text_field($v);
                $query .= "`$k` = '$v',";
            }
            $query = rtrim($query, ',');
            $query .= " WHERE ";
            $c = 0;
            foreach ($where as $k => $v) {
                $c++;
                $AND = ' AND ';
                if ($c == sizeof($where)) $AND = '';
                if (!is_array($v)) {
	                $query .= "`$k` = '$v'$AND";
                } else {
	                $query .= "`$k` IN ('" . join("','", $v) . "')$AND";
                }
            }

	        try {
		        $wpdb->query( $query );
		        return true;
	        } catch ( Exception $ex ) {
		        return $ex->getMessage();
	        }
        }

        public static function truncate($table = false) {
	        global $wpdb;

	        if(!$table){
		        $table = static::TABLE_NAME;
	        }

	        $wpdb->query( 'TRUNCATE TABLE ' .$table );
        }

		public static function removeData($where, $table = false) {
			global $wpdb;
			$c = 0;

            if(!$table){
                $table = static::TABLE_NAME;
            }
			$query = 'DELETE FROM ' . $table . ' WHERE ';
			foreach ($where as $k => $v) {
				$c++;
				$AND = ' AND ';
				if ($c == sizeof($where)) $AND = '';
				if (!is_array($v)) {
					$query .= "`$k` = '$v'$AND";
				} else {
					$query .= "`$k` IN ('" . join("','", $v) . "')$AND";
				}
			}
			$wpdb->query($query);
		}

        public static function getCount($where = null) {
            global $wpdb;
            $query = 'select count(*) from ' . static::TABLE_NAME;
	        if ($where != null) {
		        $query .= " WHERE ";
		        $c = 0;
	        	foreach ($where as $k => $v) {
			        $c++;
			        $AND = ' AND ';
			        if ($c == sizeof($where)) $AND = '';
			        $query .= "`$k` = '$v'$AND";
		        }
	        }
            $count = $wpdb->get_var($query);
            return $count;
        }

	}

}