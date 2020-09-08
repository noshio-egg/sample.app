<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * アンケート定義
 *
 * @author noshio
 *
 */
class SheetDef extends Model
{

	/**
	 * sheet_id, def_id より集計対象のアンケート定義を取得して返却する
	 *
	 * @param int $sheet_id
	 * @param int $def_id
	 * @return array
	 */
	public static function getResearchDefinitions($sheet_id, $def_id = null)
	{
		return DB::select(self::generateSql($sheet_id, $def_id));
	}

	/**
	 * sheet_id, def_id より全てのアンケート定義を取得して返却する
	 *
	 * @param int $sheet_id
	 * @param int $def_id
	 * @return array
	 */
	public static function getAllDefinitions($sheet_id, $def_id = null)
	{
		return DB::select(self::generateAllSql($sheet_id, $def_id));
	}

	/**
	 * $sheet_id, $def_id より全てのアンケート定義を取得するSQLを生成し返却する
	 *
	 * @param int $sheet_id
	 * @param int $def_id
	 * @return string
	 */
	private static function generateAllSql($sheet_id, $def_id = null)
	{
		$condition = '';
		if ($def_id != null) {
			$condition = 'and t2.def_id = ' . $def_id;
		}
		return 'select
				     t1.sheet_id
				    ,t2.def_id
				    ,t2.data_type
				    ,t2.title
				    ,t3.value as rows_key
				    ,case
				     when t3.value is null then null
				     else concat(t2.title, \'(\', t3.value, \')\')
				     end as rows_title
				    ,t4.value as item
				from
				    sheets t1
				    inner join
				    sheet_defs t2
				    on
				            t1.sheet_id = ' . $sheet_id . '
				        and t1.sheet_id = t2.sheet_id
				    left outer join
				    sheet_def_attrs t3
				    on
				            t2.sheet_id = t3.sheet_id
				        and t2.def_id = t3.def_id
				        and t3.key = \'ROW\'
				    left outer join
				    sheet_def_attrs t4
				    on
				            t2.sheet_id = t4.sheet_id
				        and t2.def_id = t4.def_id
				        and t4.key = \'LIST\'
				where
				    0 = 0 ' . $condition . '
				order by
				     t2.index
				    ,t3.id
				    ,t4.id';
	}

	/**
	 * $sheet_id, $def_id より集計対象として扱うアンケート定義を取得するSQLを生成し返却する
	 *
	 * @param int $sheet_id
	 * @param int $def_id
	 * @return string
	 */
	private static function generateSql($sheet_id, $def_id = null)
	{
		$condition = '';
		if ($def_id != null) {
			$condition = 'and t2.def_id = ' . $def_id;
		}
		return 'select
				     t1.sheet_id
				    ,t2.def_id
				    ,t2.data_type
				    ,t2.title
				    ,t3.value as rows_key
				    ,case
				     when t3.value is null then null
				     else concat(t2.title, \'(\', t3.value, \')\')
				     end as rows_title
				    ,t4.value as item
				from
				    sheets t1
				    inner join
				    sheet_defs t2
				    on
				            t1.sheet_id = ' . $sheet_id . '
				        and t1.sheet_id = t2.sheet_id
				    left outer join
				    sheet_def_attrs t3
				    on
				            t2.sheet_id = t3.sheet_id
				        and t2.def_id = t3.def_id
				        and t3.key = \'ROW\'
				    inner join
				    sheet_def_attrs t4
				    on
				            t2.sheet_id = t4.sheet_id
				        and t2.def_id = t4.def_id
				        and t4.key = \'LIST\'
				where
				    0 = 0 ' . $condition . '
				order by
				     t2.index
				    ,t3.id
				    ,t4.id';
	}
}
