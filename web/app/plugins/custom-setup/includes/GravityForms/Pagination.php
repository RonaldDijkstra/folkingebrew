<?php

namespace Custom\Setup\GravityForms;

use Custom\Setup\ServiceInterface;

class Pagination implements ServiceInterface
{
    public function register()
    {
        add_filter('gform_progress_bar', [$this, 'renderProgressBar'], 10, 3);
        add_filter('gform_progress_steps', [$this, 'renderProgressSteps'], 10, 3);
        add_filter('gform_next_button', [$this, 'renderNextButton'], 10, 2);
        add_filter('gform_previous_button', [$this, 'renderPreviousButton'], 10, 2);
    }

    /**
     * Render progress bar with Blade template
     *
     * @param string $progress_bar The original progress bar HTML
     * @param array $form The form object
     * @param string $confirmation_message The confirmation message
     * @return string The rendered progress bar HTML
     */
    public function renderProgressBar($progress_bar, $form, $confirmation_message): string
    {
        if (is_admin()) {
            return $progress_bar;
        }

        // Check if we're on confirmation page (confirmation_message is provided)
        $is_confirmation = !empty($confirmation_message);

        // Calculate progress percentage
        $current_page = $is_confirmation ? $this->getTotalPages($form) : $this->getCurrentPage($form);
        $total_pages = $this->getTotalPages($form);
        $percentage = $is_confirmation ? 100 : ($total_pages > 0 ? round(($current_page / $total_pages) * 100) : 0);

        // Extract clean confirmation text if available
        $confirmationText = '';
        if ($is_confirmation) {
            $confirmationText = is_string($confirmation_message)
                ? wp_strip_all_tags($confirmation_message)
                : $confirmation_message;
        }

        // Check if Blade template exists
        if (function_exists('view') && view()->exists('gravity.progress-bar')) {
            return view('gravity.progress-bar', [
                'current_page' => $current_page,
                'total_pages' => $total_pages,
                'percentage' => $percentage,
                'is_confirmation' => $is_confirmation,
                'confirmation_message' => $confirmationText,
            ])->render();
        }

        return $progress_bar;
    }

    /**
     * Render progress steps with Blade template
     *
     * @param string $progress_steps The original progress steps HTML
     * @param array $form The form object
     * @param int $page The current page number
     * @return string The rendered progress steps HTML
     */
    public function renderProgressSteps($progress_steps, $form, $page): string
    {
        if (is_admin()) {
            return $progress_steps;
        }

        $total_pages = $this->getTotalPages($form);

        // Check if we're on confirmation page (page number might be beyond total pages)
        $is_confirmation = $page > $total_pages;
        $current_page = $is_confirmation ? $total_pages : $page;

        $steps = $this->buildStepsArray($form, $current_page, $is_confirmation);

        // Check if Blade template exists
        if (function_exists('view') && view()->exists('gravity.progress-steps')) {
            return view('gravity.progress-steps', [
                'form' => $form,
                'current_page' => $current_page,
                'total_pages' => $total_pages,
                'steps' => $steps,
                'is_confirmation' => $is_confirmation,
            ])->render();
        }

        return $progress_steps;
    }

    /**
     * Get current page number from form
     *
     * @param array $form The form object
     * @return int Current page number
     */
    private function getCurrentPage($form): int
    {
        // Use Gravity Forms' built-in method if available
        if (class_exists('GFFormDisplay')) {
            return \GFFormDisplay::get_current_page($form['id']);
        }

        // Fallback to manual detection
        if (isset($_POST['gform_source_page_number_' . $form['id']])) {
            return (int) $_POST['gform_source_page_number_' . $form['id']];
        }

        if (isset($_POST['gform_target_page_number_' . $form['id']])) {
            return (int) $_POST['gform_target_page_number_' . $form['id']];
        }

        return 1;
    }

    /**
     * Get total number of pages in form
     *
     * @param array $form The form object
     * @return int Total number of pages
     */
    private function getTotalPages($form): int
    {
        if (empty($form['fields'])) {
            return 1;
        }

        $page_count = 1;
        foreach ($form['fields'] as $field) {
            if ($field->type === 'page') {
                $page_count++;
            }
        }

        return $page_count;
    }

