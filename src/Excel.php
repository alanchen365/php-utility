<?php

namespace Es3\Utility;

class Excel
{
    static function xlswriterMapping(string $filePath, array $mapping, int $startRow = 2, string $sheetName = null, array $xlswriterConfig = []): array
    {
        // 读取测试文件
        $xlswriterConfig['path'] = '/';
        $excel = new \Vtiful\Kernel\Excel($xlswriterConfig);
        $sheetList = $excel->openFile($filePath)->sheetList();

        $sheetName = superEmpty($sheetName) ? current((array)$sheetList) : $sheetName;
        $excel->openFile($filePath)->openSheet($sheetName, \Vtiful\Kernel\Excel::SKIP_EMPTY_ROW);

        /**  开始行数对外界 */
        $startRow = $startRow - 1;
        $startRow = $startRow > 1 ? 0 : $startRow;

        $results = [];
        for ($row = $startRow; ($rowData = $excel->nextRow()) !== NULL; $row++) {

            /** 如果正常是空的 就过滤掉 */
            if (empty(array_filter($rowData))) {
                continue;
            }

            $tmp = [];
            foreach ($mapping as $column => $key) {
                $column = \Vtiful\Kernel\Excel::columnIndexFromString($column);
                $tmp[$key] = $rowData[$column] ?? null;
            }

            $results[] = $tmp;
        }

        return $results;
    }
}