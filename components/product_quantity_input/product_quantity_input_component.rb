module Components
  module ProductQuantityInput
    class ProductQuantityInputComponent < Middleman::Extension
      helpers do
        def product_quantity_input(_opts = {})
          content_tag(:input, nil, type: 'number', id: 'quantity', name: 'quantity', pattern: '[0-9]*',
                                 value: '1', min: '1', class: input_classes)
        end
      end

      helpers do
        def input_classes
          'text-center px-8 border border-border-gray flex-grow-0 flex-shrink-0 w-28
           focus:border-green-default focus:ring-green-default'
        end
      end
    end
  end
end

::Middleman::Extensions.register(:product_quantity_input_component,
                               Components::ProductQuantityInput::ProductQuantityInputComponent)
