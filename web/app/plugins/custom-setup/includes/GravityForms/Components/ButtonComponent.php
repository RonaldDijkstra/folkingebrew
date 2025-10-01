<?php
namespace Custom\Setup\GravityForms\Components;

class ButtonComponent implements ComponentInterface
{
    /**
     * Render a button component (Next, Previous, or Submit)
     *
     * @param array $data The data needed to render the button
     * @return string The rendered HTML
     */
    public function render(array $data = []): string
    {
        if (!$this->shouldRender($data)) {
            return '';
        }

        $buttonType = $data['type'] ?? 'submit';
        $form = $data['form'] ?? [];
        $button = $data['button'] ?? [];

        switch ($buttonType) {
            case 'next':
                return $this->renderNextButton($data);
            case 'previous':
                return $this->renderPreviousButton($data);
            case 'submit':
            default:
                return $this->renderSubmitButton($data);
        }
    }

    /**
     * Render the submit button
     */
    private function renderSubmitButton(array $data): string
    {
        $form = $data['form'] ?? [];
        $button = $data['button'] ?? [];

        // Extract submit button settings from the form object
        $submitSettings = [
            'inputType' => $form['button']['type'] ?? 'text',
            'text' => $form['button']['text'] ?? 'Submit',
            'imageUrl' => $form['button']['imageUrl'] ?? '',
            'width' => $form['button']['width'] ?? 'auto',
            'location' => $form['button']['location'] ?? 'bottom',
            'id' => $form['button']['id'] ?? '',
            'conditionalLogic' => $form['button']['conditionalLogic'] ?? null,
        ];

        // Process button settings to determine CSS classes and display logic
        $buttonType = $submitSettings['inputType'];
        $buttonText = $submitSettings['text'];
        $buttonWidth = $submitSettings['width'];
        $buttonLocation = $submitSettings['location'];
        $imageUrl = $submitSettings['imageUrl'];

        // Determine CSS classes based on width setting
        $widthClass = $buttonWidth === 'full' ? 'w-full' : 'w-auto';

        // Determine container classes based on location
        $containerClass = $buttonLocation === 'left' ? 'text-left' :
                         ($buttonLocation === 'center' ? 'text-center' :
                         ($buttonLocation === 'right' ? 'text-right' : ''));

        return view('gravity.submit', [
            'button' => $button,
            'form' => $form,
            'settings' => $submitSettings,
            'buttonType' => $buttonType,
            'buttonText' => $buttonText,
            'buttonWidth' => $buttonWidth,
            'buttonLocation' => $buttonLocation,
            'imageUrl' => $imageUrl,
            'widthClass' => $widthClass,
            'containerClass' => $containerClass,
        ])->render();
    }

    /**
     * Render the next button
     */
    private function renderNextButton(array $data): string
    {
        $form = $data['form'] ?? [];
        $currentPage = $data['current_page'] ?? 1;
        $totalPages = $data['total_pages'] ?? 1;

        return view('gravity.next-button', [
            'form' => $form,
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
            'next_button_text' => $data['next_button_text'] ?? __('Next', 'folkingebrew'),
        ])->render();
    }

    /**
     * Render the previous button
     */
    private function renderPreviousButton(array $data): string
    {
        $form = $data['form'] ?? [];
        $currentPage = $data['current_page'] ?? 1;

        return view('gravity.previous-button', [
            'form' => $form,
            'current_page' => $currentPage,
            'is_first_page' => $currentPage <= 1,
            'previous_button_text' => $data['previous_button_text'] ?? __('Previous', 'folkingebrew'),
        ])->render();
    }

    /**
     * Check if the button should be rendered
     *
     * @param array $data The data to check
     * @return bool Whether the button should be rendered
     */
    public function shouldRender(array $data = []): bool
    {
        return !is_admin();
    }
}
