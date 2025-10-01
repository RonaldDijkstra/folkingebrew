<?php
namespace Custom\Setup\GravityForms\Processors;

class DateFieldProcessor implements FieldProcessorInterface
{
    /**
     * Process date fields before submission
     * Combines split datefield inputs (input_{id}_1/2/3) into input_{id} as Y-m-d
     */
    public function process(array $form): void
    {
        if (empty($form['fields']) || !function_exists('rgpost')) {
            return;
        }

        foreach ($form['fields'] as $field) {
            if (($field->type ?? '') !== 'date') {
                continue;
            }

            // Only apply to split datefields (datefield or datedropdown)
            if (!isset($field->dateType) || !in_array($field->dateType, ['datefield', 'datedropdown'], true)) {
                continue;
            }

            $fid = (int) $field->id;
            $m = rgpost('input_' . $fid . '_1');
            $d = rgpost('input_' . $fid . '_2');
            $y = rgpost('input_' . $fid . '_3');

            if ($y !== '' && $m !== '' && $d !== '' && $y !== null && $m !== null && $d !== null) {
                // Zero-pad and assemble
                $yy = (int) $y;
                $mm = str_pad((string) (int) $m, 2, '0', STR_PAD_LEFT);
                $dd = str_pad((string) (int) $d, 2, '0', STR_PAD_LEFT);
                $_POST['input_' . $fid] = sprintf('%04d-%s-%s', $yy, $mm, $dd);
            }
        }
    }

    /**
     * Check if this processor supports the given field type
     */
    public function supports(string $fieldType): bool
    {
        return $fieldType === 'date';
    }

    /**
     * Get the processing priority
     */
    public function getPriority(): int
    {
        return 10;
    }
}
