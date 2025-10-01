<?php
namespace Custom\Setup\GravityForms\Components;

class ProgressBarComponent implements ComponentInterface
{
    /**
     * Render the progress bar component
     *
     * @param array $data The data needed to render the progress bar
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
        $progressPercentage = $totalPages > 0 ? ($currentPage / $totalPages) * 100 : 0;

        return view('gravity.progress-bar', [
            'form' => $form,
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
            'percentage' => $progressPercentage,
            'is_confirmation' => $data['is_confirmation'] ?? false,
            'confirmation_message' => $data['confirmation_message'] ?? '',
        ])->render();
    }

    /**
     * Check if the progress bar should be rendered
     *
     * @param array $data The data to check
     * @return bool Whether the progress bar should be rendered
     */
    public function shouldRender(array $data = []): bool
    {
        // Only render if we have a multi-step form
        $totalPages = $data['total_pages'] ?? 1;
        return $totalPages > 1 && !is_admin();
    }
}
