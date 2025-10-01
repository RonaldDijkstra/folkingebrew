<?php
namespace Custom\Setup\GravityForms\Components;

class ComponentManager
{
    private ProgressBarComponent $progressBarComponent;
    private ProgressStepsComponent $progressStepsComponent;
    private ButtonComponent $buttonComponent;
    private ValidationMessageComponent $validationMessageComponent;

    public function __construct()
    {
        $this->progressBarComponent = new ProgressBarComponent();
        $this->progressStepsComponent = new ProgressStepsComponent();
        $this->buttonComponent = new ButtonComponent();
        $this->validationMessageComponent = new ValidationMessageComponent();
    }

    /**
     * Register component hooks
     */
    public function register(): void
    {
        $this->registerHooks();
    }

    /**
     * Register WordPress hooks for all components
     */
    private function registerHooks(): void
    {
        // Submit button rendering
        add_filter('gform_submit_button', [$this, 'renderSubmitButton'], 10, 2);

        // Validation message rendering
        add_filter('gform_validation_message', [$this, 'renderValidationMessage'], 10, 2);

        // Pagination rendering - use the correct Gravity Forms hooks
        add_filter('gform_progress_bar', [$this, 'renderProgressBar'], 10, 3);
        add_filter('gform_progress_steps', [$this, 'renderProgressSteps'], 10, 3);
        add_filter('gform_next_button', [$this, 'renderNextButton'], 10, 2);
        add_filter('gform_previous_button', [$this, 'renderPreviousButton'], 10, 2);
    }

    /**
     * Render submit button using ButtonComponent
     */
    public function renderSubmitButton($button, $form): string
    {
        if (is_admin()) {
            return $button;
        }

        return $this->buttonComponent->render([
            'type' => 'submit',
            'button' => $button,
            'form' => $form,
        ]);
    }

    /**
     * Render validation message using ValidationMessageComponent
     */
    public function renderValidationMessage($message, $form): string
    {
        return $this->validationMessageComponent->render([
            'message' => $message,
            'type' => 'error',
            'form' => $form,
        ]);
    }

    /**
     * Render progress bar using ProgressBarComponent
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

        $result = $this->progressBarComponent->render([
            'form' => $form,
            'current_page' => $current_page,
            'total_pages' => $total_pages,
            'percentage' => $percentage,
            'is_confirmation' => $is_confirmation,
            'confirmation_message' => $confirmationText,
        ]);

        return $result;
    }

    /**
     * Get current page number from form
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
     * Get the next button text for the current page from the page break field
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

    /**
     * Render progress steps using ProgressStepsComponent
     */
    public function renderProgressSteps($progress_steps, $form, $page): string
    {
        error_log('Gravity Forms: renderProgressSteps called for form ID: ' . ($form['id'] ?? 'unknown') . ', page: ' . $page);

        if (is_admin()) {
            return $progress_steps;
        }

        $total_pages = $this->getTotalPages($form);

        // Check if we're on confirmation page (page number might be beyond total pages)
        $is_confirmation = $page > $total_pages;
        $current_page = $is_confirmation ? $total_pages : $page;

        $steps = $this->buildStepsArray($form, $current_page, $is_confirmation);

        return $this->progressStepsComponent->render([
            'form' => $form,
            'current_page' => $current_page,
            'total_pages' => $total_pages,
            'steps' => $steps,
            'is_confirmation' => $is_confirmation,
        ]);
    }

    /**
     * Render next button using ButtonComponent
     */
    public function renderNextButton($button, $form): string
    {
        error_log('Gravity Forms: renderNextButton called for form ID: ' . ($form['id'] ?? 'unknown'));

        if (is_admin()) {
            return $button;
        }

        $current_page = $this->getCurrentPage($form);
        $total_pages = $this->getTotalPages($form);
        $next_button_text = $this->getNextButtonText($form, $current_page);

        $result = $this->buttonComponent->render([
            'type' => 'next',
            'button' => $button,
            'form' => $form,
            'current_page' => $current_page,
            'total_pages' => $total_pages,
            'is_last_page' => $current_page >= $total_pages,
            'next_button_text' => $next_button_text,
        ]);

        error_log('Gravity Forms: Next button render result length: ' . strlen($result));
        return $result;
    }

    /**
     * Render previous button using ButtonComponent
     */
    public function renderPreviousButton($button, $form): string
    {
        error_log('Gravity Forms: renderPreviousButton called for form ID: ' . ($form['id'] ?? 'unknown'));

        if (is_admin()) {
            return $button;
        }

        $current_page = $this->getCurrentPage($form);
        $total_pages = $this->getTotalPages($form);
        $previous_button_text = $this->getPreviousButtonText($form, $current_page);

        $result = $this->buttonComponent->render([
            'type' => 'previous',
            'button' => $button,
            'form' => $form,
            'current_page' => $current_page,
            'total_pages' => $total_pages,
            'is_first_page' => $current_page <= 1,
            'previous_button_text' => $previous_button_text,
        ]);

        error_log('Gravity Forms: Previous button render result length: ' . strlen($result));
        return $result;
    }

    /**
     * Get the progress bar component
     */
    public function getProgressBarComponent(): ProgressBarComponent
    {
        return $this->progressBarComponent;
    }

    /**
     * Get the progress steps component
     */
    public function getProgressStepsComponent(): ProgressStepsComponent
    {
        return $this->progressStepsComponent;
    }

    /**
     * Get the button component
     */
    public function getButtonComponent(): ButtonComponent
    {
        return $this->buttonComponent;
    }

    /**
     * Get the validation message component
     */
    public function getValidationMessageComponent(): ValidationMessageComponent
    {
        return $this->validationMessageComponent;
    }
}
