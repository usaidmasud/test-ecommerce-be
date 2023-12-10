<?php

namespace App\Traits;

use App\Http\Resources\Accounting\JournalResource;
use App\Models\Inventory\ItemDetail;
use App\Models\Inventory\PurchaseUnit;
use App\Models\Inventory\SalesUnit;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait HelperTrait
{
  use UploadTrait;

  /**
   * foreignCheck
   *
   * @param  mixed $val
   * @return void
   */
  public static function foreignCheck($val = 0): void
  {
    DB::statement("SET FOREIGN_KEY_CHECKS=$val;");
  }

  /**
   * getFileJson
   *
   * @param  mixed $path
   * @return void
   */
  public function getFileJson($path)
  {
    return json_decode(File::get(base_path("database/json/$path.json")));
  }

  public function getFromTable(string $table, string $where, $value)
  {
    $find = DB::table($table)
      ->where($table . '.' . $where, 'like', '%' . $value);
    return $find;
  }

  public function sum(array $value): int
  {
    return array_sum($value);
  }

  public function helperUpload(Request $request, string $file, string $name, string $path = null): string
  {
    if ($request->has($file)) {
      $image = $request->file($file);
      $name = Str::slug($name) . '_' . time();
      $filePath = $this->uploadOne($image, $path, $name);
      return $filePath;
    }
    return '';
  }

  /**
   * lockTable
   *
   * @param  mixed $table
   * @param  mixed $type
   * @return void
   */
  public function lockTable(String $table, String $type = 'WRITE')
  {
    $sql = "LOCK TABLE {$table} {$type}";
    DB::unprepared($sql);
  }

  public function unlockTable()
  {
    DB::unprepared("UNLOCK TABLES");
  }
}
