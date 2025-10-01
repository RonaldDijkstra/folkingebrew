<?php

namespace Custom\Setup\GravityForms\FieldRenderers;

final class ListFieldRenderer extends BaseFieldRenderer
{
    public function supports(string $fieldType): bool
    {
        return $fieldType === 'list';
    }

    public function getViewName(): string
    {
        return 'gravity.fields.list';
    }

    protected function buildViewModel(object $field, $value, int $formId, int $leadId): array
    {
        $viewModel = parent::buildViewModel($field, $value, $formId, $leadId);

        // Core GF field settings
        $maxRows       = property_exists($field, 'maxRows') ? (int) $field->maxRows : 0;
        $enableColumns = property_exists($field, 'enableColumns') ? (bool) $field->enableColumns : false;

        // Keep original field meta around if you use it elsewhere
        $viewModel['maxRows']       = $maxRows;
        $viewModel['enableColumns'] = $enableColumns;
        $viewModel['listFields']    = property_exists($field, 'fields') ? (array) $field->fields : [];

        // Columns (labels come from $field->choices for GF List)
        $choices  = (property_exists($field, 'choices') && is_array($field->choices)) ? $field->choices : [];
        $columns  = $this->buildColumns($choices, $enableColumns);
        $colCount = count($columns);

        $viewModel['columns']        = $columns;
        $viewModel['columnCount']    = $colCount;
        $viewModel['hasMultiColumns'] = $enableColumns && $colCount > 1;

        // Existing values (from saved $value or POST fallback)
        $existingValues = $this->extractExistingValues($value, $columns, (int) ($field->id ?? 0));
        if ($maxRows > 0 && count($existingValues) > $maxRows) {
            $existingValues = array_slice($existingValues, 0, $maxRows);
        }
        // Ensure at least one empty row
        if (empty($existingValues)) {
            $existingValues[] = array_fill(0, $colCount, '');
        }
        $viewModel['existingValues'] = $existingValues;

        // Helpers for your Blade view (follow GF’s id/name conventions)
        $fieldId = (int) ($field->id ?? 0);

        $viewModel['makeInputId'] = static function (int $row, int $col) use ($formId, $fieldId): string {
            // e.g. input_3_12_0_1
            return "input_{$formId}_{$fieldId}_{$row}_{$col}";
        };

        $viewModel['makeAriaLabel'] = static function (int $row, array $column, bool $hasMulti): string {
            // "Column Label, Row 1" or "Row 1"
            $base = 'Row ' . ($row + 1);
            return ($hasMulti && !empty($column['label'])) ? ($column['label'] . ', ' . $base) : $base;
        };

        // One name for all cells in GF list fields
        $viewModel['baseInputName'] = "input_{$fieldId}[]";

        // Minimal presentation hints (safe defaults)
        $viewModel['inputBaseCls'] = 'list-input border rounded px-3 py-2 focus:outline-none';
        $viewModel['inputBorder']  = $viewModel['failed'] ? 'border-red-500' : 'border-gray-300';
        $viewModel['cellBaseCls']  = 'gfield_list_group_item gfield_list_cell gform-grid-col';

        return $viewModel;
    }

    /**
     * Build columns from GF "choices". If columns are disabled or choices empty, return a single generic column.
     *
     * @param array<int,array<string,mixed>> $choices
     * @return array<int,array{index:int,label:string,key:string}>
     */
    private function buildColumns(array $choices, bool $enableColumns): array
    {
        if ($enableColumns && !empty($choices)) {
            $out = [];
            foreach (array_values($choices) as $i => $c) {
                $label = (string) ($c['text'] ?? ($c['value'] ?? 'Column ' . ($i + 1)));
                $out[] = [
                    'index' => $i,
                    'label' => $label,
                    'key'   => $this->columnKey($label, $i),
                ];
            }
            return $out;
        }

        // Single-column list
        return [
            [
                'index' => 0,
                'label' => '',
                'key'   => '0',
            ],
        ];
    }

    /**
     * Convert a human label into a stable array key (also falls back to index).
     */
    private function columnKey(string $label, int $index): string
    {
        $key = strtolower(trim($label));
        // Normalize to [a-z0-9_]
        $key = preg_replace('/[^a-z0-9]+/i', '_', $key ?? '') ?? '';
        $key = trim($key, '_');

        return $key !== '' ? $key : (string) $index;
    }

    /**
     * Turn a saved $value or POSTed flat list into a 2D array: rows x columns.
     *
     * @param mixed $value
     * @param array<int,array{index:int,label:string,key:string}> $columns
     * @return array<int,array<int,string>>
     */
    private function extractExistingValues($value, array $columns, int $fieldId): array
    {
        $colCount = count($columns);
        $rows     = [];

        // 1) Saved value from GF entry (usually array of rows)
        if (is_array($value) && !empty($value)) {
            foreach ($value as $rowIndex => $rowData) {
                if (!is_array($rowData)) {
                    continue;
                }

                $row = [];
                foreach ($columns as $i => $col) {
                    // Try exact label, then normalized key, then numeric index
                    $cand = $rowData[$col['label']] ?? $rowData[$col['key']] ?? $rowData[$i] ?? '';
                    $row[$i] = is_scalar($cand) ? (string) $cand : '';
                }
                // Keep row if it has any content, or include to preserve layout
                $rows[$rowIndex] = $row;
            }

            if (!empty($rows)) {
                // Normalize indexes (0..n-1)
                return array_values($rows);
            }
        }

        // 2) POST fallback (GF posts as a flat array input_FIELDID[] row-major)
        if (function_exists('rgpost')) {
            $postValues = rgpost("input_{$fieldId}");
        } else {
            $postValues = $_POST["input_{$fieldId}"] ?? null;
        }

        if (is_array($postValues) && !empty($postValues)) {
            $row = [];
            $rowIndex = 0;
            foreach (array_values($postValues) as $k => $val) {
                $colIndex = $k % $colCount;
                $row[$colIndex] = is_scalar($val) ? (string) $val : '';
                if ($colIndex === $colCount - 1) {
                    $rows[$rowIndex++] = $row;
                    $row = [];
                }
            }
            // If last row incomplete, pad it
            if (!empty($row)) {
                $rows[$rowIndex] = array_replace(array_fill(0, $colCount, ''), $row);
            }

            return array_values($rows);
        }

        return [];
    }
}
