<?php
namespace Custom\Setup\GravityForms\Components;

class ValidationMessageComponent implements ComponentInterface
{
    /**
     * Render the validation message component
     *
     * @param array $data The data needed to render the validation message
     * @return string The rendered HTML
     */
    public function render(array $data = []): string
    {
        if (!$this->shouldRender($data)) {
            return '';
        }

        $message = $data['message'] ?? '';
        $type = $data['type'] ?? 'error';
        $form = $data['form'] ?? [];

        return view('gravity.validation-message', [
            'message' => $message,
            'type' => $type,
            'form' => $form,
        ])->render();
    }

    /**
     * Check if the validation message should be rendered
     *
     * @param array $data The data to check
     * @return bool Whether the validation message should be rendered
     */
    public function shouldRender(array $data = []): bool
    {
        $message = $data['message'] ?? '';
        return !empty($message) && !is_admin();
    }
}
