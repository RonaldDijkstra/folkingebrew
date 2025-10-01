<?php
namespace Custom\Setup\GravityForms\Components;

class ProgressStepsComponent implements ComponentInterface
{
    /**
     * Render the progress steps component
     *
     * @param array $data The data needed to render the progress steps
     * @return string The rendered HTML
     */
    public function render(array $data = []): string
    {
        if (!$this->shouldRender($data)) {
            return '';
        }

        $form = $data['form'] ?? [];
        $currentPage = $data['current_page'] ?? 1;
        $totalPages = $data['total_pages'] ?? 1;
        $steps = $data['steps'] ?? [];

        return view('gravity.progress-steps', [
            'form' => $form,
            'steps' => $steps,
            'is_confirmation' => $data['is_confirmation'] ?? false,
        ])->render();
    }

    /**
     * Check if the progress steps should be rendered
     *
     * @param array $data The data to check
     * @return bool Whether the progress steps should be rendered
     */
    public function shouldRender(array $data = []): bool
    {
        // Only render if we have a multi-step form
        $totalPages = $data['total_pages'] ?? 1;
        return $totalPages > 1 && !is_admin();
    }
}
