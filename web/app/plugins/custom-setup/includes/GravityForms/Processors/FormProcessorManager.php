<?php
namespace Custom\Setup\GravityForms\Processors;

class FormProcessorManager
{
    private array $processors = [];

    public function __construct()
    {
        $this->registerDefaultProcessors();
        $this->registerHooks();
    }

    /**
     * Register the default processors
     */
    private function registerDefaultProcessors(): void
    {
        $this->registerProcessor(new DateFieldProcessor());
        $this->registerProcessor(new TimeFieldProcessor());
        $this->registerProcessor(new AddressFieldProcessor());
    }

    /**
     * Register a processor
     */
    public function registerProcessor(FieldProcessorInterface $processor): void
    {
        $this->processors[] = $processor;

        // Sort processors by priority
        usort($this->processors, function($a, $b) {
            return $a->getPriority() <=> $b->getPriority();
        });
    }

    /**
     * Register WordPress hooks for form processing
     */
    private function registerHooks(): void
    {
        // Register form pre-submission processing
        add_action('gform_pre_submission', [$this, 'processForm'], 10, 1);
    }

    /**
     * Process a form before submission
     */
    public function processForm(array $form): void
    {
        foreach ($this->processors as $processor) {
            $processor->process($form);
        }
    }

    /**
     * Get all registered processors
     */
    public function getProcessors(): array
    {
        return $this->processors;
    }

    /**
     * Get processors that support a specific field type
     */
    public function getProcessorsForFieldType(string $fieldType): array
    {
        return array_filter($this->processors, function($processor) use ($fieldType) {
            return $processor->supports($fieldType);
        });
    }
}
