module Components
  module ProductQuantityButton
    class ProductQuantityButtonComponent < Middleman::Extension
      helpers do
        def product_quantity_button(opts, &block)
          additional_classes = opts.dig(:html, :class) ? " #{opts[:html][:class]}" : ''
          combined_classes = "absolute top-0 bottom-0 border-border-gray text-center w-8
                              focus:border-green-default #{additional_classes}"

          content_tag(:button, id: opts[:id], class: combined_classes.to_s, "aria-label": opts[:html][:"aria-label"],
                      &block)
        end
      end
    end
  end
end

::Middleman::Extensions.register(:product_quantity_button_component,
                                 Components::ProductQuantityButton::ProductQuantityButtonComponent)
