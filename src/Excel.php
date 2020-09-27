<?php

namespace Es3\Utility;

class Excel
{
    static function xlswriterMapping(string $filePath, array $mapping, int $skipRows = null, string $sheetName = null, array $xlswriterConfig = []): array
    {
        // 打开文件
        $xlswriterConfig['path'] = '/';
        $excel = new \Vtiful\Kernel\Excel($xlswriterConfig);
        $sheetList = $excel->openFile($filePath)->sheetList();

        /** 获取 sheet */
        $sheetName = superEmpty($sheetName) ? current((array)$sheetList) : $sheetName;
        $excel->openFile($filePath)->openSheet($sheetName, \Vtiful\Kernel\Excel::SKIP_EMPTY_ROW);

        /** 获取结果 */
        $results = [];
        $skipRows ? $excel->setSkipRows($skipRows) : null;
        for ($row = 1 + intval($skipRows); ($rowData = $excel->nextRow()) !== NULL; $row++) {

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