<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SheetData extends Model
{

	/**
	 * 集計データ取得SQLを生成して返却する
	 *
	 * @param integer $sheet_id
	 * @param string $ident_id
	 * @param array $exclude_pair
	 * @return string
	 */
	public static function getSummary($sheet_id, $ident_id, $exclude_pair = null)
	{
		return self::getSummaryDataSql($sheet_id, $ident_id, $exclude_pair);
	}

	/**
	 * クロス集計データ取得SQLを生成して返却する
	 *
	 * @param integer $sheet_id
	 * @param string $ident_id
	 * @param string $cross_id
	 * @param array $exclude_pair
	 * @return string
	 */
	public static function getCrossSummary($sheet_id, $ident_id, $cross_id, $exclude_pair = null)
	{
		return self::getCrossSummaryDataSql($sheet_id, $ident_id, $cross_id, $exclude_pair);
	}

	/**
	 * 集計データ取得用SQLを生成して返却する
	 *
	 * @param integer $sheet_id
	 * @param string $ident_id
	 * @param array $params
	 * @return string
	 */
	private static function getSummaryDataSql($sheet_id, $ident_id, $params = null)
	{
		$condition = '';
		if ($ident_id != null) {
			$condition = ' and t2.ident_id = \'' . $ident_id . '\'';
		}

		$exclude = '';
		if ($params != null && (empty($params) == false)) {
			collect($params)->each(function ($param) use (&$exclude) {
				$exclude .= ' and t1.data_id not in(
					select distinct
						t3.data_id
					from
						sheet_data_details t3
					where
							0 = 0
						and (t3.ident_id = \'' . $param['key'] . '\' and t3.`value` = \'' . $param['val'] . '\')
					)';
			});
		}

		return 'select
				     ident_id
				    ,value
				    ,count(*) as data_count
				from
				    sheet_datas t1
				    inner join
				    sheet_data_details t2
				    on
				            t1.sheet_id = ' . $sheet_id . '
				        and t1.data_id = t2.data_id
				where
				        0 = 0' . $condition . $exclude . '
				group by
				     ident_id
				    ,value';
	}

	/**
	 * クロス集計用SQLを生成して返却する
	 *
	 * @param integer $sheet_id
	 * @param string $ident_id
	 * @param string $cross_id
	 * @param array $params
	 * @return string
	 */
	private static function getCrossSummaryDataSql($sheet_id, $ident_id, $cross_id, $params = null)
	{
		$condition = '';
		if ($ident_id != null) {
			$condition = ' and t02.ident_id = \'' . $ident_id . '\'';
		}

		$cross_condition = '';
		if ($cross_id != null) {
			$cross_condition = ' and ident_id = \'' . $cross_id . '\'';
		}

		$exclude = '';
		if ($params != null && (empty($params) == false)) {
			collect($params)->each(function ($param) use (&$exclude) {
				$exclude .= ' and t01.data_id not in(
					select distinct
						t3.data_id
					from
						sheet_data_details t3
					where
							0 = 0
						and (t3.ident_id = \'' . $param['key'] . '\' and t3.`value` = \'' . $param['val'] . '\')
					)';
			});
		}

		return 'select
					 t1.ident_id        as ident_id
					,t1.value           as value
					,t2.value           as cross_value
					,count(t1.ident_id) as counts
				from
					(
						select
							 t02.data_id
							,t02.ident_id
							,t02.value
						from
							sheet_datas t01
							inner join
							sheet_data_details t02
							on
									t01.sheet_id = ' . $sheet_id . '
								and t01.data_id = t02.data_id
						where
								0 = 0' . $condition . $exclude . '
						group by
							 t02.data_id
							,t02.ident_id
							,t02.value
					) t1
					left outer join
					(
					select
						 data_id
						,ident_id
						,value
					from
						sheet_data_details
					where
						0 = 0' . $cross_condition . '
					) t2
					on
						t1.data_id = t2.data_id
				group by
					 t1.ident_id
					,t1.value
					,t2.value
				order by
					 t1.ident_id
					,t1.value
					,t2.value';
	}
}