    /**
     * Build array of step information
     *
     * @param array $form The form object
     * @param int $current_page Current page number
     * @param bool $is_confirmation Whether we're on confirmation page
     * @return array Array of step information
     */
    private function buildStepsArray($form, $current_page, $is_confirmation = false): array
    {
        $steps = [];
        $total_pages = $this->getTotalPages($form);

        for ($i = 1; $i <= $total_pages; $i++) {
            $steps[] = [
                'number' => $i,
                'is_current' => !$is_confirmation && $i === $current_page,
                'is_completed' => $is_confirmation || $i < $current_page,
                'is_pending' => !$is_confirmation && $i > $current_page,
                'title' => $this->getPageTitle($form, $i),
            ];
        }

        return $steps;
    }

    /**
     * Get page title for a specific page
     *
     * @param array $form The form object
     * @param int $page_number Page number
     * @return string Page title
     */
    private function getPageTitle($form, $page_number): string
    {
        if (empty($form['fields']) || $page_number === 1) {
            return $form['title'] ?? "Step {$page_number}";
        }

        $page_count = 1;
        foreach ($form['fields'] as $field) {
            if ($field->type === 'page') {
                $page_count++;
                if ($page_count === $page_number) {
                    return !empty($field->label) ? $field->label : "Step {$page_number}";
                }
            }
        }

        return "Step {$page_number}";
    }

    /**
     * Render next button with Blade template
     *
     * @param string $button The original button HTML
     * @param array $form The form object
     * @return string The rendered button HTML
     */
    public function renderNextButton($button, $form): string
    {
        if (is_admin()) {
            return $button;
        }

        $current_page = $this->getCurrentPage($form);
        $total_pages = $this->getTotalPages($form);
        $next_button_text = $this->getNextButtonText($form, $current_page);

        return view('gravity.next-button', [
            'button' => $button,
            'form' => $form,
            'current_page' => $current_page,
            'total_pages' => $total_pages,
            'is_last_page' => $current_page >= $total_pages,
            'next_button_text' => $next_button_text,
        ])->render();
    }

    /**
     * Render previous button with Blade template
     *
     * @param string $button The original button HTML
     * @param array $form The form object
     * @return string The rendered button HTML
     */
    public function renderPreviousButton($button, $form): string
    {
        if (is_admin()) {
            return $button;
        }

        $current_page = $this->getCurrentPage($form);
        $total_pages = $this->getTotalPages($form);
        $previous_button_text = $this->getPreviousButtonText($form, $current_page);

        // Check if Blade template exists
        if (function_exists('view') && view()->exists('gravity.previous-button')) {
            return view('gravity.previous-button', [
                'button' => $button,
                'form' => $form,
                'current_page' => $current_page,
                'total_pages' => $total_pages,
                'is_first_page' => $current_page <= 1,
                'previous_button_text' => $previous_button_text,
            ])->render();
        }

        return $button;
    }

    /**
     * Get the next button text for the current page from the page break field
     *
     * @param array $form The form object
     * @param int $current_page Current page number
     * @return string Next button text or default fallback
     */
    private function getNextButtonText($form, $current_page): string
    {
        $page_field = $this->getPageBreakField($form, $current_page, 'next');

        if ($page_field && isset($page_field->nextButton) && !empty($page_field->nextButton['text'])) {
            return $page_field->nextButton['text'];
        }

        // Fallback to default translation
        return __('Next', 'folkingebrew');
    }

    /**
     * Get the previous button text for the current page from the page break field
     *
     * @param array $form The form object
     * @param int $current_page Current page number
     * @return string Previous button text or default fallback
     */
    private function getPreviousButtonText($form, $current_page): string
    {
        $page_field = $this->getPageBreakField($form, $current_page, 'previous');

        if ($page_field && isset($page_field->previousButton) && !empty($page_field->previousButton['text'])) {
            return $page_field->previousButton['text'];
        }

        // Fallback to default translation
        return __('Previous', 'folkingebrew');
    }

    /**
     * Get the page break field for a specific page
     * For "Next" button: gets the page break field that creates the next page
     * For "Previous" button: gets the page break field that created the current page
     *
     * @param array $form The form object
     * @param int $page_number Page number to get the field for
     * @param string $button_type Either 'next' or 'previous'
     * @return object|null Page break field object or null
     */
    private function getPageBreakField($form, $page_number, $button_type = 'next'): ?object
    {
        if (empty($form['fields'])) {
            return null;
        }

        $page_count = 1;
        $target_page = $button_type === 'next' ? $page_number + 1 : $page_number;

        foreach ($form['fields'] as $field) {
            if ($field->type === 'page') {
                $page_count++;
                if ($page_count === $target_page) {
                    return $field;
                }
            }
        }

        return null;
    }
}
